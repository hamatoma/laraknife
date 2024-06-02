#! /bin/bash
INCLUDE=missing.seeders
test -f $INCLUDE && . $INCLUDE
if [ "$(id -u)" != 0 ]; then
  echo "+++ be root!"
elif [ -z "MISSING" ]; then
  echo "+++ missing $INCLUDE"
else
  rm -f storage/logs/laravel.log
  for module in $MISSING; do
    echo "= $module"
    sudo -u hm php artisan db:seed --class=${module}Seeder
  done
fi

