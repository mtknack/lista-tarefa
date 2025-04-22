FROM php:8.2-cli

# Instala dependências necessárias
RUN apt-get update && apt-get install -y \
    git curl libzip-dev unzip sqlite3 \
    && docker-php-ext-install zip pdo pdo_sqlite

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /app

# Copia arquivos
COPY . .

# Instala dependências PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Gera key do Laravel
RUN php artisan key:generate

# Expõe porta
EXPOSE 9000

# Inicia o servidor
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
