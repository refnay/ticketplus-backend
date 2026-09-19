# Convenciones de desarrollo de Ticketplus

Este archivo es la guía operativa para personas y agentes de IA que creen o
modifiquen código en este repositorio. Describe la arquitectura y las
convenciones que existen actualmente. Antes de implementar un cambio, leer
también `docs/architecture.md` y revisar al menos un caso de uso vecino de la
misma capacidad.

Si el código existente y este documento difieren, no asumir en silencio:
comprobar la configuración y los consumidores, conservar compatibilidad y
actualizar este archivo si la convención realmente cambió.

## 1. Stack y reglas generales

- PHP `>= 8.4`, Symfony `8.1`, Doctrine ORM `3.6` y Symfony Messenger.
- Autoload PSR-4: `App\` corresponde a `src/`.
- El sistema es un monolito modular con DDD, arquitectura hexagonal y CQRS.
- Se usa inyección por constructor, autowiring y autoconfigure de Symfony.
- Una clase por archivo y nombre de archivo igual al nombre de la clase.
- Namespace y ruta deben coincidir exactamente.
- Usar tipos de parámetros, propiedades y retornos. No introducir datos de
  dominio como arrays si ya existe un objeto de dominio que los representa.
- El código actual no declara `strict_types`. No añadirlo aisladamente a
  archivos nuevos; un cambio de esta política debe hacerse para todo el
  proyecto.
- Los nombres de código están en inglés; los mensajes visibles se traducen en
  `translations/messages.es.yaml`.
- Preferir nombres de negocio. No crear carpetas genéricas como `Managers`,
  `Helpers` o `Utils` fuera de las utilidades compartidas ya existentes.
- No modificar entidades Doctrine, mappings ni migraciones como efecto lateral
  de un cambio de dominio. La persistencia heredada es una frontera de
  compatibilidad deliberada.

Comprobaciones mínimas antes de terminar:

```bash
php bin/console lint:container
php vendor/bin/phpstan analyse
php vendor/bin/deptrac analyse
```

No hay una suite PHPUnit configurada actualmente. Para archivos PHP cambiados,
ejecutar además `php -l <archivo>` cuando las comprobaciones completas no sean
posibles.

## 2. Mapa arquitectónico

La estructura principal es:

```text
src/
  Account/               # Company, Member, User
  Catalog/               # Category, Event, Seat, Zone
  Feedback/              # Review
  Sale/                  # Discount, Order, Payment, Ticket y Reference
  Shared/                # kernel compartido transversal
```

Cada capacidad completa sigue:

```text
<Contexto>/<Capacidad>/
  Domain/
  Application/
  Infrastructure/
```

Ejemplo: `App\Catalog\Seat\Domain\Seat` vive en
`src/Catalog/Seat/Domain/Seat.php`.

La regla de dependencias es:

```text
Infrastructure -> Application -> Domain
```

- `Domain` contiene agregados, entidades, value objects, enums, contratos de
  repositorio, servicios de dominio, excepciones de negocio y eventos.
- `Application` contiene casos de uso, comandos, queries, handlers, DTOs de
  respuesta, suscriptores y puertos técnicos.
- `Infrastructure` contiene controladores, comandos de consola, Doctrine,
  Symfony, adaptadores y proveedores externos.
- Domain no depende de Application ni Infrastructure.
- Application no depende de clases concretas de Infrastructure ni del
  framework.
- Infrastructure sí puede depender de todas las capas internas.

`deptrac.yaml` materializa esta regla. La única excepción de framework
permitida en Domain es `Symfony\Component\Uid`, usada por `UuidValueObject`.
Doctrine, HTTP, Messenger, Twig y SDKs externos no entran en Domain.

## 3. Cómo crear un caso de uso (CU)

Un caso de uso vive directamente bajo `Application/<Intención>`. No agregar
una carpeta por actor (`Admin`, `Customer`, `Public`, etc.). La intención va en
PascalCase: `Create`, `Find`, `Search`, `BrowsePublished`, `QuickUpdate`,
`ReportOccupancySummary`, `OnPaymentApproved`.

### 3.1 Escritura: Command + CommandHandler + servicio

Estructura habitual:

```text
Application/Create/
  CreateThingCommand.php
  CreateThingCommandHandler.php
  ThingCreator.php
```

Responsabilidades:

1. El `Command` es un mensaje inmutable de entrada con tipos primitivos o
   inputs de Application. No contiene actor actual, servicios ni lógica de
   negocio.
2. `create(array $data)` usa `PayloadMapper` para JSON. Para identificadores de
   ruta o archivos puede usarse el constructor explícito.
3. El `CommandHandler::__invoke()` obtiene el actor mediante
   `AuthorizationContext`, convierte primitivos a value objects y llama al
   servicio del caso de uso.
4. El servicio (`ThingCreator`, `ThingUpdater`, etc.) orquesta finders,
   invariantes, agregado, repositorio, puertos y eventos.
5. El handler devuelve el resultado que necesita el transporte: normalmente
   `string` para un id creado o `void` para update/delete.

Convención de nombres:

```text
Create<Resource>Command
Create<Resource>CommandHandler
<Resource>Creator
Update<Resource>Command / <Resource>Updater
Delete<Resource>Command / <Resource>Deleter
Cancel<Resource>Command / <Resource>Cancelator  # conservar nombre existente
```

Los handlers deben llamarse exactamente `*CommandHandler`: el compiler pass
`MessageHandlerPass` usa ese sufijo para registrarlos solo en `command_bus`.
Se invocan con `__invoke(Command $command)` y deben tener un único comando.

No inyectar `Request`, `Security`, EntityManager o adaptadores concretos en el
handler o servicio de Application. Tampoco pasar `userId` o `companyId` desde
el body si se pueden derivar del actor.

### 3.2 Lectura: Query + QueryHandler + finder/searcher + Response

Estructura habitual:

```text
Application/Find/
  FindThingQuery.php
  FindThingQueryHandler.php
  ThingFinder.php
  ThingResponse.php

Application/Search/
  SearchThingQuery.php
  SearchThingQueryHandler.php
  ThingSearcher.php
  ThingResponse.php
  ThingsResponse.php
```

- `Find...Query` lleva solo el id objetivo cuando es necesario.
- `Search...Query` extiende `Shared\Application\Query\SearchQuery` cuando usa
  orden y paginación.
- Las queries HTTP se construyen con `fromQuery(array $data)` y
  `PayloadMapper`.
- `filters()` devuelve filtros opcionales; para backoffice recibe o incorpora
  la compañía validada por el handler.
- El handler se llama exactamente `*QueryHandler`, implementa `__invoke()` y
  devuelve un DTO de respuesta.
- El servicio de consulta se llama según intención: `Finder`, `Searcher`,
  `Chooser`, `Reporter` o `Render`.
- Las respuestas implementan `JsonSerializable`; las colecciones contienen
  `total` y una propiedad plural. Construir DTOs desde objetos de dominio en
  `Response::create(...)` cuando la conversión sea reutilizable.
- No devolver agregados directamente desde un controlador.

`SearchQuery` calcula `offset` como `(page - 1) * limit`. Mantener juntos
`searchByFilters()` y `countByFilters()` en el repositorio para que los mismos
filtros produzcan una paginación coherente. Al añadir orden dinámico, validar o
limitar los campos permitidos antes de enviarlos al query builder; nunca
concatenar entrada libre del usuario en DQL/SQL.

Los handlers deben llamarse exactamente `*QueryHandler` para quedar asociados
a `query_bus`.

### 3.3 Finders de Domain frente a Find de Application

Hay dos conceptos distintos:

- `Domain/Services/<Resource>Finder`: recibe value objects, consulta un
  repositorio de dominio, devuelve el agregado y lanza `<Resource>NotFound` si
  no existe.
- `Application/Find/<Resource>Finder`: es parte de un caso de uso completo;
  puede autorizar, coordinar varios dominios y construir un `Response`.

Un finder de Domain no puede conocer HTTP, sesiones, DTOs de Application,
Doctrine ni entidades de persistencia. Para variantes usar nombres explícitos:
`UserByEmailFinder`, `EventByDayFinder`, `SeatByCodeFinder`.

## 4. Mensajes, buses y transacciones

Puertos compartidos:

- `CommandBus::dispatch(object $command): mixed`
- `QueryBus::ask(object $query): mixed`
- `EventBus::publish(object ...$events): void`

Los controladores siempre pasan comandos y queries por estos buses. No deben
invocar directamente handlers ni servicios de aplicación.

Configuración actual de Messenger:

- `command_bus`: síncrono y con middleware `doctrine_transaction`.
- `query_bus`: síncrono.
- `event_bus`: con `doctrine_transaction`.
- Todo subtipo de `Shared\Domain\Events\DomainEvent` se enruta a `async`.

Por lo tanto, no asumir que una reacción a evento terminó antes de responder al
comando. Todo subscriber debe ser seguro ante reintentos y no debe depender del
estado de una request HTTP. Si un flujo requiere atomicidad estricta entre
varios pasos, no esconderla detrás de un evento asíncrono.

`TransactionService` existe para orquestaciones que necesitan control manual
(`begin`, `commit`, `rollback`, `clear`). Preferir la transacción del bus para
un comando normal y usar el servicio explícito solo cuando el caso lo exige.

## 5. Eventos de dominio y subscribers

El evento pertenece al dominio que posee el hecho:

```text
Domain/Events/<PastTense>DomainEvent.php
Application/On<Fact>/<Fact>EventSubscriber.php
```

Ejemplos: `CompanyCreatedDomainEvent`, `PaymentUpdatedDomainEvent`,
`OrderProcessedDomainEvent`.

Reglas actuales:

- Extender `App\Shared\Domain\Events\DomainEvent`.
- El constructor lleva solo escalares serializables; no incluir agregados,
  entidades Doctrine, requests, streams ni servicios.
- Exponer getters sin prefijo `get`: `companyId()`, `paymentId()`.
- Implementar `payload(): array` con todos los datos relevantes.
- Publicar explícitamente desde el servicio de Application, después de
  persistir: `$eventBus->publish(new ...DomainEvent(...));`.
- Los agregados actualmente no acumulan eventos internamente; no introducir un
  mecanismo paralelo de `pullDomainEvents()` en una sola capacidad.
- El subscriber vive en Application, se llama exactamente
  `*EventSubscriber`, recibe un solo tipo de evento en `__invoke()` y convierte
  sus escalares a value objects antes de delegar.
- El sufijo `EventSubscriber` es obligatorio para el registro automático en
  `event_bus`.

Para una reacción entre bounded contexts, consumir el evento público del
contexto dueño. No importar su infraestructura ni sus entidades persistidas.

## 6. Dominio

### 6.1 Agregados y entidades

- Nombre singular (`Event`, `Order`, `Payment`).
- Propiedades privadas y tipadas con value objects.
- El constructor público rehidrata el estado completo desde persistencia.
- La factoría estática `create(...)` genera el id y fija estados/defaults de
  creación.
- Los getters no llevan `get`: `id()`, `status()`, `price()`.
- Los cambios se expresan con verbos de negocio o `change<Field>()`; no crear
  setters genéricos.
- La nulabilidad se encapsula: una propiedad interna puede ser nullable, pero
  su getter suele devolver `Field::fromNull()`.
- Las invariantes se aplican en Domain o en un servicio de dominio, no en el
  controlador ni en el mapper Doctrine.

### 6.2 Value objects

Reutilizar las bases de `Shared/Domain/ValueObjects`:

- `UuidValueObject`
- `StringValueObject`
- `IntValueObject`
- `FloatValueObject`
- `BooleanValueObject`
- `ArrayValueObject`
- `DateValueObject`, `DateTimeValueObject`, `TimeValueObject`

Nombrar el value object con el agregado como prefijo para evitar ambigüedad:
`EventId`, `EventName`, `OrderStatus`, `TicketPrice`.

Usar las factorías existentes según el tipo: `generate()`, `fromString()`,
`fromInt()`, `fromFloat()`, `fromArray()`, `fromDateTime()`, `fromNull()`.
Implementar `validate(): void` y añadir `#[Override]`. Aunque muchos value
objects actuales tengan validación vacía, una nueva invariante propia del valor
debe vivir allí. No duplicarla en controller y handler.

Los ids concretos extienden `UuidValueObject`. Un `UuidValueObject::value()`
puede ser `null`; donde el flujo garantiza un id real, conservar la garantía en
la creación/finder y no propagar `?string` de forma accidental.

Para estados o catálogos cerrados:

```text
<Resource>StatusList   # enum backed: int|string
<Resource>Status       # value object que lo envuelve
```

Usar enums backed y comparar sus `->value`. No usar números o strings mágicos.

### 6.3 Servicios de dominio

Viven en `Domain/Services`, tienen una única responsabilidad y normalmente son
invocables mediante `__invoke()`. Pueden depender de contratos del mismo
Domain y de tipos de dominio, pero no de buses, puertos técnicos o framework.

### 6.4 Excepciones

Las excepciones de negocio viven en `Domain/Exceptions`; las de autorización
transversal en `Shared/Application/Security/Exception`.

Convención:

```php
class ThingNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('thing.thing_not_found', 0, $previous);
    }
}
```

- Nombre específico: `NotFound`, `AlreadyExists`, `NotCreated`, `NotUpdated`,
  `NotDeleted`, `NotAllowed` o una regla de negocio concreta.
- El mensaje de la excepción es una clave estable
  `<recurso>.<error_en_snake_case>`, nunca texto para el usuario.
- Añadir la traducción española en `translations/messages.es.yaml` en el mismo
  cambio.
- Preservar opcionalmente `Throwable $previous`.
- No lanzar excepciones HTTP desde Domain o Application.
- No capturar `Throwable` para ignorarlo. Los repositorios pueden traducir un
  fallo técnico a `NotCreated/NotUpdated/NotDeleted`; una regla esperada debe
  capturar únicamente la excepción concreta.

Comportamiento HTTP real de `ApiExceptionSubscriber` a la fecha:

- `AuthenticationRequired` -> 401.
- `CompanyRequired`, `MemberRequired`, `CompanyOwnerRequired` y
  `UserNotAllowed` -> 403.
- Cualquier clase cuyo nombre termine en `NotFound` -> 404.
- `HttpExceptionInterface` conserva su status.
- Las demás excepciones actualmente -> 500.

No asumir que `AlreadyExists` ya produce 409 o que toda regla de negocio
produce 422: hoy no es así. Si un nuevo caso exige otro status esperado, ampliar
de forma explícita y centralizada `ApiExceptionSubscriber` y documentarlo; no
codificar el status dentro de la excepción de dominio.

## 7. Repositorios y persistencia

### 7.1 Contrato de repositorio

El contrato vive en `Domain/<Resource>Repository.php`, junto al agregado, no en
Application ni Infrastructure. Trabaja con agregados y value objects:

```php
interface ThingRepository
{
    public function save(Thing $thing): void;
    public function update(Thing $thing): void;
    public function delete(Thing $thing): void;
    public function findById(ThingId $id, CompanyId $companyId): ?Thing;
}
```

- El repositorio devuelve `null` al no encontrar; el finder traduce eso a
  `ThingNotFound`.
- Incluir en el contrato solo consultas requeridas por casos reales.
- Métodos expresivos: `findByEmail`, `findPublishedByDayId`,
  `searchByFilters`, `countByFilters`.
- En recursos multi-tenant, toda lectura/mutación de backoffice debe quedar
  acotada por `CompanyId`, directamente o mediante joins.
- Los accesos públicos usan métodos explícitos como `findPublishedById`; no
  reutilizar un finder administrativo omitiendo la compañía.

### 7.2 Adaptador Doctrine

Nombrar `<Resource>DoctrineRepository`, ubicarlo en
`Infrastructure/Persistence` e implementar el contrato con `#[Override]`.
Symfony lo autowirea por interfaz cuando existe una única implementación; para
puertos con varias implementaciones se requiere alias/resolver explícito.

- Recibe `EntityManagerInterface` y el mapper correspondiente.
- `save`: `mapper->newEntity()`, `persist()`, `flush()`.
- `update/delete`: obtiene referencia por id, aplica mapper y hace `flush()`.
- Traduce fallos de escritura a la excepción de dominio correspondiente.
- Las búsquedas convierten entidad a dominio o retornan `null`.
- Usar parámetros enlazados. Para filtros reutilizar
  `Shared\Infrastructure\Persistence\Doctrine\QueryBuilder` o
  `NativeQueryBuilder`.
- El helper omite filtros cuyo valor sea `null`/vacío; tenerlo en cuenta para
  booleanos, cero y paginación.
- `searchByFilters` devuelve agregados; `countByFilters` devuelve `int` y debe
  replicar los joins/filtros de búsqueda.

### 7.3 Mapper y entidades heredadas

`<Resource>Mapper` separa ambos modelos:

- `newEntity(Domain $domain): PersistenceEntity`
- `newDomain(PersistenceEntity $entity): Domain`
- `update(PersistenceEntity $entity, Domain $domain): void`
- `entityClass(): string`

Las entidades de persistencia están centralizadas en
`Shared/Infrastructure/Persistence/Entity`. No exponerlas fuera de
Infrastructure. El mapper es el único lugar normal para traducirlas a/desde el
agregado.

Al agregar una propiedad persistida, mantener sincronizados agregado, value
object, entidad, mapper de alta, mapper de rehidratación, mapper de update y
migración. No generar una migración para un cambio que no altere el esquema.

## 8. Puertos y adaptadores

Un puerto representa una capacidad técnica requerida por Application:
mailer, hash, imágenes, PDF, plantillas, storage, gateway o API externa.

Ubicación:

```text
# Transversal
Shared/Application/Port/<Capability>/<Port>.php
Shared/Infrastructure/<Capability>/<ConcreteAdapter>.php

# Específico de una capacidad
<Context>/<Capability>/Application/Port/<Type>/<Port>.php
<Context>/<Capability>/Infrastructure/<Type>/<ConcreteAdapter>.php
```

Reglas:

- El puerto es una interfaz y usa tipos de Domain/Application, nunca tipos del
  SDK concreto.
- Los DTOs del proveedor se traducen a resultados propios de Application, por
  ejemplo `TransactionResult`.
- El adaptador implementa el puerto y es el único que importa Symfony, Doctrine
  o el SDK externo.
- Declarar el alias interfaz -> implementación en `config/services.yaml`.
- Si hay varias implementaciones, usar un resolver inyectado con nombres
  explícitos, como `TransactionGatewayResolver`; no un service locator global.
- Secretos y endpoints vienen de variables de entorno/configuración, no del
  código ni del comando.

No crear un puerto para un repositorio de agregados: su contrato pertenece a
Domain. `Application/Port` es para capacidades técnicas.

## 9. Shared, Shared de contexto y referencias

`src/Shared` debe mantenerse pequeño y verdaderamente transversal:

- `Shared/Domain`: bases de value objects, contrato de evento, auditoría,
  excepciones y utilidades puras compartidas.
- `Shared/Application`: buses, inputs, seguridad, transacción y puertos
  técnicos transversales.
- `Shared/Infrastructure`: implementaciones Symfony/Doctrine y adaptadores.

### 9.1 Catálogo de clases Shared que se deben reutilizar

Antes de crear una abstracción nueva, usar este catálogo:

| Clase/grupo | Uso correcto |
|---|---|
| `ValueObjects/*ValueObject` | Base para UUID, string, int, float, bool, array, fecha, fecha-hora y hora de Domain. |
| `Audit` + `AuditCreatedAt`/`AuditUpdatedAt` | Trait y valores de timestamps para agregados que exponen auditoría. El mapper llama `assignAudit()`. |
| `DomainEvent` | Base obligatoria de hechos que viajarán por `EventBus`. |
| `Shared\Domain\UserId` | Id de usuario solo cuando el concepto es realmente transversal; preferir el id local de un contexto si existe. |
| `CurrencyList`, `ReportIntervalList` | Catálogos transversales de moneda e intervalos de reporte. |
| `MemberStatusList`, `UserStatusList`, `UserTypeList` | Enums compartidos heredados; en Account existen enums de capacidad. No crear una tercera variante y no sustituir una por otra sin revisar imports/persistencia. |
| `IntegerHelper`, `StringHelper`, `BoolBuilder` | Utilidades puras existentes. Son pequeñas y heredadas; no ampliarlas con lógica propia de negocio. Para flags de dominio nuevos preferir `BooleanValueObject`. |
| Excepciones de `Shared/Domain/Exceptions` | Fallos genéricos producidos por las bases (`ArrayKeyNotFound`, división por cero, valor nulo, token inválido). No usarlas para reglas específicas de una capacidad. |
| `PayloadMapper` | Lectura tolerante y tipada de arrays HTTP al construir Command/Query. |
| `FileUpload` | Único input de archivo admitido en Application. |
| `SearchQuery` | Orden, dirección, límite, página y cálculo de offset para listados. |
| `ArrayBuilder` | Acumulación mutable sencilla y eliminación de duplicados en Application; no reemplaza una colección de dominio. |
| `CommandBus`, `QueryBus`, `EventBus` | Puertos de mensajería; no inyectar Messenger directamente fuera de Infrastructure. |
| `CurrentActor` | Puerto de lectura nullable del actor; normalmente consumir `AuthorizationContext`, no este puerto directamente. |
| `AuthorizationContext` | Fuente validada de user/company/member y rol owner para handlers. |
| Excepciones de `Security/Exception` | Autenticación/autorización independientes de HTTP. |
| `TransactionService` | Control transaccional manual excepcional; el command bus ya es transaccional. |
| `ImageUploader` | Puerto para subir una imagen por path y devolver su ubicación. |
| `Mailer`, `EmailMessage`, `EmailAttachment` | Envío de correos sin filtrar clases de Symfony a Application. |
| `PdfGenerator`, `PdfRenderer`, `PdfStorage` | Preparación, render y almacenamiento de PDF. Usar el nivel mínimo que requiera el CU. |
| `TemplateRenderer` | Render de template a string sin depender de Twig en Application. |
| `FileBuilder` | Adaptación exclusiva de Infrastructure desde upload HTTP a `FileUpload`. |
| `QueryBuilder` | Composición parametrizada de búsquedas Doctrine ORM. |
| `NativeQueryBuilder` | Consultas DBAL de reporting/lectura cuando el modelo ORM no es adecuado. |
| `ApiExceptionSubscriber` | Único traductor central de excepción a JSON/status. |
| `Symfony*Bus`, `SymfonyCurrentActor`, adaptadores mail/image/PDF/template | Implementaciones concretas; nunca importarlas desde Domain/Application. |
| `MessageHandlerPass` | Registro automático por sufijos; no etiquetar handlers manualmente sin cambiar la estrategia global. |

`Shared/Infrastructure/Persistence/Entity` y
`Shared/Infrastructure/Persistence/Repository` contienen el modelo Doctrine
heredado. Que estén bajo `Shared` no los convierte en piezas reutilizables por
Application o Domain: solo Infrastructure puede usarlos.

No mover una clase a `Shared` porque dos archivos de una misma capacidad la
usen. Solo hacerlo si representa el mismo concepto estable en varios bounded
contexts. Antes de crear una clase shared, comprobar que no existe una base,
input, enum o helper equivalente.

`Catalog/Shared/Domain`, `Sale/Shared/Domain` y `Feedback/Shared/Domain` son
kernels internos de cada contexto. Contienen conceptos mínimos compartidos por
capacidades de ese contexto, como `CompanyId` o excepciones frontera.

`Sale/Reference` es una frontera anticorrupción: Sale posee modelos locales de
Event, EventDay, User, Zone y Seat que necesita para vender. No importar los
agregados dueños de Account/Catalog dentro de Sale cuando exista una referencia
local. Cada referencia tiene su contrato de repositorio y su adaptador de
lectura.

No compartir value objects entre contextos solo porque tienen el mismo nombre:
un `Catalog\Event\EventId` y un `Feedback\Shared\EventId` pueden representar
fronteras distintas.

## 10. Autorización, actor y aislamiento por compañía

La autorización vive en Application y se obtiene con
`AuthorizationContext`; el actor nunca se almacena dentro de Commands/Queries.

- `userId()`: exige usuario autenticado.
- `companyId()`: exige compañía seleccionada y membresía activa.
- `memberId()`: además devuelve la membresía validada.
- `validateOwner()`: exige rol `OWNER` después de validar el contexto que
  corresponda.

Política:

- Endpoints públicos de catálogo no dependen de `CurrentActor` y solo muestran
  eventos publicados e inventario disponible.
- Perfil, checkout, órdenes, pagos y tickets del comprador se acotan al
  `userId()` autenticado.
- Backoffice y reportes se acotan a `companyId()`.
- Cambios a nivel de compañía requieren owner.
- `UserType` persiste por compatibilidad, pero no es una frontera de
  autorización.

El cliente envía solo el id del recurso objetivo o de su padre inmediato. Los
ancestros y la compañía se derivan de relaciones cargadas y del actor. Nunca
confiar en un `companyId` del request para aislar tenants.

Ejemplos actuales:

```text
zone create -> day; find/update/delete -> zone
seat create -> zone; find/update/delete -> seat
order create -> day + ids de zone/seat en items
payment create -> order; confirm -> payment
ticket render -> ticket
discount create -> event; find/update/delete -> discount
```

## 11. HTTP, controllers, rutas y archivos

Los controllers viven en `Infrastructure/Controller`, son delgados y hacen
solo lo siguiente:

1. Leer path, query, JSON o upload.
2. Crear Command/Query.
3. Despachar por el bus correspondiente.
4. Construir `JsonResponse` o una respuesta de archivo.

No contienen reglas de negocio, autorización, consultas Doctrine ni bloques
`try/catch` para excepciones de negocio.

Nombres habituales:

```text
<Resource>CreateController::create
<Resource>FindController::find
<Resource>SearchController::search
<Resource>UpdateController::update
<Resource>DeleteController::delete
Public<Resource>BrowseController::browse
```

Los controllers actuales extienden `AbstractController`, reciben dependencias
en el método y se configuran en `config/routes/<resource>.yaml`. Las rutas usan
nombres kebab-case (`event-create`) y recursos singulares bajo `/api`
(`/api/event/{id}`). Conservar esta convención para compatibilidad aunque una
API nueva pudiera preferir plurales.

Respuestas actuales:

- Create: `{"id": "..."}`; algunos endpoints usan 200 y otros 201. Para uno
  nuevo preferir 201 sin cambiar endpoints existentes incidentalmente.
- Update/Delete/Upload: objeto JSON vacío y 200.
- Find/Search/Report: serialización del Response de Application.
- Errores: `{"error":{"code":...,"message":...,"status":...}}` mediante
  `ApiExceptionSubscriber`.

Los uploads son una excepción controlada a los primitivos: Infrastructure usa
`Shared\Infrastructure\Http\FileBuilder` para convertir `UploadedFile` a
`Shared\Application\Input\FileUpload`; Application nunca recibe
`UploadedFile`.

Rutas públicas actuales: `GET /events`, `GET /events/{id}`,
`GET /days/{day}/zones`, `GET /zones/{zone}/seats`. Añadir requisitos UUID a
parámetros públicos equivalentes.

## 12. Nomenclatura resumida

| Concepto | Forma |
|---|---|
| Contexto/capacidad/carpeta | PascalCase |
| Clase, interfaz, enum | PascalCase, singular |
| Método/propiedad | camelCase |
| Command | `<Verb><Resource>Command` |
| Handler de command | mismo nombre + `Handler` |
| Query | `<Verb><Resource>Query` |
| Handler de query | mismo nombre + `Handler` |
| Servicio CU | `<Resource><Creator|Updater|Deleter|Finder|Searcher|Reporter>` |
| Repositorio Domain | `<Resource>Repository` |
| Adaptador Doctrine | `<Resource>DoctrineRepository` |
| Mapper | `<Resource>Mapper` |
| Value object | `<Resource><Field>` |
| Enum | `<Resource><Field>List` |
| Excepción | `<Resource><Reason>` |
| Evento | `<FactInPast>DomainEvent` |
| Subscriber | `<Fact>EventSubscriber` |
| DTO item | `<Resource>Response` |
| DTO colección | plural `<Resources>Response` |
| Clave de error/traducción | `<resource>.<snake_case_error>` |
| Ruta Symfony | kebab-case |
| Campo JSON/query | camelCase |

No abreviar salvo vocabulario consolidado (`Id`, `Pdf`, `QrCode`). Mantener el
vocabulario que ya usa la capacidad; no renombrar `Order` a `Purchase` o
`EventDay` a `Date` solo en una capa.

## 13. Receta completa para una capacidad nueva

Antes de escribir, buscar una capacidad vecina que tenga el mismo tipo de
flujo. Luego:

1. Definir agregado/value objects/enums e invariantes en Domain.
2. Crear excepciones con clave traducible y actualizar
   `translations/messages.es.yaml`.
3. Definir contrato de repositorio y finder(s) de Domain.
4. Crear carpeta de intención en Application.
5. Crear Command o Query, su Handler y el servicio del caso de uso.
6. Añadir Response(s) para lecturas; no exponer el agregado.
7. Añadir puertos solo para I/O técnico que Application necesite.
8. Implementar mapper y repositorio/adaptador en Infrastructure.
9. Crear controller delgado y ruta YAML.
10. Si hay un hecho relevante entre capacidades, crear DomainEvent,
    publicarlo y añadir subscribers idempotentes.
11. Configurar alias en `services.yaml` cuando autowiring no pueda resolver la
    interfaz de forma inequívoca.
12. Verificar autorización y scope de compañía/usuario en todas las rutas de
    acceso, no solo en el controller.
13. Si cambia persistencia, actualizar entidad, mapper y migración de forma
    coherente.
14. Ejecutar lint de PHP, container, PHPStan y Deptrac.

## 14. Antipatrones que deben evitarse

- Domain importando Application, Infrastructure, Doctrine o Symfony HTTP.
- Handler o controller usando EntityManager directamente.
- Controller llamando un repositorio o servicio de dominio.
- Commands/Queries que llevan el usuario o compañía enviados por el cliente.
- Repositorios de tenant que buscan solo por id sin validar compañía/propiedad.
- Usar entidades Doctrine como modelos de dominio o DTOs HTTP.
- Publicar agregados completos en eventos asíncronos.
- Crear un `Shared` prematuramente o acoplar bounded contexts por comodidad.
- Duplicar interfaces de bus, mailer, uploader, transaction o query builders.
- Mensajes de excepción literales sin clave de traducción.
- Confiar en que el nombre de cualquier excepción produce automáticamente el
  status HTTP deseado, salvo `*NotFound`.
- Agregar atributos `AsMessageHandler` a unos handlers y depender del sufijo en
  otros; el proyecto centraliza el registro en `MessageHandlerPass`.
- Cambiar la forma pública de respuestas, rutas o entidades heredadas como
  refactor incidental.

## 15. Fuentes de verdad relacionadas

- Arquitectura y decisiones de límites: `docs/architecture.md`.
- Dependencias permitidas: `deptrac.yaml`.
- Nivel de análisis estático: `phpstan.neon`.
- Wiring de puertos/adaptadores: `config/services.yaml`.
- Buses, transacciones y async: `config/packages/messenger.yaml`.
- Autenticación y rutas públicas: `config/packages/security.yaml`.
- Contrato HTTP de rutas: `config/routes/*.yaml`.
- Traducciones de errores: `translations/messages.es.yaml`.

Este documento describe el estado actual, no sustituye una decisión explícita
de arquitectura. Cuando un cambio deliberado altere una convención, actualizar
en el mismo commit el código, sus verificaciones y esta guía.
