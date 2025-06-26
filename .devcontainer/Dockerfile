FROM mcr.microsoft.com/devcontainers/base:ubuntu

# Cài PHP và các extension cần thiết
RUN apt-get update && apt-get install -y \
    php php-cli php-mbstring php-xml php-curl php-mysql php-zip php-fileinfo php-tokenizer php-common php-json \
    unzip curl git mariadb-client \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

