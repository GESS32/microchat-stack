##@ Development

.PHONY: identify-dev
identify-dev: ## Start Identify local Docker Compose environment
	$(MAKE) -C $(IDENTIFY_DIR) compose-up
