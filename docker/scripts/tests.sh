#!/bin/sh

docker run -it -v "$PWD":/application -v /application/vendor food-delivery-restaurant-php php vendor/bin/phpunit