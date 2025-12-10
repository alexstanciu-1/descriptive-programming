<?php

# install crontab start crontab
# start services when ubuntu starts !!!!

echo "provision/ubuntu\n";

$provision_for_user = trim($argv[1] ?? '');

var_dump("provision @user: {$provision_for_user}\n\n");

s_exec("DEBIAN_FRONTEND=noninteractive apt install -y");
s_exec("DEBIAN_FRONTEND=noninteractive apt install -f");
s_exec("DEBIAN_FRONTEND=noninteractive apt update -y");
s_exec("DEBIAN_FRONTEND=noninteractive apt install -y php-fpm apache2 apache2-suexec-custom mariadb-server phpmyadmin cron");

s_exec("a2enmod alias rewrite suexec");

# nano /etc/apache2/ports.conf # Listen 8080
$ports_content = file_get_contents('/etc/apache2/ports.conf');
if (!preg_match("/(^|\\n)\\s*Listen\\s+8080\\b/uis", $ports_content)) {
	$ports_lines = explode("\n", $ports_content);
	$new_lines = [];
	foreach ($ports_lines as $line) {
		$new_lines[] = $line;
		if (preg_match("/\\s*Listen\\s+80\\b/uis", $line)) {
			$new_lines[] = "Listen 8080";
		}
	}
	$ports_content = implode("\n", $new_lines);
	echo "\n=========================================================\n", 
		$ports_content, "\n=========================================================\n";
	file_put_contents('/etc/apache2/ports.conf', $ports_content);
}

# /etc/apache2/sites-available/
(function ($User) {
	
	$FPM_Pool = 'descriptive-app';
	# $User will be used in the require
	ob_start();
	require __DIR__ . '/apache.conf';
	$conf = ob_get_clean();
	file_put_contents("/etc/apache2/sites-available/descriptive-app.conf", $conf);
	
})($provision_for_user);

# /etc/apache2/sites-available/
(function ($User) {

	$FPM_Pool = 'descriptive-app';
	# $User will be used in the require
	ob_start();
	require __DIR__ . '/php-fpm.conf';
	$conf = ob_get_clean();
	file_put_contents("/etc/php/".PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION."/fpm/pool.d/descriptive-app.conf", $conf);
	
})($provision_for_user);

s_exec("a2ensite descriptive-app.conf");

s_exec("service apache2 stop");
s_exec("service apache2 start");
s_exec("service mariadb stop");
s_exec("service mariadb start");

s_exec("service php".PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION."-fpm stop");
s_exec("service php".PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION."-fpm start");

# systemctl start cron
# /etc/wsl.conf
# [boot]
# command="service ssh start; service cron start"

function s_exec(string $command)
{
	echo $command, "\n";
	echo shell_exec($command), "\n";
}
