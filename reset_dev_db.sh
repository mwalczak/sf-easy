#!/bin/sh

php bin/console d:d:d --force -e dev
php bin/console d:d:c -e dev
php bin/console d:m:m -n -e dev
php bin/console d:f:l -n -e dev
