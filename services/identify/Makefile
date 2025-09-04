COMPOSE_COMMAND:=docker-compose -f ./docker/compose.yml --env-file ./docker/.env

.PHONY: compose-up
compose-up:
	@clear
	@$(COMPOSE_COMMAND) up

.PHONY: compose-up-service
compose-build:
	$(COMPOSE_COMMAND) build $(c)

.PHONY: compose-exec
container-exec:
	@clear
	@docker exec -it --user root identify-app bash

.PHONY: compose-resolve-permissions
container-resolve-permissions:
	docker  exec -it --user root identify-app bash -lc 'mkdir -p var/cache var/log && chown -R app:app var'
