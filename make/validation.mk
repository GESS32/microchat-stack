##@ Validation

.PHONY: preflight
preflight: check-stack-files check-swarm check-swarm-resources check-node-labels ## Run all deployment preflight checks

.PHONY: check-swarm
check-swarm: ## Check that Docker is running on a Swarm manager
	@docker info >/dev/null 2>&1 || { \
		echo "Docker daemon is not available."; \
		exit 1; \
	}; \
	state="$$(docker info --format '{{.Swarm.LocalNodeState}}')"; \
	manager="$$(docker info --format '{{.Swarm.ControlAvailable}}')"; \
	error="$$(docker info --format '{{.Swarm.Error}}')"; \
	if [[ "$$state" == "error" ]]; then \
		echo "Docker Swarm is in error state."; \
		echo "$$error"; \
		exit 1; \
	fi; \
	if [[ "$$state" != "active" ]]; then \
		echo "Docker Swarm is not active. Current state: $$state"; \
		echo "Run 'docker swarm init' for a new local swarm, or join/promote this node in an existing swarm."; \
		exit 1; \
	fi; \
	if [[ "$$manager" != "true" ]]; then \
		echo "This node is not a Swarm manager. Run this command on a manager node or promote/join this node as manager."; \
		exit 1; \
	fi

.PHONY: check-swarm-resources
check-swarm-resources: ## Check required external Swarm configs and secrets
	@missing=0; \
	for config in $(REQUIRED_CONFIGS); do \
		if ! docker config inspect "$$config" >/dev/null 2>&1; then \
			echo "Missing Docker config: $$config"; \
			missing=1; \
		fi; \
	done; \
	for secret in $(REQUIRED_SECRETS); do \
		if ! docker secret inspect "$$secret" >/dev/null 2>&1; then \
			echo "Missing Docker secret: $$secret"; \
			missing=1; \
		fi; \
	done; \
	if [[ "$$missing" -ne 0 ]]; then \
		echo; \
		echo "Create missing configs/secrets before running make up."; \
		echo "See docs/deployment.md for the source commands."; \
	fi; \
	exit $$missing

.PHONY: check-node-labels
check-node-labels: ## Check labels required by Swarm placement constraints
	@role="$$(docker node inspect self --format '{{ index .Spec.Labels "role" }}' 2>/dev/null || true)"; \
	if [[ "$$role" != "microchat" ]]; then \
		hostname="$$(docker node inspect self --format '{{.Description.Hostname}}')"; \
		echo "Missing Docker node label: role=microchat"; \
		echo "Run: docker node update --label-add role=microchat $$hostname"; \
		exit 1; \
	fi

.PHONY: check-stack-files
check-stack-files: ## Check that all stack.yml files exist
	@missing=0; \
	for file in $(STACK_FILES); do \
		if [[ ! -f "$$file" ]]; then \
			echo "Missing $$file. Create it from stack.template.yml before deploying."; \
			missing=1; \
		fi; \
	done; \
	exit $$missing
