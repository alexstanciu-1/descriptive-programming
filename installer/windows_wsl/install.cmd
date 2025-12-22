:: !!! WE ASUME %username% is the linux user to be used
:: test command: git commit -am update; git push; "./installer/windows_wsl/install.cmd"

:: remove current ubuntu
:: wslconfig /u Ubuntu

cd /D "%~dp0"
start "" "%ProgramFiles%\Git\git-bash.exe" -c ./install.sh

exit
