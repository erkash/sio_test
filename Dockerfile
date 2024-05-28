# Stage 1: Build stage
FROM php:8.3-cli-alpine as sio_test

# Install dependencies
RUN apk add --no-cache \
    git \
    zip \
    bash \
    postgresql-client \
    postgresql-dev \
    && docker-php-ext-install pdo_pgsql

# Copy composer from the official composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Setup php app user
ARG USER_ID=1000
RUN adduser -u ${USER_ID} -D -H app

# Set user to app
USER app

# Copy application files
COPY --chown=app . /app
WORKDIR /app

# Expose the port
EXPOSE 8337

# Command to run the application
CMD ["php", "-S", "0.0.0.0:8337", "-t", "public"]
