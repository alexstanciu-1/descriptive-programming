<?php

# install crontab start crontab
# start services when ubuntu starts !!!!

echo "provision/ubuntu\n";

shell_exec("apt update");
shell_exec("apt install php-fpm apache2 mariadb-server phpmyadmin");

