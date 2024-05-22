FROM php:8.2-fpm

# Instalar dependências necessárias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configurar PHP-CS
RUN composer global require "squizlabs/php_codesniffer=*"

# Configurar PHPUnit
RUN composer global require "phpunit/phpunit"

# Configurar variáveis de ambiente
ENV PATH="/root/.composer/vendor/bin:${PATH}"

# Definir o diretório de trabalho
WORKDIR /var/www

# Copiar o código da aplicação
COPY . /var/www

# Instalar as dependências do Laravel
RUN composer install

# Expor a porta do PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]

