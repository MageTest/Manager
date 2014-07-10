#!/usr/bin/env bash

# Packages
sudo apt-get update
sudo apt-get install -y apache2 libapache2-mod-fastcgi
sudo a2enmod rewrite actions fastcgi alias
sudo cp ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf.default ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf
sudo a2enmod rewrite actions fastcgi alias
echo "cgi.fix_pathinfo = 1" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
~/.phpenv/versions/$(phpenv version-name)/sbin/php-fpm

# Configure Apache
WEBROOT="$(pwd)/vendor/magetest/magento/src"
CGIROOT=`dirname "$(which php-cgi)"`
echo "WEBROOT: $WEBROOT"
echo "CGIROOT: $CGIROOT"
sudo echo "<VirtualHost *:80>
        DocumentRoot $WEBROOT
        <Directory />
                Options FollowSymLinks
                AllowOverride All
        </Directory>
        <Directory $WEBROOT >
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
        </Directory>

		# Configure PHP as CGI
		ScriptAlias /local-bin $CGIROOT
		DirectoryIndex index.php index.html
		AddType application/x-httpd-php5 .php
		Action application/x-httpd-php5 '/local-bin/php-cgi'

</VirtualHost>" | sudo tee /etc/apache2/sites-available/default > /dev/null
cat /etc/apache2/sites-available/default

sudo service apache2 restart

# Configure custom domain
echo "127.0.0.1 manager.dev" | sudo tee --append /etc/hosts

# Install sample data
wget -O magento-sample-data-1.6.1.0.tar.gz http://www.magentocommerce.com/downloads/assets/1.6.1.0/magento-sample-data-1.6.1.0.tar.gz 2> /dev/null
tar -xzf magento-sample-data-1.6.1.0.tar.gz
mysql -uroot -e 'CREATE DATABASE 'magento';'
mysql -uroot magento < magento-sample-data-1.6.1.0/magento_sample_data_for_1.6.1.0.sql

# Install
composer install --prefer-source
php -f vendor/magetest/magento/src/install.php -- --license_agreement_accepted yes --locale en_GB --timezone Europe/London --default_currency GBP --db_host localhost --db_name magento --db_user root --db_pass "" --url http://manager.dev/ --skip_url_validation yes --use_rewrites yes --use_secure no --secure_base_url --use_secure_admin no --admin_firstname admin --admin_lastname admin --admin_email admin@example.com --admin_username admin --admin_password adminadmin123123
curl http://manager.dev
sudo cat /var/log/apache2/error.log

echo "TRAVIS_PHP_VERSION: $TRAVIS_PHP_VERSION"
