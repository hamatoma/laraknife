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
EOS
  echo "+++ $*"
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
  test "$option" = "--force" && rm larascripts
  ln -s $base/scripts larascripts
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
function FillDb(){
  php artisan migrate
  sudo mysql lrv$PROJ <<'EOS'
insert into roles (name, priority, created_at, updated_at) values 
('Administrator', 10, '2023.12.28', '2023-12-28'),
('Manager', 20, '2023.12.28', '2023-12-28'),
('User', 30, '2023.12.28', '2023-12-28'),
('Guest', 90, '2023.12.28', '2023-12-28');
insert into sproperties (id, scope, name, `order`, shortname, created_at) values
(1001, 'status', 'active', 10, 'A', '2023-12-28'),
(1002, 'status', 'inactive', 20, 'I', '2023-12-28'),
(2001, 'category', 'standard', 10, '-', '2023-12-28'),
(2002, 'category', 'private', 20, 'P', '2023-12-28'),
(2003, 'category', 'work', 30, 'W', '2023-12-28'),
(2601, 'notestatus', 'open', 10, 'O', '2023-12-28'),
(2602, 'notestatus', 'closed', 20, 'C', '2023-12-28');
select count(*) from roles as role_count;
select count(*) from sproperties as sproperty_count;
EOS
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
function CreateHome(){
 . project.env
  local fn=resources/views/home.blade.php
  sed -e "s/PROJECT/$PROJ/g" vendor/hamatoma/laraknife/templates/home.templ >$fn
  echo "= home $fn has been created"
}
case $MODE in
build-links)
  BuildLinks $2
  ;;
init-i18n)
  InitI18N
  ;;
fill-db)
  FillDb
  ;;
adapt-modules)
  AdaptModules
  ;;
create-layout)
  CreateLayout
  ;;
create-home)
  CreateHome
  ;;
*)
  Usage "unknown TASK: $MODE"
  ;;
esac
