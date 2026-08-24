# Ticketplus architecture

Ticketplus is a modular monolith organized first by bounded context and then by
business capability. Each capability is split into `Domain`, `Application`, and
`Infrastructure`.

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

## Commands and queries

Commands and queries are immutable application messages containing only input
data. The current actor and authorization policies are injected into handlers;
they are not stored inside messages. Controllers only map transport input,
dispatch a message, and build a transport response.

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
deliberate compatibility boundary for this refactor. Entities, Doctrine
configuration, mappers, repositories, relation fetchers, and migrations are not
reorganized as part of the application-layer refactor.
