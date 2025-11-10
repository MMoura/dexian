FROM php:8.3-fpm-bookworm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    gnupg2 \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    default-mysql-client \
    libzip-dev \
    libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip sockets \
    || true

# Copiar Composer da imagem oficial
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Garantir permissões corretas
RUN chown -R www-data:www-data /var/www/html

# Expor a porta padrão do PHP-FPM
EXPOSE 9000

# Comando padrão
CMD ["php-fpm"]