STACK=microchat_service-identify
STACK_FILE=./stack.yml
RABBITMQ_CONTAINER_NAME=$(STACK)-identify_rabbitmq
APP_CONTAINER_NAME=identify-app
COMPOSE_COMMAND:=docker-compose -f ./docker/compose.yml --env-file ./docker/.env

.PHONY: up
up:
	- docker stack deploy $(STACK) --with-registry-auth --compose-file=$(STACK_FILE)

.PHONY: down
down:
	- docker stack rm $(STACK)

.PHONY: ps
ps:
	- docker stack services $(STACK)

.PHONY: logs
logs:
ifndef S
	$(error Please specify service name via S= (e.g., S=rabbitmq))
endif
	@docker service logs -f $$(docker service ls --format '{{.ID}} {{.Name}}' | awk '/$(STACK)_$(S)$$/ {print $$1}')

.PHONY: compose-up
compose-up:
	- @clear
	- @$(COMPOSE_COMMAND) up

.PHONY: compose-up-service
compose-build:
	- $(COMPOSE_COMMAND) build $(c)

.PHONY: app-exec
app-exec:
	- @clear
	- @docker exec -it --user root identify-app bash

.PHONY: rabbitmq-exec
rabbitmq-exec:
	- @clear
	- @docker exec -it $(shell docker ps --filter "name=$(RABBITMQ_CONTAINER_NAME)" --format "{{.ID}}") bash
