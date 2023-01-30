FROM php:8.2-cli

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

WORKDIR /the/workdir/path
USER 1000:1000