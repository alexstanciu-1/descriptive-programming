<?php

# install crontab start crontab
# start services when ubuntu starts !!!!

echo "provision/ubuntu\n";

shell_exec("apt install -y");
shell_exec("apt install -f");
shell_exec("apt update -y");
shell_exec("apt install -y php-fpm apache2 mariadb-server phpmyadmin");

