:: !!! WE ASUME %username% is the linux user to be used
:: test command: git commit -am update; git push; "./installer/windows_wsl/install.cmd"

:: remove current ubuntu
:: wslconfig /u Ubuntu

cd /D "%~dp0"

git commit -am update
git pull
git push

"%ProgramFiles%\Git\cmd\git" commit -am update
"%ProgramFiles%\Git\cmd\git" pull
"%ProgramFiles%\Git\cmd\git" push

SET mypath="%~dp0"
echo %mypath%
exit

:: make sure windows subsystem for linux is installed !!!
wsl --install -d Ubuntu

:: run a update if needed
:: wsl --update

wsl -u root -d Ubuntu -- apt update
wsl -u root -d Ubuntu -- apt install -y git php

wsl -d Ubuntu -- git clone https://github.com/alexstanciu-1/descriptive-programming.git ~/descriptive-app

:: make sure we update, in case it's not the first run
wsl -d Ubuntu -- git -C ~/descriptive-app pull

:: This is the main provisioner. We need root access.
wsl -u root -d Ubuntu -- php -r "require '/home/'.strtolower('%username%').'/descriptive-app/installer/provision/ubuntu.php';" %username% wsl %mypath%

start http://localhost:8080

pause

:: SOME COMMANDS TO KEEP IN MIND
:: go to current dir
:: cd /D "%~dp0"
:: echo Now about to end...
:: pause
:: set /p DUMMY=Hit ENTER to continue...
:: wsl whoami
:: ubuntu -c "echo $USER"
:: wsl -u root -d Ubuntu -- echo "I am $USER"
:: wsl -d Ubuntu -- echo "I am $USER"
:: wsl -d Ubuntu -- git --version
