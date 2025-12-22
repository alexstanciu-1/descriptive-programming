#!/bin/bash

pwd

# just for dev-mode for install scripts
	git commit -am update
	git pull
	git push
# end

wsl --install -d Ubuntu

# run a update if needed
# wsl --update

wsl -u root -d Ubuntu -- apt update
wsl -u root -d Ubuntu -- apt install -y git php

wsl -u root -d Ubuntu -- chown root:root /usr/share/descriptive-app -R
wsl -u root -d Ubuntu -- git clone https://github.com/alexstanciu-1/descriptive-programming.git /usr/share/descriptive-app

# make sure we update, in case it's not the first run
wsl -u descriptive-app -d Ubuntu -- git -C /usr/share/descriptive-app pull

# This is the main provisioner. We need root access.
# wsl -u root -d Ubuntu -- php -r "require '/home/'.strtolower('%username%').'/descriptive-app/installer/provision/ubuntu.php';" %username% wsl '%mypath%'
wsl -u root -d Ubuntu -- php '/usr/share/descriptive-app/installer/provision/ubuntu.php' '%username%' '%mypath%' wsl

start http://localhost:8080

read -p "Press enter to continue"
