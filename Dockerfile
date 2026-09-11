FROM node:24-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY postcss.config.js tailwind.config.js vite.config.js ./
RUN npm run build

FROM richarvey/nginx-php-fpm:3.1.6

COPY . .
COPY --from=frontend /app/public/build /var/www/html/public/build
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress && \
    chmod +x /var/www/html/scripts/00-laravel-deploy.sh

ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV APP_ENV=production
ENV APP_DEBUG=true
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_KEY=base64:cca36xCX47WBAIKFXl8aUAAbn0iSzptH7dPlu/lrY+g=

CMD ["/start.sh"]
