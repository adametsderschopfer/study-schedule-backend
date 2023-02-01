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
COPY composer.json composer.json
RUN composer install --no-scripts --no-autoloader
COPY ./ /var/www
RUN composer dump-autoload --optimize && \
      composer run-script post-root-package-install && \
      composer run-script post-create-project-cmd
EXPOSE 8000
CMD [ "php", "artisan", "serve" , "--host=0.0.0.0", "--port=8000"]