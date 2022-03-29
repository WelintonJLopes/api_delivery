FROM php:7.4.0-apache
COPY . /var/www/

# Extensões php
# manual: https://github.com/mlocati/docker-php-extension-installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# RUN apt-get update

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions \
    zip xmlrpc soap pdo_mysql pdo_sqlsrv sqlsrv bcmath mysqli \
    && a2enmod rewrite \
    && chmod 0777 -R /var/www/   

CMD ["/usr/sbin/apachectl", "-D", "FOREGROUND"]

# Timezone correto
ENV TZ=America/Sao_Paulo

### pedro@177.155.91.194 cyberldp2020

# docker build -t api_delivery:1 -f Dockerfile_dev .
# docker run --name api_delivery -p 80:80 -v C:\Users\User\Documents\code\api_delivery\src\storage:/var/www/storage -v C:\Users\User\Documents\code\api_delivery\src\storage\app\public:/var/www/html/storage -v C:\Users\User\Documents\code\api_delivery\src:/var/www api_delivery:1
# docker run --name api_delivery -p 80:80 -v C:\Users\welin\Desktop\Code\api_delivery:/var/www api_delivery:1