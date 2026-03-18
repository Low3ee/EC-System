#!/bin/sh
set -e

# Replace ${PORT} with the value of the PORT environment variable in the nginx config
envsubst '${PORT}' < /etc/nginx/sites-available/default > /etc/nginx/sites-available/default.tmp && mv /etc/nginx/sites-available/default.tmp /etc/nginx/sites-available/default

exec "$@"
