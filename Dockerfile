# Base image
FROM php:8.3.15-bullseye

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
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
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Add GPG keys for Microsoft ODBC Driver for SQL Server (Specific for Debian 11 and ODBC 18) - https://learn.microsoft.com/en-us/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server?view=sql-server-ver16&tabs=debian18-install%2Calpine17-install%2Cdebian8-install%2Credhat7-13-install%2Crhel7-offline#driver-files
RUN curl https://packages.microsoft.com/keys/microsoft.asc | tee /etc/apt/trusted.gpg.d/microsoft.asc && \
    curl https://packages.microsoft.com/config/debian/11/prod.list | tee /etc/apt/sources.list.d/mssql-release.list && \
    apt-get update && \
    ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18 && \
    apt-get install -y unixodbc-dev && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Update PATH for mssql-tools
ENV PATH=$PATH:/opt/mssql-tools18/bin

# Install PHP extensions
RUN pecl install sqlsrv
RUN pecl install pdo_sqlsrv
RUN docker-php-ext-enable sqlsrv
RUN docker-php-ext-enable pdo_sqlsrv && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

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

RUN php artisan config:cache

# Build frontend assets
RUN npm run build

# Command to start the Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
