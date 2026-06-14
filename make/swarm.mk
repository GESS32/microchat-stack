##@ Swarm

.PHONY: prepare
prepare: check-swarm ## Create shared Docker networks and volumes
	@$(MAKE) -C $(TRAEFIK_DIR) networks
	@$(MAKE) -C $(TRAEFIK_DIR) volumes
	@$(MAKE) -C $(KAFKA_DIR) networks
	@$(MAKE) -C $(KAFKA_DIR) volumes

.PHONY: up
up: preflight prepare ## Prepare and deploy all Swarm stacks
	@$(MAKE) -C $(TRAEFIK_DIR) up
	@$(MAKE) -C $(POSTGRES_DIR) up
	@$(MAKE) -C $(KAFKA_DIR) up
	@$(MAKE) -C $(IDENTIFY_DIR) up

.PHONY: down
down: ## Remove all Swarm stacks in dependency order
	- @$(MAKE) -C $(IDENTIFY_DIR) down
	- @$(MAKE) -C $(KAFKA_DIR) down
	- @$(MAKE) -C $(POSTGRES_DIR) down
	- @$(MAKE) -C $(TRAEFIK_DIR) down

.PHONY: ps
ps: ## List services for all deployed stacks
	- $(MAKE) -C $(TRAEFIK_DIR) ps
	- $(MAKE) -C $(POSTGRES_DIR) ps
	- $(MAKE) -C $(KAFKA_DIR) ps
	- $(MAKE) -C $(IDENTIFY_DIR) ps

.PHONY: validate
validate: ## Validate stack files where supported
	@$(MAKE) -C $(TRAEFIK_DIR) validate
