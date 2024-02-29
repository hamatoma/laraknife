#! /bin/bash
INCLUDE=missing.seeders
test -f $INCLUDE && . $INCLUDE
if [ -z "MISSING" ]; then
  echo "+++ missing $INCLUDE"
else
  for module in $MISSING; then
    echo "= $module"
    php artisan db:seeder --class=${module}Seeder
  done
fi
