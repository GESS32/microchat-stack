SHELL := /bin/bash
.SHELLFLAGS := -o pipefail -c
.DEFAULT_GOAL := help
MAKEFLAGS += --no-print-directory

MAKE_DIR := make

include $(MAKE_DIR)/config.mk
include $(MAKE_DIR)/help.mk
include $(MAKE_DIR)/validation.mk
include $(MAKE_DIR)/install.mk
include $(MAKE_DIR)/swarm.mk
include $(MAKE_DIR)/development.mk
