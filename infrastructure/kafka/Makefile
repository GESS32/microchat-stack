STACK=microchat_platform-kafka
STACK_FILE=./stack.yml

NET_BACKOFFICE=microchat-backoffice
NET_PUBLIC=microchat-public_net

VOL_CTRL_0=microchat-kafka_ctrl_0
VOL_CTRL_1=microchat-kafka_ctrl_1
VOL_CTRL_2=microchat-kafka_ctrl_2
VOL_BROKER_0=microchat-kafka_persistence_0
VOL_BROKER_1=microchat-kafka_persistence_1
VOL_BROKER_2=microchat-kafka_persistence_2

.PHONY: help
help:
	@echo "make networks     - create external overlay networks (if missing)"
	@echo "make volumes      - create external volumes for controllers/brokers"
	@echo "make labels       - label current node for kafka (role=kafka)"
	@echo "make up           - docker stack deploy $(STACK)"
	@echo "make down         - docker stack rm $(STACK)"
	@echo "make ps           - docker stack services $(STACK)"
	@echo "make logs S=<svc> - docker service logs -f <svc_id>"

.PHONY: networks
networks:
	- docker network create --driver overlay --attachable $(NET_BACKOFFICE) 2>/dev/null || echo "Network $(NET_BACKOFFICE) already exists."
	- docker network create --driver overlay --attachable $(NET_PUBLIC) 2>/dev/null || echo "Network $(NET_PUBLIC) already exists."

.PHONY: volumes
volumes:
	- docker volume create $(VOL_CTRL_0)
	- docker volume create $(VOL_CTRL_1)
	- docker volume create $(VOL_CTRL_2)
	- docker volume create $(VOL_BROKER_0)
	- docker volume create $(VOL_BROKER_1)
	- docker volume create $(VOL_BROKER_2)

.PHONY: rm-volumes
rm-volumes:
	- docker volume rm $(VOL_CTRL_0)
	- docker volume rm $(VOL_CTRL_1)
	- docker volume rm $(VOL_CTRL_2)
	- docker volume rm $(VOL_BROKER_0)
	- docker volume rm $(VOL_BROKER_1)
	- docker volume rm $(VOL_BROKER_2)

.PHONY: labels
labels:
	@echo "Label the node that should run Kafka (choose a node name):"
	@echo "  docker node ls"
	@echo "  docker node update --label-add role=kafka <node-name>"

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
	$(error "Please specify service name via S= (e.g., S=kafka-broker-0)")
endif
	@docker service logs -f $$(docker service ls --format '{{.ID}} {{.Name}}' | awk '/$(STACK)_$(S)$$/ {print $$1}')
