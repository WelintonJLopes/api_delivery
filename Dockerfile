FROM php:7.4.0-apache
COPY . /var/public/

# Extensões php
# manual: https://github.com/mlocati/docker-php-extension-installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# RUN apt-get update

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions \
    zip xmlrpc soap pdo_mysql pdo_sqlsrv sqlsrv bcmath mysqli \
    && a2enmod rewrite \
    && chmod 0777 -R /var/www/   

# Timezone correto
ENV TZ=America/Sao_Paulo