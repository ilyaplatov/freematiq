#!/usr/bin/env bash

#== Import script args ==

timezone=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Add PHP 7.1 repository"
add-apt-repository ppa:ondrej/php -y

info "Update OS software"
apt-get update
apt-get upgrade -y

info "Install additional software"
apt-get install -y php7.1-curl php7.1-cli php7.1-intl php7.1-pgsql php7.1-gd php7.1-fpm php7.1-mbstring php7.1-xml unzip nginx php.xdebug

info "Install PostgreSQL"
apt-get install -y postgresql-9.5 postgresql-client-9.5 postgresql-contrib-9.5

info "Configure PostgreSQL"
sudo -u postgres psql -c "CREATE USER root WITH PASSWORD 'root'"
service postgresql restart
echo "Done!"

info "Configure PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
cat << EOF > /etc/php/7.1/mods-available/xdebug.ini
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.remote_port=9000
xdebug.remote_autostart=1
EOF
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Initailize databases for PostgreSQL"
sudo -u postgres psql -c "CREATE DATABASE yii2advanced"
sudo -u postgres psql -c "CREATE DATABASE yii2advanced_test"
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE yii2advanced TO root"
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE yii2advanced_test TO root"
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer