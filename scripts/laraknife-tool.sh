#! /bin/bash

MODE=$1
PROJ=$(basename $(pwd))
function Usage(){
  echo "Usage: laraknife-tool.sh build-links [[--force]]}"
  echo "  run it from the root directory of the composer package"
  echo "  --force: existing links will be removed before"
  echo "+++ $*"
}
function BuildLinks(){
  local option="$1"
  local base=vendor/hamatoma/laraknife
  local dirResources=$base/resources
  local dirTemplates=$base/templates
  if [ ! -d $dirResources ]; then
    Usage "wrong current directory: use root directory of the package. [missing $dirResources]"
  elif [ ! -d $dirTemplates ]; then
    Usage "wrong current directory: use root directory of the package. [missing $dirTemplates]"
  else
    # === Modules
    for module in SProperty Role User ; do
      test "$option" = "--force" && rm -fv app/Models/$module.php app/Http/Controllers/${module}Controller.php
      if [ $module != User ]; then
        ln -sv ../../$dirTemplates/Models/${module}.php app/Models/
      fi
      ln -sv ../../../$dirTemplates/Http/Controllers/${module}Controller.php app/Http/Controllers/
    done
    # === Views
    for module in laraknife sproperty role user ; do
      test "$option" = "--force" && rm -fv resources/views/$module
      ln -sv ../../$dirResources/views/$module/ resources/views/
    done
    # === Helpers
    mkdir -pv app/Helpers
    for module in DbHelper OsHelper ViewHelper StringHelper Builder Pagination; do
      test "$option" = "--force" && rm -fv app/Helpers/$module.php
      ln -sv ../../$dirResources/helpers/$module.php app/Helpers/$module.php
    done
    # CSS+JS
    for resource in css js; do
      mkdir -p public/$resource
      test "$option" = "--force" && rm -fv public/$resource/laraknife.$resource
      ln -s ../../$dirResources/$resource/laraknife.$resource public/$resource/laraknife.$resource
    done
    local fn=public/css/$PROJ.css
    cat <<EOS >$fn
.white { color: white; }
.expand100 { width: 100%;}
.nav-link, .nav-item { color: white; font-weight: bolder;}
table, tr, th, td {
    border: solid black 1px;
    border-collapse: collapse;
    border-radius: 0.25rem;
}
input, button {
    margin-bottom: 0.5rem;
    border-radius: 0.25rem;
}
.logout {
    color: #CCC;
    background-color: #333;
    border-color: #CCC;
}
EOS
    echo "= created: $fn"
    test "$option" = "--force" && rm -fv resources/views/$module
    ln -s ../../../$dirResources/js/laraknife.js
    # === DB creation
    local node=''
    for file in $dirTemplates/database/migrations/*.php; do
      node=$(basename $file)
      test "$option" = "--force" && rm -fv database/migrations/$node
      ln -sv ../../$dirTemplates/database/migrations/$node database/migrations/$node
    done
    # === Components
    mkdir -p resources/views/components
    test "$option" = "--force" && rm -fv resources/views/components/laraknife
    ln -sv ../../../$dirResources/components/laraknife resources/views/components/
    # ==== Translations
    mkdir -p resources/lang/sources
    test "$option" = "--force" && rm -fv resources/lang/sources/laraknife.de.json
    ln -s ../../../$dirResources/lang/de_DE.json resources/lang/sources/laraknife.de.json
    # Scripts:
    local script=Join
    cat <<EOS >$script
#! /bin/bash
php app/Helpers/Builder.php update:languages resources/lang/sources resources/lang/de_DE.json
EOS
    chmod +x $script
  fi
  script=Build
  cat <<EOS >$script
#! /bin/bash
composer update
composer dump-autoload
npm run build
EOS
  chmod +x $script
  script=Lara
  cat <<'EOS' >$script
#! /bin/bash
php app/Helpers/Builder.php $*
EOS
  chmod +x $script
}
case $MODE in
build-links)
  BuildLinks $2
  ;;
*)
  Usage "unknown MODE: $MODE"
  ;;
esac
