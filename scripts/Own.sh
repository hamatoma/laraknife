#! /bin/bash
FN=.dev.user
test -f $FN && . $FN
if [ -z "$DEV_USER" ]; then
   echo "DEV_USER=$(id | egrep -o '\(\w+' | head -n1 | egrep -o '\w+')" >$FN
   echo "=== $FN created:" 
   cat $FN
else
   if [ "$1" = all ]; then
     sudo chown -R $DEV_USER:www-data .
     sudo chmod g+w -R .
     sudo find -type d -exec chmod ug+wx "{}" \;
   fi
   sudo chown -R www-data:www-data bootstrap/cache storage/logs
fi
