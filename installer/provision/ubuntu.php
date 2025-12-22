<?php

# THIS WILL RUN AS `root` user !!!

# install crontab start crontab
# start services when ubuntu starts !!!!

/**
 *	@TODO
 *		there should be a command line
 * 
 */

/*

PLAN:
	descriptive-app | sudoer | - able to run a /etc/sudoers.d/


 */


echo "provision v0.2\n";

$arg_vm_type = trim($argv[1] ?? '');
$arg_host_user = trim($argv[2] ?? '');
$arg_host_path = trim($argv[3] ?? '');

if ($arg_vm_type === 'wsl') {
	$arg_host_user = strtolower($arg_host_user);
}
if (strlen($arg_host_user) === 0) {
	$arg_host_user = null;
}

if (($arg_host_user !== null) && (!preg_match("/^[\\w\\_\\-\\d]+\$/uis", $arg_host_user))) {
	echo "Invalid username: {$arg_host_user}.";
	exit;
}

if ($arg_host_user === 'root') {
	echo "Not expected to be provisioned for `root`.";
	exit;
}
else if ($arg_host_user === null) {
	$provision_for_user = "descriptive-app";
	# making descriptive-app a sudoer so it can run privisioning scripts and other stuff
	s_exec("useradd -m " . escapeshellarg($provision_for_user));
	s_exec("sudo adduser " . escapeshellarg($provision_for_user) . " sudo");
}
else if ($arg_host_user && (!is_dir("/home/{$arg_host_user}"))) {
	echo "Invalid user dir for: {$arg_host_user}.";
	exit;
}
else {
	$provision_for_user = $arg_host_user;
}

if (!$provision_for_user) {
	echo "MISSING USER\n\n";
	exit;
}
if (!is_dir("/home/{$provision_for_user}")) {
	echo "MISSING USER's HOME DIR\n\n";
	exit;
}

var_dump("provision @user: {$provision_for_user}\n\n");

if (!is_dir("/home/{$provision_for_user}/logs")) {
	mkdir("/home/{$provision_for_user}/logs");
	s_exec("chown {$provision_for_user}:{$provision_for_user} /home/{$provision_for_user}/logs");
}

s_exec("git clone https://github.com/alexstanciu-1/descriptive-programming.git /home/{$provision_for_user}/descriptive-app");
# make sure we update, in case it's not the first run
s_exec("git -C /home/{$provision_for_user}/descriptive-app pull");
s_exec("chown {$provision_for_user}:{$provision_for_user} /home/{$provision_for_user}/descriptive-app -R");

s_exec("chmod +x /home/{$provision_for_user}");

s_exec("DEBIAN_FRONTEND=noninteractive apt install -y");
s_exec("DEBIAN_FRONTEND=noninteractive apt install -f");
s_exec("DEBIAN_FRONTEND=noninteractive apt update -y");
s_exec("DEBIAN_FRONTEND=noninteractive apt install -y php-fpm apache2 apache2-suexec-custom mariadb-server phpmyadmin cron");

# s_exec("a2dismod mpm_prefork php".PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION);
# s_exec("a2enmod mpm_event"); # this is not working ok ! not needed on dev boxes !
s_exec("a2enmod proxy proxy_fcgi setenvif suexec rewrite alias http2");

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
	if ($ports_content !== file_get_contents('/etc/apache2/ports.conf')) {
		file_put_contents('/etc/apache2/ports.conf', $ports_content);
	}
}

# /etc/apache2/sites-available/
(function ($User) {
	
	$FPM_Pool = 'descriptive-app';
	# $User will be used in the require
	ob_start();
	require __DIR__ . '/apache.conf';
	$conf = ob_get_clean();
	if ($conf !== file_get_contents('/etc/apache2/sites-available/descriptive-app.conf')) {
		file_put_contents("/etc/apache2/sites-available/descriptive-app.conf", $conf);
	}
	
})($provision_for_user);

# /etc/apache2/sites-available/
(function ($User) {

	$FPM_Pool = 'descriptive-app';
	# $User will be used in the require
	ob_start();
	require __DIR__ . '/php-fpm.conf';
	$conf = ob_get_clean();
	if ($conf !== file_get_contents("/etc/php/".PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION."/fpm/pool.d/descriptive-app.conf")) {
		file_put_contents("/etc/php/".PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION."/fpm/pool.d/descriptive-app.conf", $conf);
	}
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
