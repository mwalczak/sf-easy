FROM php:7.4-fpm

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt-get update \
	&& chmod +x /usr/local/bin/install-php-extensions && sync \
    && apt-get install -y zlib1g-dev libpq-dev git libicu-dev libxml2-dev librabbitmq-dev libssh-dev libzip-dev libcurl4-gnutls-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev libonig-dev unzip libxslt-dev libxslt1.1 \
    && install-php-extensions zip bcmath sockets xsl mysqli pdo_mysql opcache redis amqp intl gd @composer \
    xdebug \
    && echo "xdebug.mode=coverage,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo $XDEBUG_CONFIG >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.discover_client_host = true' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	&& echo 'memory_limit = 512M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini \
    && echo 'session.save_path = "/tmp"' >> /usr/local/etc/php/conf.d/docker-php-session.ini \
	&& rm /etc/localtime \
    && ln -s /usr/share/zoneinfo/Europe/Warsaw /etc/localtime

WORKDIR /var/www/symfony
