# Source image
#FROM mattrayner/lamp:latest-1604
#FROM 1and1internet/ubuntu-16-apache-php-phpmyadmin-mysql-5
FROM 1and1internet/ubuntu-16-apache-php-7.2
#FROM php:7.2-apache

# Fluidsynth (midi -> wav) et lame (wav -> mp3) installation | ffmpeg pro mix výsledných mp3
RUN apt-get update
#RUN  apt-get install -y php7.2
RUN  apt-get install sqlite3 libsqlite3-dev -y
RUN  apt-get install -y fluidsynth

RUN  apt-get install -y  lame
RUN  apt-get install -y   ffmpeg
##RUN  apt-get install -y curl
RUN  apt-get install -y php7.0-curl
RUN  apt-get install -y  mp3info
RUN  apt-get install -y  nano
RUN  apt-get install -y cron

#RUN apt-get install dialog apt-utils -y

# MySQL

#ENV MYSQL_PWD HesloKMYSQLPROMM2020
#RUN export TERM=xterm
#RUN echo "mysql-server mysql-server/root_password password $MYSQL_PWD" | debconf-set-selections
#RUN echo "mysql-server mysql-server/root_password_again password $MYSQL_PWD" | debconf-set-selections
#RUN sudo apt-get -y install mysql-server



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

# CRON SETUP
#* * * * * php /var/www/lib/cronRunner.php

#RUN sqlite3 -init /var/www/lib/db.sqlite
#RUN CREATE TABLE "queue" ("id" integer,"state" varchar DEFAULT 'waiting', PRIMARY KEY (id));
#RUN CREATE TABLE "sf" ("id" integer, "name" varchar FIRST, "uuid" varchar FIRST, "type" varchar FIRST, "location" varchar FIRST, "source" text FIRST, "downloaded" varchar FIRST, PRIMARY KEY (id));