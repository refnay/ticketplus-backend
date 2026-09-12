# Ticketplus architecture

Ticketplus is a modular monolith organized first by bounded context and then by
business capability. Each capability is split into `Domain`, `Application`, and
`Infrastructure`.

Every application use case is a direct child of `Application`, named by its
business intent: `Create`, `Search`, `BrowsePublished`, `SearchAvailable`,
`ReportOccupancySummary`, or `OnPaymentApproved`. Actor categories such as
management, customer, or public are not directory levels. This keeps the tree
flat and lets the same authenticated user act as both buyer and company member.
`Port` is retained as the only technical grouping under `Application`.

## Dependency rule

Dependencies point inward:

```text
Infrastructure -> Application -> Domain
```

- `Domain` contains aggregates, value objects, business policies, repository
  interfaces, business exceptions, and domain events.
- `Application` coordinates use cases and owns ports for technical capabilities
  such as mail, files, templates, transactions, and message buses.
- `Infrastructure` contains HTTP controllers, Symfony and Doctrine adapters, and
  external-provider implementations.

`Domain` must not depend on `Application` or `Infrastructure`. `Application`
must not depend on concrete infrastructure or framework classes.

The current shared UUID value object uses `Symfony\Component\Uid` as a small
domain-safe library. This is the only documented framework exception in Domain;
transport, persistence, and Messenger classes remain forbidden there.

## Shared kernel

`Shared/Domain` is intentionally small. It is reserved for concepts genuinely
used by multiple domains, such as base value objects and domain-event contracts.
Technical interfaces belong to `Shared/Application/Port` and are implemented by
adapters under `Shared/Infrastructure`.

## Finders

There are two intentional kinds of finder:

- A fast finder in `Domain/Services` loads an aggregate through a domain
  repository and throws a domain `NotFound` exception. It receives and returns
  domain types and remains in Domain.
- A finder under `Application/Find` is a complete query use case. It may apply
  authorization and map an aggregate to an application response.

A domain finder may not depend on HTTP, sessions, application DTOs, Symfony,
Doctrine, or infrastructure entities.

## Sale references

`Sale/Reference` contains the local read models that Sale needs from other
bounded contexts: events, event days, users, zones, and seats. They are not the
owning Catalog or Account aggregates. Sale owns these representations and their
repository contracts as an anti-corruption boundary.

## Commands and queries

Commands and queries are immutable application messages containing only input
data. The current actor and authorization policies are injected into handlers;
they are not stored inside messages. Controllers only map transport input,
dispatch a message, and build a transport response.

## Actors and authorization

Actors are contextual capabilities, not mutually exclusive user classes:

- Public discovery use cases do not depend on `CurrentActor` and may be used by
  anonymous and authenticated visitors.
- Profile, checkout, customer order, payment, and ticket use cases require only
  an authenticated user and scope owned resources by that user's identifier.
- Backoffice management and reporting use cases require an active membership in
  the currently selected company.
- Company-level changes require the `OWNER` membership role.

`AuthorizationContext` returns already validated identifiers through
`userId()`, `companyId()`, `memberId()`, and `ownerCompanyId()`. The legacy
`UserType` value remains persisted for
compatibility but is not an authorization boundary. Membership, status, role,
resource ownership, and the current company determine access.

Public catalog responses are deliberately separate from management responses.
The public endpoints only expose published events and available inventory:

```text
GET /events
GET /events/{id}
GET /days/{day}/zones
GET /zones/{zone}/seats
```

## Resource identifiers

HTTP inputs contain only the identifier of the target resource or, when a
collection is being created or listed, its immediate parent. Ancestor IDs are
derived from the loaded relation and the current company is always derived from
the authenticated actor.

```text
zones: create -> day; search -> current company (optional day filter); find/update/delete -> zone
seats: create -> zone; search -> current company (optional zone filter); find/update/delete -> seat
orders: create -> day plus item zone/seat identifiers
payments: create -> order; confirm -> payment
tickets: render -> ticket
discounts: create -> event; find/update/delete -> discount
```

This does not weaken tenant isolation. Company-scoped repositories constrain
the resource through Doctrine joins to the actor's current company. Buyer use
cases constrain owned resources by the authenticated user and validate
parent-child relationships after loading each referenced object.

Transport-specific uploaded files are converted by Infrastructure to the
application `FileUpload` input before a command is dispatched.

There are three explicit application ports:

- `CommandBus` executes state-changing use cases.
- `QueryBus` executes read use cases.
- `EventBus` publishes facts for application subscribers.

Symfony Messenger is an infrastructure detail. Each handler is registered only
on its corresponding bus, and event subscribers are registered only on the
event bus.

## Events

Domain events are defined by the domain that owns the fact. Application
subscribers coordinate reactions, while Infrastructure provides the transport.
Application depends on an `EventBus` port and never on Symfony Messenger
directly.

## HTTP errors

Domain and application authorization exceptions do not know about HTTP. An
infrastructure subscriber translates expected failures to JSON responses:
authorization failures use `403`, missing resources `404`, duplicates `409`,
and invalid business states `422`. Unexpected technical exceptions are left to
Symfony's normal `500` handling.

## Persistence boundary

The existing persistence model under `Shared/Infrastructure/Persistence` is a
deliberate compatibility boundary for this refactor. Its entities, Doctrine
mapping configuration, and migrations are not changed. Persistence mappers keep
their mapping logic; only the imports required by the `Sale/Reference` namespace
move are updated.
