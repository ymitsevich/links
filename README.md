# Link Compressor

This software provides service to convert long links like `https://facebook.com/qwe=123&asd=123&zxc=567` to short version `http://domain.name/r/fDkX`
### Installation & Requirements

- `docker`

### Running

- `export UID=${UID}`
- `docker-compose up -d`
- `docker-compose exec php php artisan migrate`

### Testing

- `docker-compose exec php vendor/bin/phpunit`


## Using
