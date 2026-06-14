##@ General

.PHONY: help
help: ## Show available commands
	@awk 'BEGIN {FS = ":.*##"; printf "Microchat stack commands\n"} /^##@/ {printf "\n%s\n", substr($$0, 5)} /^[a-zA-Z0-9_-]+:.*##/ {printf "  %-24s %s\n", $$1, $$2}' $(MAKEFILE_LIST)
