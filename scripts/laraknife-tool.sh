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
    // === Modules
    for module in SProperty User Exchange; do
      test "$option" = "--force" && rm -fv app/Models/$module.php app/Http/Controllers/${module}Controller.php
      if [ $module != User ]; then
        ln -sv ../../$templates/Models/${module}.php app/Models/
      fi
      ln -sv ../../../$templates/Http/Controllers/${module}Controller.php app/Http/Controllers/
    done
    // === Views
    for module in laraknife sproperty user exchange; do
      test "$option" = "--force" && rm -fv resources/views/$module
      ln -sv ../../$resources/views/$module/ resources/views/
    done
    // === Helpers
    for module in DbHelper OsHelper ViewHelper; do
      test "$option" = "--force" && rm -fv app/helpers/$module
      ln -sv ../../$resources/helpers/$module/ app/helpers/
    done
    // CSS+JS
    for resource in css js; do
      mkdir -p public/$resource
      test "$option" = "--force" && rm -fv public/$resource/laraknife.$resource
      ln -s ../../vendor/hamatoma/laraknife/resources/$recoure/laraknife.$resource public/$resource/laraknife.$resource
    done
    test "$option" = "--force" && rm -fv resources/views/$module
    ln -s ../../../vendor/hamatoma/laraknife/resources/js/laraknife.js
    ln -s
    // === DB creation
    for file in $templates/database/migrations/*.php; do
      local node=$(basename $file)
      test "$option" = "--force" && rm -fv database/migrations/$node
      ln -sv ../../$templates/database/migrations/$node database/migrations/$node
    done
    // === Components
    mkdir -p resources/views/components
    test "$option" = "--force" && rm -fv resources/views/components/laraknife
    ln -sv ../../../$resources/components/laraknife resources/views/components/
    // ==== Translations
    mkdir -p resources/lang/sources
    ln -s ../../../../laraknife/resources/lang/de_DE.json resources/lang/sources/laraknife.de.json
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
