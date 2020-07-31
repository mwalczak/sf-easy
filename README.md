# SfEasy
Symfony 5.1 User/Security Bootstrap with EasyAdmin 3

[More about EasyAdmin 3](https://symfony.com/doc/master/bundles/EasyAdminBundle/index.html)

## Features
```
security
login
create user as admin
reset password
mailer (register, reset)
```

## Installation
Dependencies:
php7.4, composer, symfony-cli

Start docker with (comment out nginx, php, frontend):
```
cp docker-compose-dist.yml docker-compose.yml
docker-compose up -d
composer install
```

Adjust env settings - uncomment below (default db name is `default`):
```
cp .env.dev .env.dev.local
#DATABASE_URL=mysql://root:P@ssw0rd@127.0.0.1:3307/default
``` 
Prepare dev database with:
```
echo 'DATABASE_URL=mysql://root:P@ssw0rd@127.0.0.1:3307/default' >> .env.local
bash reset_dev_db.sh
```

## Usage

Start local server with:
```
symfony serve
```
Open your browser:

[Localhost](http://localhost:8000)

Login with one of created users:
```
root@root.dev / root (admin user)
user@user.dev / user (regular user)
```

Send email tests with:
```
php bin/console app:send-email --template=register --email=user@example.com
```

## Config
Configure Mailer for register and password reset:
```
MAILER_DSN
MAILER_FROM
MAILER_FROM
```
[How to configure mailtrap for mailer](https://blog.mailtrap.io/send-emails-in-symfony/)

[How to configure gmail for mailer](https://symfony.com/doc/current/email.html#using-gmail-to-send-emails)

Modify and generate emails with mjml:
```
npm install
npm run build:email
```
Put your custom css in:
```
public/css/admin.css
```
Check examples of templates overriding in:
```
/templates/bundles/
/templates/reset_password/
```

## Code style
Run PHP CS Fixer with:
```
vendor/bin/php-cs-fixer fix -v
```
You will find rules in: `.php_cs.dist`

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to follow code style and update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)






