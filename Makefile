STACK_NAME=microchat-stack
ENV_FILE=./.env
COMPOSE_FILES=./infrastructure/traefik/docker-compose.yml

GREEN=\033[0;32m
YELLOW=\033[0;33m
NC=\033[0m

deploy:
	bash ./deploy.sh

remove:
	@clear
	@docker stack rm $(STACK_NAME)

services:
	@clear
	@docker stack services $(STACK_NAME)

up-networks:
	@clear
	@docker network create --driver overlay microchat-public_net 2>/dev/null || echo "$(YELLOW)public-net $(GREEN)network already exists.$(NC)"

up-volumes:
	@clear
	@docker volume create --name microchat-traefik_letsencrypt

up-configs:
	@clear
	@docker config create microchat-traefik_auth deploy/traefik/.basic-auth

traefik-logs:
	@clear
	@docker service logs microchat-stack_traefik --tail 50 --follow
