FROM php:8.2-fpm

# Dependências do sistema + Node
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    nodejs \
    npm

# Extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Diretório
WORKDIR /var/www

# Copia tudo pro container
COPY . .

# Instala dependências PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Instala dependências frontend + builda
RUN npm install && npm run build

# Permissões (importante)
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]