#! /bin/bash
PROJ=$1
PASSW="$2"
BASE="$3"
test -z "$BASE" && BASE=$(pwd)

function Usage(){
  echo "Usage: CreateProj.sh PROJ PASSW BASE"
  echo "Example: CreateProj.sh taskx topsecret /home/ws/php"
  echo "+++ $*"
}
function Wait(){
    echo "= $*"
    echo "Type ENTER to continue, Strg-C to stop process"
    read ANSWER
}
if [ -z "$PROJ" ] ; then
  Usage "missing PROJ"
elif [ -z "$PASSW" ] ; then
  Usage "missing PASSW"
elif [ -z "$BASE" ] ; then
  Usage "missing BASE"
else
  sudo apt install php-laravel-framework npm php-intl
  cd $BASE
  composer create-project laravel/laravel $PROJ
  BASE=$BASE/$PROJ
  cd $BASE
  Wait "BASE is $BASE: Is that correct?"
  cat >project.env <<EOS
PROJ=$PROJ
PASSW=$PASSW
BASE=$BASE
EOS
  composer require laravel/ui
  composer require spatie/laravel-permission
  php artisan ui bootstrap
  Wait "laravel installed"
  sudo mysql mysql <<EOS
CREATE DATABASE lrv$PROJ;
GRANT ALL ON lrv$PROJ.* to '$PROJ'@'localhost' IDENTIFIED BY '$PASSW';
EOS
  sudo mysql -u $PROJ "-p$PASSW" lrv$PROJ <<EOS
show tables;
EOS
  Wait "Database lrv$PROJ and user $PROJ created"
  sed -i \
  -e "s/DB_CONNECTION=.*/DB_CONNECTION=mysql/" \
  -e "s/^#* *DB_HOST/DB_HOST/" \
  -e "s/^#* *DB_PORT/DB_PORT/" \
  -e "s/^#* *DB_DATABASE=.*/DB_DATABASE=lrv$PROJ/" \
  -e "s/^#* *DB_USERNAME=.*/DB_USERNAME=$PROJ/" \
  -e "s/^#* *DB_PASSWORD=.*/DB_PASSWORD=$PASSW/" .env
  M_HOST=smtp.web.de
  M_PORT=587
  M_USER=laraknife@web.de
  M_PW=Be.Happy4711
  sed -i \
  -e "s/APP_NAME=.*/APP_NAME=$PROJ/" \
  -e "s/APP_LOCALE=en.*/APP_LOCALE=de_DE/" \
  -e "s/MAIL_MAILER=.*/MAIL_MAILER=smtp/" \
  -e "s/MAIL_HOST=.*/MAIL_HOST=$M_HOST/" \
  -e "s/MAIL_PORT=.*/MAIL_PORT=$M_PORT/" \
  -e "s/MAIL_USERNAME=.*/MAIL_USERNAME=$M_USER/" \
  -e "s/MAIL_PASSWORD=.*/MAIL_PASSWORD=$M_PW/" \
  -e "s/MAIL_ENCRYPTION=.*/MAIL_ENCRYPTION=STARTTLS/" \
  -e "s/MAIL_FROM_ADDRESS=.*/MAIL_FROM_ADDRESS=\"\${MAIL_USERNAME}\"/" .env
  sudo sed -i -e "3 i 127.0.0.1 $PROJ.test" /etc/hosts
  grep "$PROJ.test" /etc/hosts
  php artisan migrate
  npm install
  Wait "npm is installed. Please type Strg-C after the next step" 
  npm build dev
  SCRIPT2=/tmp/IncludeLara.sh
  wget "https://public.hamatoma.de/public/IncludeLara.sh" -O $SCRIPT2
  chmod +x $SCRIPT2
  echo "= start $SCRIPT2 git $BASE"
  php artisan lang:publish
fi
