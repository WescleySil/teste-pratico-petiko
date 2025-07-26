FROM richarvey/nginx-php-fpm:latest

COPY . /var/www/html

COPY nginx.conf /etc/nginx/sites-enabled/default.conf

ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

CMD ["/start.sh"]