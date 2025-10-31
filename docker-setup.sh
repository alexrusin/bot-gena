#!/bin/bash

# Bring down running containers
docker-compose down

# Remove all volumes
docker-compose down -v

# Build and start containers
docker-compose up -d --build

# Wait for the database to be ready
echo "Waiting for database to be ready..."
sleep 30

# Install dependencies and set up Laravel
docker-compose exec -T app composer install --no-interaction
docker-compose exec -T app php artisan key:generate
docker-compose exec -T app php artisan migrate --force

echo "Setup completed!"