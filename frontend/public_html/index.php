<?php

# echo "System and services were installed.";
# This worked fast (.2 sec - (wsl v1)):
#		root@randdesk:/mnt/d/Work_2020/TRAVEL/ECOMM - REPOS/TF_dev_on_DEV002# 
#			time find ./ -type f -not -path "*/.git/*" -not -path "*/gens/*" -newer travelfuse/backend_patches/Categories_Travel_Items/Categories_Travel_Items.patch.php
# this also works ... modif last 10 mins: time find ./ -type f -not -path "*/.git/*" -not -path "*/gens/*" -mmin -10
#					also 0.2 sec (wsl v1)
#					also 8.2 sec !!! (wsl v2 !!!)

require __DIR__.'/main.tpl';

