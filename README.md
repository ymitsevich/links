# Link Compressor

This software provides service to convert long links like `https://facebook.com/qwe=123&asd=123&zxc=567` to short version `http://domain.name/r/fDkX`

### Implemented application with:
- docker containers with Nginx, PHP7.3, MySql8, Composer. Handled by docker-compose. Different yml files for dev and prod.
- exposed several endpoints (on the localhost for dev env). Description is below.
- simple authentication with registration
- unit test for controllers, services wrapped in TravisCI

### Installation & Requirements

- docker
- `export UIDX=${UID}` (a workaround for user mapping in docker container)
- `docker-compose -f docker-compose.dev.yml up -d` (wait couple of minutes until vendors will be installed)
- `docker-compose -f docker-compose.dev.yml exec php php artisan migrate`

### Running

- `export UIDX=${UID}` (a workaround for user mapping in docker container)
- `docker-compose -f docker-compose.dev.yml up -d`

### Testing

- `docker-compose -f docker-compose.dev.yml exec php vendor/bin/phpunit`

### Using

#### Endpoints:
- POST `/api/register` Register user. Body example:
```
{
"name" : "john",
"email" : "john@gmail.com",
"password" : "werwe3122",
"password_confirmation": "werwe3122"
}
```
Response example:
```

{
  "data": {
    "name": "john",
    "email": "john@gmail.com",
    "updated_at": "2019-05-27 09:36:28",
    "created_at": "2019-05-27 09:36:28",
    "id": 1,
    "api_token": "SjFHmE15pTQ9Dt6qihahlNI2s2v9SNPoZBXfVvog0r2PbvMPXJ8xnCyNgH91"
  }
}
```
- POST `/api/auth` Auth user (login). Body example:
```
{
  "email" : "john@gmail.com",
  "password" : "werwe3122"
}
```
Response example:
```
{
  "data": {
    "id": 519,
    "name": "john",
    "email": "john@gmail.com",
    "api_token": "iw5Wa1gZZ6RVqo42atUkkchoEP90UKAtHdQKfCPSh4wJTWDCqHMBYqcgMcNn",
    "created_at": "2019-05-27 09:20:17",
    "updated_at": "2019-05-27 09:37:08"
  }
}

```
After retrieving api_token you can use it in each new request in header like:
```
Authorization: Bearer 0IIjdPVSoquzWqK18VgHyy1Ens5HEXAv6dqChJLpMD5vpkApd5QdL8RwX4eb
```
or in URI parameter
```
api_token=YGmEPivttV7n3zIlByOEjPM7NnA9emDnHM2CYGzRNxuEqt3o1rJEJaO5YTJ8

```

- POST `/api/links` Create new compressed link. Body example:
```
{"link" : "https://google.com"}
```
Response example:
```
{
    "data": {
        "id": 2145,
        "link": "https:\/\/google.com",
        "created_at": "2019-01-23T11:11:44.000000Z",
        "updated_at": "2019-01-23T11:11:44.000000Z",
        "short_link": "http:\/\/localhost\/aaJV"
    }
}

```
- GET `/api/links/2145` Retrieve a compressed link.
Response example:
```
{
    "data": {
        "id": 2145,
        "link": "https:\/\/google1.com",
        "created_at": "2019-01-23T11:11:44.000000Z",
        "updated_at": "2019-01-23T11:11:44.000000Z",
        "short_link": "http:\/\/localhost\/aaJV"
    }
}

```

- PUT `/api/links/2145` Update a compressed link. Body example:
```
{"link" : "https://google2.com"}
```
Response example:
```
{
    "data": {
        "id": 2145,
        "link": "https:\/\/google2.com",
        "created_at": "2019-01-23T11:11:44.000000Z",
        "updated_at": "2019-01-23T11:11:44.000000Z",
        "short_link": "http:\/\/localhost\/aaJV"
    }
}
```

- DELETE `/api/links/2145` Destroy a compressed link.
Response example: 
```
[] 
```

### Todo
1. Laravel Passport (oauth)
2. Security:
  token lifetime;
  email confirmation when signing up
3. Pagination for data output
4. Production deploy script or tool
