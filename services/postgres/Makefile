STACK=microchat_service-postgres
STACK_FILE=./stack.yml

.PHONY: help
help:
	@echo "make up           - docker stack deploy $(STACK)"
	@echo "make down         - docker stack rm $(STACK)"

.PHONY: up
up:
	- docker stack deploy $(STACK) --with-registry-auth --compose-file=$(STACK_FILE)

.PHONY: down
down:
	- docker stack rm $(STACK)
