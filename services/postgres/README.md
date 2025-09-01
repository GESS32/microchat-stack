# Postgres

## Deployment

1. Generate a password for the `POSTGRES_PASSWORD` environment variable.  
    For example, you can use the following command to generate a random 16-character password:
    ```bash
    openssl rand -base64 32 | tr -d /=+ | cut -c -16
    ```
   > **Note:** Save this password, you will need it to access the database.

2. Create a Docker secret for the Postgres password:
    ```bash
    printf "REPLACE_WITH_YOUR_SECRET" | docker secret create microchat-postgres_password -
    ```

3. Add database label to a docker swarm node:
     ```bash
     docker node ls # to see the list of nodes
     docker node update --label-add role=microchat <HOSTNAME>
     docker node inspect <HOSTNAME> --format '{{json .Spec.Labels}}' # to check the label
     ```

4. Create and configure the `stack.yml` file:
    ```bash
     cp stack.template.yml stack.yml
     ```

5. Deploy the Postgres stack:
    ```bash
    make up
    ```
