# Deployment Guide

---

## Local Machine Preparation
For local developing you may don't edit files created by templates.  
Add following hosts entries to your `/etc/hosts` file:
```text
#microchat.local
127.0.0.1 traefik.microchat
127.0.0.1 whoami.microchat
127.0.0.1 kafka-ui.microchat
127.0.0.1 microchat.com
```

Generate a self-signed certificate for local development and create config:
```bash
cd infrastructure/traefik/cert
mkcert -key-file key.pem -cert-file cert.pem traefik.microchat whoami.microchat kafka-ui.microchat microchat.com
docker config create microchat-traefik_cert cert.pem
docker config create microchat-traefik_cert_key key.pem
docker config create microchat-traefik_tls tls.yml
```

---

## Steps to Deploy
1. Init the Docker Swarm cluster if not already done:
    ```bash
    docker swarm init
    ```

2. Go to Traefik infrastructure service directory:
    ```bash
    cd infrastructure/traefik
    ```

3. Create the `.basic-auth` file for the Traefik dashboard authentication, add to docker secrets and remove the file:
    ```bash
    htpasswd -c infrastructure/traefik/.basic-auth USERNAME
    docker secret create microchat-traefik_basic_auth .basic-auth
    rm .basic-auth
    ```

4. Create and configure the `stack.yml` file:
    ```bash
    cp stack.template.yml stack.yml
    ```

5. Create volumes and networks if you don't change the defaults:
   ```bash
   make volumes
   make networks
   ```

6. Deploy the Traefik stack:
    ```bash
    make up
    ```

7. Go to the Kafka infrastructure service directory:
    ```bash
    cd ../kafka
    ```

8. Create and configure the `stack.yml` file:
    ```bash
    cp stack.template.yml stack.yml
    ```

9. Generate a cluster ID:
    ```bash
    docker run --rm --entrypoint /opt/bitnami/kafka/bin/kafka-storage.sh \
      bitnami/kafka:4.0.0-debian-12-r10 random-uuid
    ```

10. Replace the `REPLACE_WITH_CLUSTER_ID` in the `stack.yml` file with the generated UUID.  
    **Note:** `KAFKA_CLUSTER_ID` cannot be changed after formatting/initializing data (otherwise volume cleanup will be required).

11. Configure the Kafka UI service and create the docker config:
     ```bash
     cp ui-config.example.yml ui-config.yml # edit this file to your needs
     docker config create microchat-kafka_ui ui-config.yml
     ```

12. Add kafka label to a docker swarm node:
     ```bash
     docker node ls # to see the list of nodes
     docker node update --label-add role=microchat <HOSTNAME>
     docker node inspect <HOSTNAME> --format '{{json .Spec.Labels}}' # to check the label
     ```

13. Create volumes and networks if you don't change the defaults:
    ```bash
    make volumes
    make networks
    ```

14. Deploy the Kafka stack:
    ```bash
    make up
    ```
