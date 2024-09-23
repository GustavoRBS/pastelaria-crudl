FROM php:8.2-fpm

# Instala as dependências necessárias
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Instala as extensões do PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo mbstring xml

# Copia o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copia os arquivos do projeto
COPY . .

# Instala as dependências do Composer
RUN composer install

# Altera a propriedade dos arquivos
RUN chown -R www-data:www-data /var/www

# Instala a extensão PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

EXPOSE 9000
CMD ["php-fpm"]
