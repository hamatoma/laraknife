#! /bin/bash
BASE=$(pwd)
NODE=$(basename $BASE)
if [ -z "$PROJ" ]; then
  echo "+++ missing \$PROJ"
elif [ "$NODE" = laraknife ]; then
  echo "+++ wrong current directory. Go into the project base, e.g. /srv/www/taskx.hamatoma.de"
elif [ ! -d ../laraknife ]; then
  echo "+++ missing ../laraknife"
else
  mkdir -pv vendor/hamatoma
  ln -s ../../../laraknife vendor/hamatoma/laraknife
  mkdir -pv scripts
  sed -e "s/#PROJECT~/$PROJ/g" ../laraknife/templates/scripts/InitApp.templ > scripts/InitApp.sh
  chmod +x scripts/InitApp.sh
  echo "call scripts/InitApp.sh"
fi

