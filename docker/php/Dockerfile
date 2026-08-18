FROM php:8.3-fpm

## =========================================================
# SYSTEM DEPENDENCIES
# =========================================================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    libicu-dev \
    libxslt1-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libaio1t64 \
    && rm -rf /var/lib/apt/lists/*

# Oracle Instant Client expects libaio.so.1
RUN ln -s /lib/x86_64-linux-gnu/libaio.so.1t64 \
          /lib/x86_64-linux-gnu/libaio.so.1


# =========================================================
# GD CONFIGURATION
# =========================================================
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg


# =========================================================
# PHP EXTENSIONS
# =========================================================
RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    intl \
    zip \
    xsl \
    gd \
    exif


# =========================================================
# ORACLE INSTANT CLIENT
# =========================================================
WORKDIR /opt/oracle

COPY docker/oracle/instantclient-basic-linux.x64-23.26.3.0.0.zip .
COPY docker/oracle/instantclient-sdk-linux.x64-23.26.3.0.0.zip .

RUN unzip -o instantclient-basic-linux.x64-23.26.3.0.0.zip \
    && unzip -o instantclient-sdk-linux.x64-23.26.3.0.0.zip \
    && rm instantclient-basic-linux.x64-23.26.3.0.0.zip \
    && rm instantclient-sdk-linux.x64-23.26.3.0.0.zip \
    && echo "/opt/oracle/instantclient_23_26" > /etc/ld.so.conf.d/oracle-instantclient.conf \
    && ldconfig

# =========================================================
# OCI8
# =========================================================
RUN echo "instantclient,/opt/oracle/instantclient_23_26" | pecl install oci8 \
    && docker-php-ext-enable oci8


# =========================================================
# COMPOSER
# =========================================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# =========================================================
# LARAVEL WORKING DIRECTORY
# =========================================================
WORKDIR /var/www/html