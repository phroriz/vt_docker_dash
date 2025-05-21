FROM php:8.0-apache

# Instala dependências
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    netcat-openbsd \     
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Ativa mod_rewrite
RUN a2enmod rewrite

# Define a pasta pública como raiz
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Copia scripts
COPY ./docker/php/wait-for-mysql.sh /wait-for-mysql.sh
COPY ./docker/php/entrypoint.sh /entrypoint.sh
RUN chmod +x /wait-for-mysql.sh /entrypoint.sh

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/wait-for-mysql.sh"]
