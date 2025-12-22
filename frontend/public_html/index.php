<?php

# echo "System and services were installed.";
# This worked fast (.2 sec - (wsl v1)):
#		root@randdesk:/mnt/d/Work_2020/TRAVEL/ECOMM - REPOS/TF_dev_on_DEV002# 
#			time find ./ -type f -not -path "*/.git/*" -not -path "*/gens/*" -newer travelfuse/backend_patches/Categories_Travel_Items/Categories_Travel_Items.patch.php
# this also works ... modif last 10 mins: time find ./ -type f -not -path "*/.git/*" -not -path "*/gens/*" -mmin -10
#					also 0.2 sec (wsl v1)
#					also 8.2 sec !!! (wsl v2 !!!)

# var_dump($username = posix_getpwuid(posix_geteuid())['name'], $_ENV);

# SYNC CODE HERE (if needed!)
if (true)
{
	$install_args = json_decode(file_get_contents("../../../descriptive-app.setup-conf.json"));

	if ($install_args->args[1] === 'wsl') {
		# map it on wsl
		$sync_path = trim($install_args->args[3], '"');
		$sync_path = preg_replace_callback("/^(\w+)\\:/uis", function ($m) { return strtolower($m[1]);}, $sync_path);
		$sync_path = "/mnt/".preg_replace("/(\\\\)/uis", "/", $sync_path);
	}
	else {
		echo "ONLY Windows WSL VM implemented atm.";
		exit;
	}

	$sync_path = dirname(dirname($sync_path));
	$sync_path = rtrim($sync_path, "/")."/";

	$local_path = realpath("../..");

	$cmd = "find ".escapeshellarg($sync_path)." -type f -not -path \"*/.git/*\" -not -path \"*/gens/*\" -mmin -30";

	$out = shell_exec($cmd);

	echo $out;

	$files = preg_split("/\\n/uis", $out, -1, PREG_SPLIT_NO_EMPTY);

	var_dump('$files', $files);

	foreach ($files as $f) {
		var_dump($f, filemtime($f), substr(file_get_contents($f), 0, 128));
	}
	exit;
}

require __DIR__.'/main.tpl';

