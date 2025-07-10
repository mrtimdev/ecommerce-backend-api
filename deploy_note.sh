


scp delivery_system.sql timdev@38.242.149.46:/var/www/php-project/uploads/
mysql -u root -p kcy_delivery_system < /var/www/php-project/uploads/database.sql


DROP DATABASE IF EXISTS kcy_delivery_system;
CREATE DATABASE kcy_delivery_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;


git clone -b Tim-delivery-system https://github.com/mrtimdev/ecommerce-backend-api.git source

git pull origin Tim-delivery-system


git stash
git pull origin Tim-delivery-system
git stash pop


sudo nano /etc/apache2/sites-available/kcy.conf

sudo nano /etc/apache2/ports.conf

sudo a2ensite kcy.conf
sudo systemctl reload apache2


tail -f /var/www/php-project/kcy-logistic/source/storage/logs/laravel.log

