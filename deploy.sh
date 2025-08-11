#!/bin/bash
set -e

ENV_FILE=".env"
STACK_NAME="microchat-stack"

COMPOSE_FILES=(
  "./infrastructure/traefik/docker-compose.yml"
)

set -a
source "$ENV_FILE"
set +a

cat "${COMPOSE_FILES[@]}" | envsubst | docker stack deploy -c - "$STACK_NAME"
