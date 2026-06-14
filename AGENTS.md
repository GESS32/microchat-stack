# Repository Guidelines

## Scope

This root guide applies to the whole `microchat-stack` repository. More specific rules live in nested guides:

- `services/identify/AGENTS.md` for the Identify Symfony service.
- `infrastructure/AGENTS.md` for Docker Swarm infrastructure.

When working in a subdirectory, follow both this file and the nearest nested `AGENTS.md`.

## Project Structure

This is an early-stage microservices chat stack. Application services live under `services/`, shared platform components under `infrastructure/`, and documentation under `docs/`.

- `services/identify/` is the current PHP/Symfony service.
- `services/postgres/` contains the Postgres Swarm stack and backup configuration.
- `infrastructure/kafka/` and `infrastructure/traefik/` contain platform stack definitions and Makefiles.
- `docs/deployment.md` holds deployment notes.

## General Commands

Run Make targets from the component directory that owns the stack:

- `make up` deploys a Docker Swarm stack where supported.
- `make down` removes that stack.
- `make ps` lists stack services where available.
- `make logs S=<service>` streams logs for a named service where available.

Use `README.md` files and local Makefiles as the source of truth before adding new commands.

## Style

Follow `.editorconfig`: UTF-8, LF line endings, 4-space indentation, final newline, and trimmed trailing whitespace. Keep changes scoped to the affected service or infrastructure component. Prefer existing directory patterns over introducing new layout conventions.

## Commits & Pull Requests

Git history uses Conventional Commit-style messages such as `feat(service/identify): init base components` and `feat(kafka): update node label`. Use `type(scope): short imperative summary`, with scopes like `service/identify`, `kafka`, `postgres`, or `traefik`.

Pull requests should include a concise description, affected area, deployment or configuration notes, linked issues when available, and validation evidence such as Makefile output, stack checks, or Symfony console/test output.

## Security

Do not commit real secrets, generated credentials, JWT keys, local `.env` values, or production stack overrides. Keep example files generic and document required secrets in README or deployment docs.
