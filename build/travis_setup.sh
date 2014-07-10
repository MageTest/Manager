#!/usr/bin/env bash
composer install --prefer-source

# Packages
sudo apt-get install -y apache2 libapache2-mod-fastcgi
sudo a2enmod rewrite actions fastcgi alias
sudo cp ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf.default ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf
echo "cgi.fix_pathinfo = 1" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
~/.phpenv/versions/$(phpenv version-name)/sbin/php-fpm

# Configure Apache
WEBROOT="$(pwd)/vendor/magetest/magento/src"
sudo echo "<VirtualHost *:80>
  DocumentRoot $WEBROOT

  <Directory $WEBROOT>
    Options FollowSymLinks MultiViews ExecCGI
    AllowOverride All
    Order deny,allow
    Allow from all
  </Directory>

  # Wire up Apache to use Travis CI's php-fpm.
  <IfModule mod_fastcgi.c>
    AddHandler php5-fcgi .php
    Action php5-fcgi /php5-fcgi
    Alias /php5-fcgi /usr/lib/cgi-bin/php5-fcgi
    FastCgiExternalServer /usr/lib/cgi-bin/php5-fcgi -host 127.0.0.1:9000 -pass-header Authorization
  </IfModule>

</VirtualHost>" | sudo tee /etc/apache2/sites-available/default > /dev/null

sudo service apache2 restart

# Configure custom domain
echo "127.0.0.1 manager.dev" | sudo tee --append /etc/hosts

# Install Magento sample data
wget -O magento-sample-data-1.6.1.0.tar.gz http://www.magentocommerce.com/downloads/assets/1.6.1.0/magento-sample-data-1.6.1.0.tar.gz
tar -xzf magento-sample-data-1.6.1.0.tar.gz
mysql -uroot -e 'CREATE DATABASE 'magento';'
mysql -uroot magento < magento-sample-data-1.6.1.0/magento_sample_data_for_1.6.1.0.sql

# Install Magento CE 1.8
php -f vendor/magetest/magento/src/install.php -- --license_agreement_accepted yes --locale en_GB --timezone Europe/London --default_currency GBP --db_host localhost --db_name magento --db_user root --db_pass "" --url http://manager.dev/ --skip_url_validation yes --use_rewrites yes --use_secure no --secure_base_url --use_secure_admin no --admin_firstname admin --admin_lastname admin --admin_email admin@example.com --admin_username admin --admin_password adminadmin123123
