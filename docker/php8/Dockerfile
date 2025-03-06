FROM php:8.1.23-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip unzip \
    git \
    curl
RUN docker-php-ext-install  \
    ctype \
    fileinfo \
    bcmath \
    mysqli \
    pdo \
    pdo_mysql \
    pcntl \
    exif
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN apt-get update && \
apt-get install -y libxml2-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
docker-php-ext-install gd
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis
RUN docker-php-ext-install soap

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer global require laravel/installer

RUN export PATH="/root/.composer/vendor/bin:$PATH"