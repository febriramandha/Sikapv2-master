# Sikapv2-master

konfigurasi git
- login user ssh
- $ ssh-keygen -t rsa
- copy public key cat .ssh/id_rsa.pub
- repository->Sikap-Agamkab->setting->add Deploy keys

instal app #server
- cd /var/www/html/
- git clone git@github.com:rianreski/Sikap-Agamkab.git
- download folder uploads
- $ cp index.example.php index.php
- $ cp database.example.php database.php
- $ sudo chgrp -R www-data uploads
- $ sudo chmod 775 uploads

instal app #local
- htdocs
- git clone https://github.com/rianreski/Sikap-Agamkab.git
- download folder uploads
- $ cp index.example.php index.php
- $ cp database.example.php database.php

update app server
- login user ssh
- $ sh deploy.sh 
- $ git pull origin master

database postgre 9.6
sikap_1

