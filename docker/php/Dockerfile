FROM php:7.4-fpm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install apcu

RUN apt-get update && \
apt-get install -y libzip-dev \ 
&& apt-get install -y libicu-dev

RUN docker-php-ext-install pdo_mysql \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl 

RUN docker-php-ext-install zip
RUN docker-php-ext-enable apcu

WORKDIR /usr/src/app

COPY --chown=1000:1000 apps/test /usr/src/app

RUN PATH=$PATH:/usr/src/app/vendor/bin:bin

RUN composer install --no-scripts --prefer-dist \
  && rm -rf "$(composer config cache-dir)" "$(composer config data-dir)" 
#  && php bin/console doctrine:schema:create \
#  && php -d memory_limit=-1 bin/console doctrine:fixtures:load \
#  && php bin/console assets:install –symlink public/
