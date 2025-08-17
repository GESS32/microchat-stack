STACK ?= microchat-platform_traefik
STACK_FILE ?= ./stack.yml
NETWORK ?= microchat-public_net
VOLUME ?= microchat-traefik_letsencrypt

.PHONY: help
help:
	@echo "Makefile for $(STACK) stack management"
	@echo " make networks            - create external overlay network ($(NETWORK))"
	@echo " make volumes             - create external volume for ACME ($(VOLUME))"
	@echo " make up                  - docker stack deploy"
	@echo " make down                - docker stack rm"
	@echo " make ps                  - list services in the stack"
	@echo " make logs S=<svc>        - stream logs for service (e.g., S=traefik or S=whoami)"
	@echo " make validate            - docker stack config (lint the stack file)"
	@echo " make print               - print current variables"

.PHONY: print
print:
	@echo "STACK=$(STACK)"
	@echo "STACK_FILE=$(STACK_FILE)"
	@echo "NETWORK=$(NETWORK)"
	@echo "VOLUME=$(VOLUME)"

.PHONY: networks
networks:
	- docker network create --driver overlay --attachable $(NETWORK)

.PHONY: volumes
volumes:
	- docker volume create $(VOLUME)

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
	$(error Please specify service name via S= (e.g., S=traefik))
endif
	@docker service logs -f $$(docker service ls --format '{{.ID}} {{.Name}}' | awk '/$(STACK)_$(S)$$/ {print $$1}')

.PHONY: validate
validate:
	@docker stack config -c $(STACK_FILE) >/dev/null && echo "OK: $(STACK_FILE) is valid."
