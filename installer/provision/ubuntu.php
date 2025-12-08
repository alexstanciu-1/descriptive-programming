<?php

# install crontab start crontab
# start services when ubuntu starts !!!!

echo "provision/ubuntu\n";

s_exec("DEBIAN_FRONTEND=noninteractive apt install -y");
s_exec("DEBIAN_FRONTEND=noninteractive apt install -f");
s_exec("DEBIAN_FRONTEND=noninteractive apt update -y");
s_exec("DEBIAN_FRONTEND=noninteractive apt install -y php-fpm apache2 mariadb-server phpmyadmin");


function s_exec(string $command)
{
	echo $command, "\n";
	echo shell_exec($command), "\n";
}
