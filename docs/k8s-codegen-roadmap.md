# Kubernetes Code Generator Roadmap

This roadmap describes how to add Kubernetes/k3d support next to the current Docker Swarm deployment. Swarm sources must remain intact until Kubernetes becomes the documented default.

## Goals

- Generate Kubernetes manifests from explicit project metadata, not by mutating Swarm `stack.yml` files in place.
- Support local development on k3d first, then keep the path open for production Kubernetes.
- Keep Swarm and Kubernetes outputs side by side with separate commands, docs, and generated files.

## Target Structure

```text
infrastructure/
  k3d/
    cluster.yml
    Makefile
  k8s/
    generator/
    manifests/
      base/
      overlays/
        local/
        prod/
```

Generated manifests should live under `infrastructure/k8s/manifests/`. Generator source and templates should live under `infrastructure/k8s/generator/`.

## Phase 1: Inventory & Metadata

Create a declarative service inventory that captures what Swarm currently encodes:

- service name, image, ports, environment variables, secrets, configs, volumes;
- dependencies such as Postgres, RabbitMQ, Kafka, and Traefik/Ingress;
- local hostnames such as `identify.microchat` and `kafka-ui.microchat`.

Suggested file:

```text
infrastructure/k8s/generator/services.yml
```

Acceptance criteria: the inventory covers Traefik/Ingress, Postgres, Kafka, RabbitMQ, and Identify without deleting or rewriting Swarm files.

## Phase 2: k3d Foundation

Add k3d cluster lifecycle commands:

```bash
make k3d-create
make k3d-delete
make k3d-status
```

Create `infrastructure/k3d/cluster.yml` with predictable ports for HTTP/HTTPS and local registry support if needed.

Acceptance criteria: a clean k3d cluster can be created and deleted without touching Swarm stacks.

## Phase 3: Manifest Generator MVP

Implement the first generator pass for simple resources:

- `Namespace`
- `ConfigMap`
- `Secret` placeholders
- `Service`
- `Deployment`
- `Ingress`

The generator should be deterministic: same input produces the same YAML ordering and content.

Acceptance criteria: generated manifests can deploy a minimal Identify service shell into k3d.

## Phase 4: Stateful Components

Add templates for stateful dependencies:

- Postgres with `StatefulSet`, `Service`, and `PersistentVolumeClaim`;
- RabbitMQ for Identify;
- Kafka as a local single-node profile first, before multi-node production topology.

Acceptance criteria: Identify can connect to Postgres and RabbitMQ inside k3d.

## Phase 5: Environment Overlays

Introduce overlays:

- `local` for k3d hostnames, small resources, and local credentials;
- `prod` for production-ready names, storage classes, resource limits, and secret references.

Use Kustomize-compatible layout unless a stronger reason appears to choose Helm.

Acceptance criteria: `make k8s-render ENV=local` and `make k8s-render ENV=prod` generate separate outputs.

## Phase 6: Root Make Integration

Add root-level commands without changing existing Swarm commands:

```bash
make swarm-up
make swarm-down
make k3d-up
make k3d-down
make k8s-render ENV=local
make k8s-apply ENV=local
make k8s-delete ENV=local
```

Keep `make up` mapped to the current Swarm flow until Kubernetes is promoted as the default.

Acceptance criteria: Swarm and k3d workflows are both available and clearly separated.

## Phase 7: Validation & CI

Add validation for generated YAML:

- generator input schema validation;
- Kubernetes manifest validation with `kubectl apply --dry-run=server` when a cluster is available;
- static checks with `kubectl kustomize` or equivalent render checks.

Acceptance criteria: CI can verify generator output without requiring a production cluster.

## Phase 8: Migration Decision

Promote Kubernetes only after it supports the same minimum operational path as Swarm:

- ingress;
- Identify;
- Postgres;
- RabbitMQ;
- Kafka;
- documented setup and teardown;
- documented secrets/config handling.

When this is complete, update README and deployment docs to mark Kubernetes as default while keeping Swarm as legacy or fallback.
