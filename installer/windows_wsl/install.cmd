:: !!! WE ASUME %username% is the linux user to be used
:: test command: git commit -am update; git push; "./installer/windows_wsl/install.cmd"

:: remove current ubuntu
:: wslconfig /u Ubuntu

cd /D "%~dp0"
SET mypath="%~dp0"
:: start /B /wait "" "%ProgramFiles%\Git\git-bash.exe" -c ./install.sh

:: just for dev-mode for install scripts
"%ProgramFiles%\Git\cmd\git" commit -am update
"%ProgramFiles%\Git\cmd\git" pull
"%ProgramFiles%\Git\cmd\git" push
:: end

wsl --install -d Ubuntu

:: run a update if needed
:: wsl --update

:: minimal required to boot provision
wsl -u root -d Ubuntu -- apt update
wsl -u root -d Ubuntu -- apt install -y git php

:: get the repo or update it
wsl -u root -d Ubuntu -- chown root:root /usr/share/descriptive-app -R
wsl -u root -d Ubuntu -- git clone https://github.com/alexstanciu-1/descriptive-programming.git /usr/share/descriptive-app
:: make sure we update, in case it's not the first run
wsl -u root -d Ubuntu -- git -C /usr/share/descriptive-app pull

:: This is the main provisioner. We need root access.
wsl -u root -d Ubuntu -- php '/usr/share/descriptive-app/installer/provision/ubuntu.php' wsl '%username%' '%mypath%'

start http://localhost:8080

pause
