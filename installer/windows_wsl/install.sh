#!/bin/bash

export MSYS_NO_PATHCONV=1

username="$(whoami)"
currentpath="$(pwd)"

echo "username: $username"
echo "currentpath: $currentpath"

# just for dev-mode for install scripts
	git commit -am update
	git pull
	git push
# end

wsl --install -d Ubuntu

# run a update if needed
# wsl --update

# minimal required to boot provision
wsl -u root -d Ubuntu -- apt update
wsl -u root -d Ubuntu -- apt install -y git php

# get the repo or update it
wsl -u root -d Ubuntu -- chown root:root /usr/share/descriptive-app -R
wsl -u root -d Ubuntu -- git clone https://github.com/alexstanciu-1/descriptive-programming.git /usr/share/descriptive-app
# make sure we update, in case it's not the first run
wsl -u root -d Ubuntu -- git -C /usr/share/descriptive-app pull

# This is the main provisioner. We need root access.
# wsl -u root -d Ubuntu -- php /usr/share/descriptive-app/installer/provision/ubuntu.php wsl "$username" "$currentpath"
wsl -u root -d Ubuntu -- php /usr/share/descriptive-app/installer/provision/ubuntu.php wsl "descriptive-app" "$currentpath"

# export MSYS_NO_PATHCONV=0
# start 'http://localhost:8080'
