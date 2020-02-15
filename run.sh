#!/bin/sh
sudo chmod +x *.sh
export OWENRE=$(whoami)
export GROUP=$(whoami)

sudo chgrp $GROUP * -Rf
sudo chown $OWENRE * -Rf
sudo find ./ -type f -exec chmod 664 {} \;
sudo find ./ -type d -exec chmod 775 {} \;
sudo chmod -R a+x vendor
sudo chmod 0777 data/cache -Rf

sudo chmod 0777 data/ -Rf

sudo rm -rf yarn.lock package-lock.json
npm cache clean --force
npm install
npm cache verify
sudo chmod -R a+x node_modules
