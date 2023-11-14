#! /bin/bash

MODE=$1

function Usage(){
  echo "Usage: laraknife-tool.sh build-links [[--force]]}"
  echo "  run it from the root directory of the composer package"
  echo "  --force: existing links will be removed before"
  echo "+++ $*"
}
function BuildLinks(){
  local option="$1"
  local base=vendor/hamatoma/laraknife
  local resources=$base/resources
  local templates=$base/templates
  if [ ! -d $resources ]; then
    Usage "wrong current directory: use root directory of the package. [missing $resources]"
  elif [ ! -d $templates ]; then
    Usage "wrong current directory: use root directory of the package. [missing $templates]"
  else
    for module in SProperty User; do
      test "$option" = "--force" && rm -fv app/Models/$module.php app/Http/Controllers/${module}Controller.php
      if [ $module != User ]; then
        ln -sv ../../$templates/Models/${module}.php app/Models/
      fi
      ln -sv ../../../$templates/Http/Controllers/${module}Controller.php app/Http/Controllers/
    done
    for module in laraknife sproperty; do
      test "$option" = "--force" && rm -fv resources/views/$module
      ln -sv ../../$resources/views/$module/ resources/views/
    done
    for file in $templates/database/migrations/*.php; do
      local node=$(basename $file)
      test "$option" = "--force" && rm -fv database/migrations/$node
      ln -sv ../../$templates/database/migrations/$node database/migrations/$node
    done
    mkdir -p resources/views/components
    test "$option" = "--force" && rm -fv resources/views/components/laraknife
    ln -sv ../../../$resources/components/laraknife resources/views/components/
  fi
}
case $MODE in
build-links)
  BuildLinks $2
  ;;
*)
  Usage "unknown MODE: $MODE"
  ;;
esac
