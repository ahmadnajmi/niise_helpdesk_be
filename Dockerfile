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
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


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

# Expose port
EXPOSE 8000

# Build frontend assets
RUN npm run build

# Command to start the Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
