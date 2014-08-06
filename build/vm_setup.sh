#!/usr/bin/env bash

#need this to get 'libapache2-mod-fastcgi'
sudo sed -i "/^# deb.*multiverse/ s/^# //" /etc/apt/sources.list
sudo apt-get update

sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'

sudo apt-get install -y curl apache2 libapache2-mod-fastcgi php5 php5-fpm php5-cli php5-curl php5-gd php5-mcrypt php5-mysql phpt5-xdebug mysql-server

sudo sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php5/fpm/php.ini
sudo sed -i "s/display_errors = .*/display_errors = On/" /etc/php5/fpm/php.ini
sudo sed -i "s/disable_functions = .*/disable_functions = /" /etc/php5/cli/php.ini
sudo sed -i "s/memory_limit = .*/memory_limit = 1024M/" /etc/php5/fpm/php.ini
sudo sed -i "s#date\.timezone.*#date\.timezone = \"Europe\/London\"#" /etc/php5/fpm/php.ini

sudo a2enmod rewrite actions fastcgi alias

sudo bash -c "cat >> /etc/apache2/conf.d/servername.conf <<EOF
ServerName localhost
EOF"

WEBROOT="/vagrant/vendor/magetest/magento/src"
sudo echo "<VirtualHost *:80>
  DocumentRoot $WEBROOT

  <Directory $WEBROOT>
    Options FollowSymLinks MultiViews ExecCGI
    AllowOverride All
    Order deny,allow
    Allow from all
  </Directory>

  <IfModule mod_fastcgi.c>
    AddHandler php5-fcgi .php
    Action php5-fcgi /php5-fcgi
    Alias /php5-fcgi /usr/lib/cgi-bin/php5-fcgi
    FastCgiExternalServer /usr/lib/cgi-bin/php5-fcgi -host 127.0.0.1:9000 -pass-header Authorization
  </IfModule>

</VirtualHost>" | sudo tee /etc/apache2/sites-available/default > /dev/null

sudo bash -c "cat >> /etc/php5/conf.d/xdebug.ini <<EOF
xdebug.default_enable = 1
xdebug.remote_autostart = 1
xdebug.remote_connect_back = 1
xdebug.remote_enable = 1
xdebug.remote_handler = "dbgp"
xdebug.remote_port = 9000
EOF"

sudo service apache2 restart
sudo service php5-fpm restart

mysql -uroot -proot -e "SET PASSWORD = PASSWORD('');"
echo 'Installing Magento sample data...'
wget -O magento-sample-data-1.6.1.0.tar.gz http://www.magentocommerce.com/downloads/assets/1.6.1.0/magento-sample-data-1.6.1.0.tar.gz 2> /dev/null
tar -xzf magento-sample-data-1.6.1.0.tar.gz
mysql -uroot -e 'CREATE DATABASE 'magento';'
mysql -uroot magento < magento-sample-data-1.6.1.0/magento_sample_data_for_1.6.1.0.sql
rm -rf magento-sample-data-1.6.1.0*

php -f /vagrant/vendor/magetest/magento/src/install.php -- --license_agreement_accepted yes --locale en_GB --timezone Europe/London --default_currency GBP --db_host localhost --db_name magento --db_user root --db_pass "" --url http://manager.dev/ --skip_url_validation yes --use_rewrites yes --use_secure no --secure_base_url --use_secure_admin no --admin_firstname admin --admin_lastname admin --admin_email admin@example.com --admin_username admin --admin_password adminadmin123123

sudo bash -c "cat >> /etc/hosts <<EOF
127.0.0.1 manager.dev
EOF"
