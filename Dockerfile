FROM php:8.2-cli
RUN apt-get update && apt-get install -y git 
RUN curl -sSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o - | sh -s \
      bcmath \
      ctype \
      curl \
      dom \
      fileinfo \
      json \
      mbstring \
      openssl \
      pcre \
      pdo \
      tokenizer \
      xml 
# USER 1000:1000
WORKDIR /var/www
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY ./ /var/www
RUN composer install --no-cache
EXPOSE 8080
CMD [ "php", "artisan", "serve" ]