# Repository Guidelines

## Scope

This guide applies to `infrastructure/`, including Kafka, Traefik, and future shared platform components. Follow the root `AGENTS.md` as well.

## Project Structure

- `kafka/` contains Kafka Swarm stack files, UI config examples, volume/network setup, and node-label guidance.
- `traefik/` contains Traefik Swarm stack files, certificate configuration, and validation targets.
- `monitoring/` is reserved for observability components.

Prefer editing `stack.template.yml` and example config files when changing reusable configuration. Treat generated or environment-specific `stack.yml` and non-example config files as local deployment artifacts unless the change is intentionally operational.

## Commands

Run commands from the component directory:

- `cd infrastructure/kafka && make help` lists Kafka targets.
- `cd infrastructure/kafka && make networks` creates external overlay networks.
- `cd infrastructure/kafka && make volumes` creates Kafka controller and broker volumes.
- `cd infrastructure/kafka && make labels` prints node-label instructions.
- `cd infrastructure/kafka && make up|down|ps` manages the Kafka stack.
- `cd infrastructure/kafka && make logs S=<service>` streams Kafka service logs.
- `cd infrastructure/traefik && make validate` validates the Traefik stack file.
- `cd infrastructure/traefik && make networks volumes up` prepares and deploys Traefik.

## Style & Conventions

Use 4-space indentation for repository files unless a format requires otherwise. Keep Make targets simple, explicit, and consistent with existing names: `help`, `networks`, `volumes`, `up`, `down`, `ps`, `logs`, and `validate`.

For Docker Swarm YAML, keep service names, networks, volumes, labels, and secrets descriptive and prefixed with `microchat` where the existing stack does so.

## Validation

Validate stack changes before opening a PR. Traefik has `make validate`; for other stacks, use Docker's stack config validation when available and document any manual checks performed. For Kafka changes, verify required external networks, volumes, and node labels are documented.

## Security & Operations

Do not commit production certificates, ACME state, passwords, tokens, or private UI configuration. Keep `*.example.yml` files safe to share. Document new external networks, volumes, labels, and secrets in the relevant README or deployment guide.
