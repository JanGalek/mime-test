FROM composer:2 AS composer

FROM php:8.3-fpm
COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV UID=1000
ENV GID=1000
ENV USERNAME=developer

RUN addgroup --gid ${GID} --system ${USERNAME}
RUN adduser --gid ${GID} --uid ${UID} --system --disabled-password ${USERNAME}
RUN usermod -a -G www-data ${USERNAME}
RUN usermod -a -G ${USERNAME} www-data

RUN apt-get update && apt-get install -y \
        git \
        libicu-dev \
        gcc \
        curl \
        libcurl4 \
        libcurl4-openssl-dev \
        zip \
        unzip \
        git \
        zlib1g-dev \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
        postgresql \
        libpq-dev \
        icu-devtools \
        gettext \
        locales \
        build-essential \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        libgmp-dev \
        libsodium-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd pgsql gmp intl sodium pdo mbstring simplexml xmlwriter zip gettext curl bcmath exif \
    && docker-php-ext-enable gd pgsql gmp sodium mbstring curl intl gettext simplexml xmlwriter zip bcmath exif

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini