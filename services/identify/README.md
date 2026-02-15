# Identify Service

The Identify Service is a microservice designed to handle user identification and authentication tasks.  
It provides endpoints for user registration, login, and profile management.


## Deployment

1. Generate RabbitMQ hash:
    ```bash
    docker run --rm rabbitmq:4.1.4-management rabbitmqctl hash_password 'YOUR_PASSWORD'
    ```
   > **Note:** Save this hash, you will need it to configure the RabbitMQ service.

2. Generate cookie secret.  
   For example, you can use the following command to generate a random 16-character password:
    ```bash
    openssl rand -base64 32 | tr -d /=+ | cut -c -16
    ```
   > **Note:** Save this password, you will need it to access the database.

3. Create several Docker secret for the RabbitMQ service:
    ```bash
    printf "YOUR_USER_NAME" | docker secret create microchat-identify_rabbitmq_user -
    printf "REPLACE_WITH_YOUR_SECRET" | docker secret create microchat-identify_rabbitmq_cookie -
    ```
