#! /bin/bash

MODE=$1
PROJ=$(basename $(pwd))
# ===
function Usage(){
  cat <<EOS
Usage: laraknife-tool.sh TASK
  Run it from the root directory of the composer package
<TASKS>:
  build-links [--force]
    Creates links for unchangable files in laraknife
    --force: existing links will be removed before
  init-i18n
    Setup I18N
  fill-db
    Puts records into tables
  adapt-modules
    Integrates the laraknife modules into the project
  create-layout
    Creates a layout blade file
  create-home
    Creates a homepage
  move-to-laraknife <module>
    Moves all files of the given module to the laraknife directory.
EOS
  echo "+++ $*"
}
# ===
function AdaptModules(){
  local fn=app/Models/User.php
  found=$(grep role_id $fn)
  if [ -n "$found" ]; then
    echo "= role_id already found"
  else
    sed -i -e "s/'password'/'password',#N#    'role_id'/" -e 's/#N#/\n/g' $fn
  fi
  grep -A5 fillable $fn
  fn=routes/web.php
  found=$(grep RoleController::routes $fn)
  if [ -n "$found" ]; then
    echo "= RoleController::routes already found"
  else
    sed -i -e 's/Route;/Route;#N#use App\\Http\\Controllers\\RoleController;#N#use App\\Http\\Controllers\\UserController;#N#use App\\Http\\Controllers\\SPropertyController;/' \
      -e 's/Auth::routes..;/Auth::routes();#N#RoleController::routes();#N#SPropertyController::routes();#N#UserController::routes();/' \
      -e 's/#N#/\n/g' \
      $fn
    echo "= routes adapted:"
  fi
  grep -A3 RoleController::routes $fn
}
# ===
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
    for module in SProperty Role User Note Menuitem; do
      test "$option" = "--force" && rm -fv app/Models/$module.php app/Http/Controllers/${module}Controller.php
      if [ $module != User ]; then
        ln -sv ../../$dirTemplates/Models/${module}.php app/Models/
      fi
      ln -sv ../../../$dirTemplates/Http/Controllers/${module}Controller.php app/Http/Controllers/
    done
    # === Views
    for module in laraknife sproperty role user menuitem note; do
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
      ln -s ../../$dirResources/$resource/laraknife.$resource public/$resource/laraknife.$resource public/$resource
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
    # === DB creation
    local node=''
    for file in $dirTemplates/database/migrations/*.php; do
      node=$(basename $file)
      test "$option" = "--force" && rm -fv database/migrations/$node
      ln -sv ../../$dirTemplates/database/migrations/$node database/migrations/$node
    done
    for file in $dirTemplates/database/seeders/*.php; do
      node=$(basename $file)
      test "$option" = "--force" && rm -fv database/seeders/$node
      ln -sv ../../$dirTemplates/database/seeders/$node database/seeders/$node
    done

    # === Components
    mkdir -p resources/views/components
    test "$option" = "--force" && rm -fv resources/views/components/laraknife
    ln -sv ../../../$dirResources/components/laraknife resources/views/components/
    # ==== Translations
    mkdir -p resources/lang/sources
    test "$option" = "--force" && rm -fv resources/lang/sources/laraknife.de.json
    ln -s ../../../$dirResources/lang/de_DE.json resources/lang/sources/laraknife.de.json
    mkdir -p resources/lang/de_DE
    local fn=resources/lang/de_DE/validation.php
    test "$option" = "--force" && rm -fv $fn
    ln -s ../../../$dirResources/lang/de_DE/validation.php $fn
    # Scripts:
    local script=Join
    cat <<EOS >$script
#! /bin/bash
php app/Helpers/Builder.php update:languages resources/lang/sources resources/lang/de_DE.json
EOS
    chmod +x $script
  fi
  script=Build
  cat <<'EOS' >$script
#! /bin/bash
composer update
composer dump-autoload
if [ "$1" = prod ]; then
  npm run build
else
  npm run dev
fi
EOS
  chmod +x $script
  script=Lara
  cat <<'EOS' >$script
#! /bin/bash
php app/Helpers/Builder.php $*
EOS
  chmod +x $script
  test "$option" = "--force" && rm larascripts
  ln -s $base/scripts larascripts
  composer dump-autoload
}
# ===
function CreateHome(){
 . project.env
  local fn=resources/views/home.blade.php
  sed -e "s/PROJECT/$PROJ/g" vendor/hamatoma/laraknife/templates/home.templ >$fn
  echo "= home $fn has been created"
}
# ===
function CreateLayout(){
  . project.env
  local fn=resources/views/layouts/$PROJ.blade.php
  sed -e "s/PROJECT/$PROJ/g" vendor/hamatoma/laraknife/templates/layout.templ >$fn
  cd resources/views/layouts
  ln -sv $PROJ.blade.php backend.blade.php
  cd ../../..
  echo "= layout $fn and backend.blade.php have been created"
}
# ===
function FillDb(){
  php artisan migrate
  php artisan db:seed
}
# ===
function InitI18N(){
  local fn=config/app.php
  if [ ! -f $fn ]; then
    echo "++ missing $fn"
  else
    sed -i -e "s/'locale' => 'en'/'locale' => 'de_DE',#N#'available_locales' => [#N#  'English' => 'en',#N#  'German' => 'de_DE'#N#]/" \
    -e 's/#N#/\n/g' $fn
    grep -A4 "locale.*de_DE" $fn
    local fn=resources/lang/sources/$PROJ.de.json
    if [ ! -f $fn ]; then
      cat <<EOS >$fn
{
"!comment": "Bitte alphabetisch sortiert eintragen",
"ZZZZZ_last": ""
}
EOS
      echo "created: $fn"
      ls -l $fn
    fi
  fi
}
# ===
function LinkModule(){
  local module="$1"
  if [ -z "$module" ]; then
    Usage "missing <module>"
  elif [ ! -d vendor/hamatoma/laraknife/resources/views/$module ]; then
    Usage "unknown module: $module (missing vendor/hamatoma/laraknife/resources/views/$module)"
  elif [ -d resources/views/$module ]; then
    Usage "$module already exists: resources/views/$module"
  else
    local Module="$(echo ${module:0:1} | tr '[:lower:]' '[:upper:]')${module:1}"
    ln -sv  ../../vendor/hamatoma/laraknife/resources/views/$module resources/views/
    ln -sv ../../../vendor/hamatoma/laraknife/templates/Http/Controllers/${Module}Controller.php app/Http/Controllers
    ln -sv ../../vendor/hamatoma/laraknife/templates/Models/${Module}.php app/Models
    local src=vendor/hamatoma/laraknife/templates/database/migrations
    local node
    for file in $src/*_${module}*.php; do
      node=$(basename $file)
      ln -sv ../../vendor/hamatoma/laraknife/templates/database/migrations/$node database/migrations/$node
    done
    ln -sv ../../vendor/hamatoma/laraknife/templates/database/migrations/${Module}Seeder.php database/seeders/${Module}Seeder.php
    done
  fi
}
# ===
function MoveToLaraknife(){
  . project.env
  local module="$1"
  if [ -z "$module" ]; then
    Usage "missing <module>"
  elif [ ! -d resources/views/$module ]; then
    Usage "unknown module: $module (missing resources/views/$module)"
  elif [ -d vendor/hamatoma/laraknife/resources/views/$module ]; then
    Usage "$module already exists"
  else
    local Module="$(echo ${module:0:1} | tr '[:lower:]' '[:upper:]')${module:1}"
    mv -v resources/views/$module vendor/hamatoma/laraknife/resources/views
    ln -sv  ../../vendor/hamatoma/laraknife/resources/views/$module resources/views
    mv -v app/Http/Controllers/${Module}Controller.php vendor/hamatoma/laraknife/templates/Http/Controllers
    ln -sv ../../../vendor/hamatoma/laraknife/templates/Http/Controllers/${Module}Controller.php app/Http/Controllers
    mv -v app/Models/${Module}.php vendor/hamatoma/laraknife/templates/Models
    ln -sv ../../vendor/hamatoma/laraknife/templates/Models/${Module}.php app/Models
    local fn=$(ls -1 database/migrations/*create_${module}*.php | head -n1)
    if [ -f $fn ]; then
      local node=$(basename $fn)
      mv -v $fn vendor/hamatoma/laraknife/templates/database/migrations
      ln -sv ../../vendor/hamatoma/laraknife/templates/database/migrations/$node database/migrations
    fi
    fn=$(ls -1 database/seeders/${Module}Seeder.php | head -n1)
    if [ -f $fn ]; then
      local node=$(basename $fn)
      mv -v $fn vendor/hamatoma/laraknife/templates/database/seeders
      ln -sv ../../vendor/hamatoma/laraknife/templates/database/seeders/$node database/seeders/$node
    fi
  fi
}
case $MODE in
adapt-modules)
  AdaptModules
  ;;
build-links)
  BuildLinks $2
  ;;
create-layout)
  CreateLayout
  ;;
create-home)
  CreateHome
  ;;
fill-db)
  FillDb
  ;;
init-i18n)
  InitI18N
  ;;
link-module)
  LinkModule "$2"
  ;;
move-to-laraknife)
  MoveToLaraknife "$2"
  ;;
rest)
  InitI18N
  FillDb
  AdaptModules
  CreateLayout
  CreateHome
  ;;
*)
  Usage "unknown TASK: $MODE"
  ;;
esac
