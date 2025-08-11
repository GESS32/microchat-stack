# Deployment Guide

1. Go to root of the repository.

2. Create a `.env` file and configure it with your environment variables:
    ```bash
    cp .env.example .env
    ```

3. Init the Docker Swarm cluster if not already done:
    ```bash
    docker swarm init
    ```

4. Create the `.basic-auth` file for the traefik dashboard authentication:
    ```bash
    htpasswd -c infrastructure/traefik/.basic-auth USERNAME
    ```

5. Create configurations for the services:
    ```bash
    make up-configs
    ```

6. Create volumes for the services:
    ```bash
    make up-volumes
    ```

7. Create overlay networks for the services:
    ```bash
    make up-networks
    ```

8. Deploy the services:
    ```bash
    make deploy
    ```
