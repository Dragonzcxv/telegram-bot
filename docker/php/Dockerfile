FROM php:8.2-fpm

ARG uname
ARG uid
ARG gid

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    zip \
    unzip \
    cron

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd \
    --enable-gd \
    --with-jpeg

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ADD ./cfg/php/custom.ini /usr/local/etc/php/conf.d
ADD ./cfg/phpfpm/custom.conf /usr/local/etc/php-fpm.d

RUN groupadd --gid $gid $uname
RUN useradd -G www-data,root -s /bin/bash --uid $uid --gid $gid $uname

RUN mkdir -p /home/$uname/.composer && \
    chown -R $uname:$uname /home/$uname

WORKDIR /var/www

USER $uname

EXPOSE 9000

CMD ["php-fpm"]
