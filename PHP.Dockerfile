FROM php:5.4.45-fpm

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install mysqli

RUN pecl channel-update pecl.php.net
RUN pecl install xdebug-2.4.1 && docker-php-ext-enable xdebug