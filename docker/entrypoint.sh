#!/bin/sh
set -e

echo "Starting container entrypoint..."
echo "PORT is set to: $PORT"

echo "Substituting PORT in nginx config..."
envsubst '${PORT}' < /etc/nginx/sites-available/default > /etc/nginx/sites-available/default.tmp && mv /etc/nginx/sites-available/default.tmp /etc/nginx/sites-available/default
echo "Nginx config updated."

echo "Starting supervisord..."
exec "$@"
