FROM php:8.2-cli

WORKDIR /app

COPY . .

# Install Composer (for PHP dependencies)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies from composer.json
RUN composer install --no-dev

# Tell Render which port to use
EXPOSE 1000

# Start PHP server
CMD ["php", "-S", "0.0.0.0:1000", "-t", "."]
