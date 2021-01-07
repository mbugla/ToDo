### How to install the project
```
bash setup_env.sh dev - to setup .env.local docker/.env
make dc_up - docker-compose up
make setup_dev - composer install, migrations and so on
http://127.0.0.1:888 - app base url
```
###Some words about docker
In project is used workplace container for code manipulations, CI or building. It was created for preventing of pollution of working containers (php-fpm) of unused in request, building tools like nodejs, composer, dev libs and so on. Also was created a local user based on host machine user PUID PGID to resolve conflicts with file permissions.

```make dev - jump into workplace container```

### How to use API
First create a user
```
POST /api/users
{
    "username": string,
    "password": string
}
```
Obtain auth token by providing created user credentials
```
POST api/auth-token
{
    "username": "john",
    "password": "password"
}
```
Now you can use task API endpoints
To make API calls you need to add Authorization header: Bearer {token}

```
POST api/tasks
{
    "name": string
}

PUT api/tasks/{taskId}
{
    "status": done|undone
    "name": string
}

GET api/tasks

GET api/tasks/{taskId}

```
