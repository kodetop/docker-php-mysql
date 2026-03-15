ARG php_version

FROM php:${php_version}

# Install system dependencies and PHP extensions in a single layer
RUN apt-get update --fix-missing && \
    apt-get install -y \
        build-essential \
        libssl-dev \
        zlib1g-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        zip \
        mysqli \
        pdo \
        pdo_mysql \
    && a2enmod rewrite headers \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
