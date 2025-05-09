FROM php:8.1-apache

# Atualiza a lista de pacotes e instala dependências do sistema
RUN apt-get update && apt-get install -y \
    libbz2-dev \
    libcurl4-openssl-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    libmcrypt-dev \
    libc-client-dev \
    libkrb5-dev \
    libonig-dev && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Configura e instala a extensão gd com suporte a freetype e jpeg
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Configura e instala a extensão imap com suporte a kerberos e SSL
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap

# Instala outras extensões PHP
RUN docker-php-ext-install \
    bz2 \
    curl \
    fileinfo \
    gettext \
    intl \
    mbstring \
    exif \
    mysqli \
    pdo_mysql \
    soap \
    zip

# Habilita o módulo rewrite do Apache
RUN a2enmod rewrite
RUN a2enmod headers
# Copia o código do aplicativo para o diretório correto
COPY . /var/www/html/

# Limpeza para reduzir o tamanho da imagem
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
