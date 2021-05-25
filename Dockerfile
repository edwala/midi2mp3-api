# Source image
FROM 1and1internet/ubuntu-16-apache-php-7.2
#FROM php:7.2-apache

# Fluidsynth (midi -> wav) et lame (wav -> mp3) installation | ffmpeg pro mix výsledných mp3
RUN apt-get update
RUN  apt-get install -y fluidsynth
RUN  apt-get install -y  lame
RUN  apt-get install -y   ffmpeg
RUN  apt-get install -y curl
RUN  apt-get install -y  php-curl
RUN  apt-get install -y  mp3info



# Maintainer info
MAINTAINER ggracieux@gmail.com

# Apache configuration
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN chmod 777 /etc/apache2/sites-available/000-default.conf

COPY limits.conf /etc/security/limits.conf
RUN chmod 777 /etc/security/limits.conf

# Adds API source files
COPY lib /var/www/lib
COPY public /var/www/public
COPY soundfonts /var/www/soundfonts
COPY vendor /var/www/vendor
RUN chown -R www-data /var/www
RUN chmod -R 777 /var/www

# Env variables for the ubuntu-16-apache-php-7.0 image
ENV DOCUMENT_ROOT public
ENV UID 33

# API's PHP env variables
RUN echo "<?php define('FLUIDSYNTH_VERSION',\"$(fluidsynth -h | sed -n 1p)\"); " > /var/www/lib/const.php
RUN echo "define('LAME_VERSION',\"$(lame -? | sed -n 1p)\"); ?>" >> /var/www/lib/const.php
RUN chmod 777 /var/www/lib/const.php