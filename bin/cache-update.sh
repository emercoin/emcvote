#!/bin/bash

cd /var/www/emcvote

php app/console cache:clear -e prod
php app/console assets:install -e prod
