FROM php:8.2-cli

WORKDIR /app

# Install system tools and PHP extensions needed
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    default-mysql-client \
    && docker-php-ext-install mysqli \
    && rm -rf /var/lib/apt/lists/*

# Copy project files
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies from composer.json
RUN composer install --no-dev

# Expose Render port
EXPOSE 1000

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:1000", "-t", "."]
