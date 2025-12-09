<?php

# install crontab start crontab
# start services when ubuntu starts !!!!

echo "provision/ubuntu\n";

$provision_for_user = trim($argv[1] ?? '');

var_dump('$provision_for_user: ' . $provision_for_user . "\n");
echo "\n\n";

s_exec("DEBIAN_FRONTEND=noninteractive apt install -y");
s_exec("DEBIAN_FRONTEND=noninteractive apt install -f");
s_exec("DEBIAN_FRONTEND=noninteractive apt update -y");
s_exec("DEBIAN_FRONTEND=noninteractive apt install -y php-fpm apache2 mariadb-server phpmyadmin cron");

# systemctl start cron
# /etc/wsl.conf
# [boot]
# command="service ssh start; service cron start"

function s_exec(string $command)
{
	echo $command, "\n";
	echo shell_exec($command), "\n";
}
