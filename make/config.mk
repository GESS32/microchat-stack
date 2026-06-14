# Shared variables for root Make targets.
# Use ?= so local invocations can override paths without editing this file.

TRAEFIK_DIR ?= infrastructure/traefik
KAFKA_DIR ?= infrastructure/kafka
POSTGRES_DIR ?= services/postgres
IDENTIFY_DIR ?= services/identify

TRAEFIK_CERT_DIR ?= $(TRAEFIK_DIR)/cert
TRAEFIK_BASIC_AUTH_FILE ?= $(TRAEFIK_DIR)/.basic-auth
TRAEFIK_BASIC_AUTH_USER ?= admin
TRAEFIK_BASIC_AUTH_PASSWORD ?=
KAFKA_UI_CONFIG_FILE ?= $(KAFKA_DIR)/ui-config.yml
POSTGRES_PASSWORD_FILE ?= $(POSTGRES_DIR)/postgres_password
IDENTIFY_RABBITMQ_COOKIE ?=

STACK_FILES := \
	$(TRAEFIK_DIR)/stack.yml \
	$(KAFKA_DIR)/stack.yml \
	$(POSTGRES_DIR)/stack.yml \
	$(IDENTIFY_DIR)/stack.yml

STACK_NAMES := \
	microchat-platform_traefik \
	microchat_service-postgres \
	microchat_platform-kafka \
	microchat_service-identify

REQUIRED_CONFIGS := \
	microchat-traefik_tls \
	microchat-traefik_cert \
	microchat-traefik_cert_key \
	microchat-kafka_ui

REQUIRED_SECRETS := \
	microchat-traefik_basic_auth \
	microchat-postgres_password \
	microchat-identify_rabbitmq_cookie

CONFIG_SOURCES := \
	microchat-traefik_tls:$(TRAEFIK_CERT_DIR)/tls.yml \
	microchat-traefik_cert:$(TRAEFIK_CERT_DIR)/cert.pem \
	microchat-traefik_cert_key:$(TRAEFIK_CERT_DIR)/key.pem \
	microchat-kafka_ui:$(KAFKA_UI_CONFIG_FILE)
