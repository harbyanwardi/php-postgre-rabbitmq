# jwt-php-native
Implementasi JWT , RabbitMQ in PHP Native


## RUN APP
1. cd src
2. cp .env.example .env
3. Run Command in root directory PHP --> "docker-compose up -d --build"
4. Create table in Postgre Client from script createTbl.sql

## LOGIN / AUTHENTICATION
http://localhost:8000/login.php 
Body JSON : 
{
    "email": "johndoe@example.com",
    "password": "qwerty123"
}

(for temporary only that user can be login to this apps)

Use access-token from response login to access API Send-Email

## API SEND EMAIL
http://localhost:8000/crud.php?function=sendEmail
Header
Authorization : Bearer Token - $AccessToken (from response login API)

Body
Form-Data:
email_to : string
subject_email : string
body_email : string


## LISTENER CONSUME SEND EMAIL
Run command with docker-cli --> php receive.php

