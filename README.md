## Test application
- Checkout the project.
- Run `composer install`
- Copy `config/example.config.php` to `config/config.php`; set values
- Run `php app.php <command_name>`

There are two commands available:
- Manage - for virtual tree management
- Backup - for backing up data

## Run tests
`php ./vendor/bin/phpunit`