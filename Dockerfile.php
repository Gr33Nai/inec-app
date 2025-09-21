FROM php:8.1-fpm-alpine

RUN apk update && apk add --no-cache \
    libzip-dev \
    zip \
    curl

RUN docker-php-ext-install pdo pdo_mysql mysqli mbstring

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN addgroup -g 1000 -S www-data && \
    adduser -u 1000 -S www-data -G www-data

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
