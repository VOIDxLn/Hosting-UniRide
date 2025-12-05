FROM php:8.2

RUN apt-get update && apt-get install -y \
    git zip unzip curl \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath zip

WORKDIR /var/www/html

COPY . .

COPY render-build.sh /render-build.sh
RUN chmod +x /render-build.sh
RUN /render-build.sh

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]
