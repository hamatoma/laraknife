#! /bin/bash

MODE=$1
CURDB=$(pwd)
PROJ=$2

function Usage(){
  echo "Usage: laraknife-tool.sh install PROJ"
  echo "  PROJ: name of the webapp base directory (without path)"
  echo "Usage: laraknife-tool.sh build-links }"
  echo "  run it from the directory PROJ/tools"
  echo "+++ $*"
}
function Install(){
  local node=$(basename $CURDB)
  local parent=$(dirname $CURDB)
  local nodeParent=$(basename $parent)
  local baseProject=$(dirname $parent)/$PROJ
  if [ "$node" != scripts -o "$nodeParent" != laraknife ]; then
    Usage "wrong current directory: $CURDB must be in the applications base directory parallel to laraknife"
  elif [ -z "$PROJ" ]; then
    Usage "missing PROJ"
  elif [ ! -d $baseProject ]; then
    Usage "missing $baseProject"
  else
    cd $baseProject
    mkdir -p tools
    cd tools
    local fn=laraknife-tool.sh
    test -L $fn && rm -f $fn
    ln -sv ../../laraknife/scripts/$fn .
    echo "PROJ=$PROJ" >project.sh
    chmod +x project.sh
  fi
}
function Link(){
  local node=$(basename $CURDB)
  local parent=$(dirname $CURDB)
  local nodeParent=$(basename $parent)
  if [ "$node" != tools ]; then
    Usage "wrong current directory: use PROJ/tools"
  elif [ ! -x project.sh ]; then
    Usage "missing project.sh"
  else
    . project.sh
    echo "project: $PROJ"
    cd ..
    for module in SProperty; do
      ln -sv ../../../laraknife/src/Models/${module}.php app/Models/
      ln -sv ../../../../laraknife/src/Http/Controllers/${module}Controller.php app/Http/Controllers/
    done
    for module in laraknife sproperty; do
      ln -sv ../../../laraknife/src/resources/views/$module/ resources/views/
    done
    for file in ../laraknife/src/database/migrations/*.php; do
      local node=$(basename $file)
      echo "F: $file N: $node"
      ln -sv ../../../laraknife/src/database/migrations/$node database/migrations/$node
    done
    ln -sv ../../../../laraknife/src/resources/components/laraknife resources/views/components/
  fi
}
case $MODE in
install)
  Install
  ;;
build-links)
  Link
  ;;
*)
  Usage "unknown MODE: $MODE"
  ;;
esac
