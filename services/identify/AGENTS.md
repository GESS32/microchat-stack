# Repository Guidelines

## Scope

This guide applies to `services/identify/`, the Identify service. Follow the root `AGENTS.md` as well.

## Project Structure

The service combines Docker/RabbitMQ stack files with a Symfony application:

- `src/src/Domain/` contains entities, value objects, domain services, policies, events, and repository interfaces.
- `src/src/Application/` contains use-case commands and handlers.
- `src/src/Infrastructure/` contains Doctrine, Messenger, security, persistence, and adapter implementations.
- `src/src/Interface/` contains HTTP controllers, request DTOs, and CLI commands.
- `src/config/`, `src/migrations/`, `src/public/`, and `src/templates/` follow standard Symfony locations.
- `docker/`, `rabbitmq/`, and `stack*.yml` define local and Swarm runtime configuration.

## Development Commands

Run these from `services/identify/` unless noted:

- `make compose-up` starts the local Docker Compose environment.
- `make compose-build` builds Compose images; pass `c=<service>` if needed.
- `make app-exec` opens a root shell in the `identify-app` container.
- `make rabbitmq-exec` opens a shell in the RabbitMQ container.
- `make up|down|ps` manages the Swarm stack.
- `make logs S=<service>` streams logs for a stack service.
- `cd src && composer install` installs PHP dependencies.
- `cd src && php bin/console <command>` runs Symfony commands.

## Coding Style & Naming

PHP uses PSR-4 autoloading with `App\` mapped to `src/src/` and `App\Tests\` mapped to `src/tests/`. Keep the current layered architecture: domain code must not depend on infrastructure or interface code. Use descriptive class names and existing suffixes: `Interface`, `Command`, `Handler`, `Exception`, `Factory`, and `Policy`.

## Testing

Tests belong in `src/tests/` under `App\Tests\`. No PHPUnit configuration is committed yet, so add test tooling before depending on automated test commands. Prefer unit tests for `Domain/` value objects and services, and integration tests for Doctrine, Messenger, controllers, and command handlers. Name tests after the subject, for example `EmailTest` or `RegisterUserHandlerTest`.

## Configuration & Secrets

Do not commit real `.env` values, RabbitMQ definitions, hashes, cookies, JWT keys, or generated secrets. Keep `*.example` files generic and update `README.md` when new secrets or setup steps are required.
