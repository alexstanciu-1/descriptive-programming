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

# nano /etc/apache2/ports.conf # Listen 8080
$ports_content = file_get_contents('/etc/apache2/ports.conf');
if (!preg_match("/(^|\\n)\\s*Listen\\s+8080\\b/uis", $ports_content)) {
	$ports_content = preg_replace("/(^|\\n)\\s*Listen\\s+80\\b/uis", "\n\nListen 80\nListen 8080\n", $ports_content);
	echo "\n=========================================================\n", 
		$ports_content, "\n=========================================================\n";
	
}

s_exec("service apache2 stop");
s_exec("service apache2 start");
s_exec("service mariadb stop");
s_exec("service mariadb start");

s_exec("service php-fpm stop");
s_exec("service php-fpm start");

# systemctl start cron
# /etc/wsl.conf
# [boot]
# command="service ssh start; service cron start"

function s_exec(string $command)
{
	echo $command, "\n";
	echo shell_exec($command), "\n";
}
