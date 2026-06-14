##@ Install

.PHONY: install
install: ## Create Swarm resources and deploy the application
	@echo "Installing microchat stack..."
	@$(MAKE) check-stack-files
	@$(MAKE) check-swarm
	@$(MAKE) install-swarm-resources
	@$(MAKE) install-node-label
	@$(MAKE) up

.PHONY: uninstall
uninstall: ## Remove stacks and Swarm configs/secrets, preserving volumes
	@echo "Uninstalling microchat stack..."
	@$(MAKE) down
	@$(MAKE) wait-stacks-removed
	@$(MAKE) uninstall-swarm-resources

.PHONY: install-swarm-resources
install-swarm-resources: install-configs install-secrets ## Create required external Swarm configs and secrets

.PHONY: install-configs
install-configs: check-swarm ## Create required external Docker configs
	@for item in $(CONFIG_SOURCES); do \
		name="$${item%%:*}"; \
		file="$${item#*:}"; \
		if docker config inspect "$$name" >/dev/null 2>&1; then \
			continue; \
		fi; \
		if [[ ! -f "$$file" ]]; then \
			echo "Missing source file for Docker config $$name: $$file"; \
			exit 1; \
		fi; \
		docker config create "$$name" "$$file" >/dev/null; \
		echo "Created Docker config: $$name"; \
	done

.PHONY: install-secrets
install-secrets: check-swarm ## Create required external Docker secrets
	@if ! docker secret inspect microchat-traefik_basic_auth >/dev/null 2>&1; then \
		if [[ -f "$(TRAEFIK_BASIC_AUTH_FILE)" ]]; then \
			docker secret create microchat-traefik_basic_auth "$(TRAEFIK_BASIC_AUTH_FILE)" >/dev/null; \
			echo "Created Docker secret: microchat-traefik_basic_auth"; \
		else \
			if [[ -z "$(TRAEFIK_BASIC_AUTH_PASSWORD)" ]]; then \
				echo "Missing Traefik basic auth secret."; \
				echo "Create $(TRAEFIK_BASIC_AUTH_FILE), or run:"; \
				echo "  make install TRAEFIK_BASIC_AUTH_USER=admin TRAEFIK_BASIC_AUTH_PASSWORD='<password>'"; \
				exit 1; \
			fi; \
			command -v htpasswd >/dev/null 2>&1 || { \
				echo "htpasswd is required to generate Traefik basic auth."; \
				echo "Install apache2-utils, or create $(TRAEFIK_BASIC_AUTH_FILE) manually."; \
				exit 1; \
			}; \
			htpasswd -nbB "$(TRAEFIK_BASIC_AUTH_USER)" "$(TRAEFIK_BASIC_AUTH_PASSWORD)" | docker secret create microchat-traefik_basic_auth - >/dev/null; \
			echo "Created Docker secret: microchat-traefik_basic_auth"; \
		fi; \
	else \
		true; \
	fi
	@if ! docker secret inspect microchat-postgres_password >/dev/null 2>&1; then \
		if [[ ! -f "$(POSTGRES_PASSWORD_FILE)" ]]; then \
			echo "Missing Postgres password file: $(POSTGRES_PASSWORD_FILE)"; \
			exit 1; \
		fi; \
		docker secret create microchat-postgres_password "$(POSTGRES_PASSWORD_FILE)" >/dev/null; \
		echo "Created Docker secret: microchat-postgres_password"; \
	else \
		true; \
	fi
	@if ! docker secret inspect microchat-identify_rabbitmq_cookie >/dev/null 2>&1; then \
		if [[ -n "$(IDENTIFY_RABBITMQ_COOKIE)" ]]; then \
			printf "%s" "$(IDENTIFY_RABBITMQ_COOKIE)" | docker secret create microchat-identify_rabbitmq_cookie - >/dev/null; \
		else \
			openssl rand -base64 32 | tr -d /=+ | cut -c -32 | docker secret create microchat-identify_rabbitmq_cookie - >/dev/null; \
		fi; \
		echo "Created Docker secret: microchat-identify_rabbitmq_cookie"; \
	else \
		true; \
	fi

.PHONY: install-node-label
install-node-label: check-swarm ## Add placement label to the current Swarm node
	@hostname="$$(docker node inspect self --format '{{.Description.Hostname}}')"; \
	docker node update --label-add role=microchat "$$hostname" >/dev/null; \
	echo "Applied Docker node label: role=microchat ($$hostname)"

.PHONY: uninstall-swarm-resources
uninstall-swarm-resources: check-swarm ## Remove external Docker configs and secrets created for this stack
	@for secret in $(REQUIRED_SECRETS); do \
		if docker secret inspect "$$secret" >/dev/null 2>&1; then \
			docker secret rm "$$secret" >/dev/null; \
			echo "Removed Docker secret: $$secret"; \
		fi; \
	done
	@for config in $(REQUIRED_CONFIGS); do \
		if docker config inspect "$$config" >/dev/null 2>&1; then \
			docker config rm "$$config" >/dev/null; \
			echo "Removed Docker config: $$config"; \
		fi; \
	done

.PHONY: wait-stacks-removed
wait-stacks-removed: check-swarm ## Wait until Swarm stacks are removed
	@timeout=120; \
	while [[ "$$timeout" -gt 0 ]]; do \
		remaining=0; \
		for stack in $(STACK_NAMES); do \
			if docker stack ls --format '{{.Name}}' | grep -Fxq "$$stack"; then \
				remaining=1; \
				break; \
			fi; \
		done; \
		if [[ "$$remaining" -eq 0 ]]; then \
			echo "All Swarm stacks are removed."; \
			exit 0; \
		fi; \
		sleep 2; \
		timeout=$$((timeout - 2)); \
	done; \
	echo "Timed out waiting for Swarm stacks to be removed."; \
	exit 1
