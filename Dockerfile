# Base image
FROM php:8.3-fpm-alpine3.19 as base

# Set timezone to Warsaw
RUN apk add --no-cache tzdata && \
    cp /usr/share/zoneinfo/Europe/Warsaw /etc/localtime && \
    echo "Europe/Warsaw" > /etc/timezone && \
    apk del tzdata

# Set working directory
WORKDIR /app

# Copy source code
COPY . .

# Install necessary packages
RUN apk add --no-cache \
        supervisor \
        nginx \
        libpng-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        libzip-dev \
        curl-dev \
        libxml2-dev \
        libxslt-dev \
        openssl-dev \
        icu-dev \
        libpq-dev \
        git \
    	gd \
    	libpng \
    	libpng-dev \
    	rabbitmq-c \
    	rabbitmq-c-dev \
        acl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql pgsql gd zip curl soap xsl intl opcache \
    && docker-php-ext-configure gd --with-freetype \
    && docker-php-ext-install gd

RUN apk add --update linux-headers --no-cache $PHPIZE_DEPS
RUN pecl install amqp && docker-php-ext-enable amqp

# Add configuration files
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
#COPY ./docker/nginx-site.conf /etc/nginx/conf.d/default.conf
COPY ./docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/crontab /var/spool/cron/crontabs/root


COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set permissions for nobody user
RUN mkdir /app/.composer
RUN mkdir /.composer
RUN mkdir -p /app/vendor /app/var
RUN chown -R nobody.nobody /app /.composer && chmod -R 777 /app/var

# symfony permissions
RUN HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1) \
    && setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var \
    && setfacl -R -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var

# Set environment variables
ENV NGINX_HOST localhost
ENV NGINX_PORT 80

# Expose ports
EXPOSE 80

# Run supervisord
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]


# --------------------------------
FROM base as prod

#USER nobody
RUN APP_ENV=prod composer install --optimize-autoloader --no-interaction --no-progress --no-dev

USER root

RUN chmod -R 777 /app/var


# --------------------------------
FROM base as dev

# Install Xdebug for development
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Set Xdebug settings
COPY ./docker/php.dev.ini /usr/local/etc/php/conf.d/dev.ini

#USER nobody
RUN /usr/bin/composer install --optimize-autoloader --no-interaction --no-scripts

USER root
