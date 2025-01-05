# Base image
FROM php:8.3.15-bullseye

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    wget \
    nano \
    unzip \
    zip \
    build-essential \
    libpq-dev \
    libzip-dev \
    gnupg2 \
    software-properties-common \
    apt-transport-https \
    docker-php-ext-install pdo pdo_pgsql mysqli zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Microsoft ODBC Driver for SQL Server
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/microsoft-prod.list > /etc/apt/sources.list.d/microsoft-prod.list \
    && apt-get update \
    && apt-get install -y msodbcsql18 \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install the sqlsrv and pdo_sqlsrv extensions
RUN apt-get update && apt-get install -y \
    php-pear \
    php-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install sqlsrv and pdo_sqlsrv using pecl
RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Install Node.js and npm from NodeSource
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Verify installation
RUN node -v && npm -v

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy project files
COPY . .
COPY .env.example .env

# Install PHP dependencies (Laravel)
RUN composer install --no-interaction --optimize-autoloader

# Install Node.js dependencies
RUN npm install

# Set permissions for storage and cache
RUN chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache

# Generate application key
RUN php artisan key:generate

# Set up Passport keys (uncomment if you use Passport)
# RUN php artisan passport:keys --force

# Git init repo
ARG ACCESS_TOKEN
ARG TOKEN_NAME

RUN git init
RUN git config --global --add safe.directory /app
RUN git remote remove origin
RUN git remote add origin https://$TOKEN_NAME:$ACCESS_TOKEN@scm.htpdevops.com/finsolutions/ifics-niise/ificsbe-niise.git
RUN git fetch origin
RUN git checkout master
RUN git config pull.ff only


# Expose port
EXPOSE 8000

# Build frontend assets
RUN npm run build

# Command to start the Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
