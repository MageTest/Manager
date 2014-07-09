#!/bin/bash

cd "$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/.."

sudo apt-get update

sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'

sudo apt-get install -y curl php5 apache2 libapache2-mod-php5 php5-curl php5-gd php5-mcrypt php5-readline mysql-server-5.5 php5-mysql

sudo sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php5/apache2/php.ini
sudo sed -i "s/display_errors = .*/display_errors = On/" /etc/php5/apache2/php.ini
sudo sed -i "s/disable_functions = .*/disable_functions = /" /etc/php5/cli/php.ini
sudo sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php5/apache2/php.ini
sudo sed -i "s#date\.timezone.*#date\.timezone = \"Europe\/London\"#" /etc/php5/apache2/php.ini

sudo a2enmod rewrite
sudo php5enmod mcrypt

mysql -uroot -proot -e "SET PASSWORD = PASSWORD('');"
wget -O magento-sample-data-1.6.1.0.tar.gz http://www.magentocommerce.com/downloads/assets/1.6.1.0/magento-sample-data-1.6.1.0.tar.gz
tar -xzf magento-sample-data-1.6.1.0.tar.gz
mysql -uroot -e 'CREATE DATABASE 'magento';'
mysql -uroot magento < magento-sample-data-1.6.1.0/magento_sample_data_for_1.6.1.0.sql

sudo cp -f build/apache /etc/apache2/sites-available/manager.conf
sudo sed -e "s?%BUILD_DIR%?$(pwd)?g" --in-place /etc/apache2/sites-available/manager.conf
sudo a2ensite manager
sudo service apache2 restart

php -f vendor/magetest/magento/src/install.php -- --license_agreement_accepted yes --locale en_GB --timezone Europe/London --default_currency GBP --db_host localhost --db_name magento --db_user root --db_pass "" --url http://manager.dev/ --skip_url_validation yes --use_rewrites yes --use_secure no --secure_base_url --use_secure_admin no --admin_firstname admin --admin_lastname admin --admin_email admin@example.com --admin_username admin --admin_password adminadmin123123

sudo sh -c 'echo "127.0.0.1 manager.dev" >> /etc/hosts'
