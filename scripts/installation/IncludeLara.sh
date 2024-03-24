#! /bin/bash
BASE=$1
MODE=$2
function Usage(){
echo "Usage: IncludeLara.sh BASE MODE"
  echo "Example: IncludeLara.sh /home/ws/php/taskx" git 
  echo "Example: IncludeLara.sh /home/ws/php/taskx" local
  echo "+++ $*"
}
if [ "$MODE" != git -a "$MODE" != "local" ]; then
  Usage "Invalid MODE. Use 'git' or 'local'"
elif [ ! -f $BASE/project.env ]; then
  Usage "Invalid BASE: $BASE/project.env not found"
else
  cd $BASE
  . project.env
  if [ "$MODE" = git ]; then
    composer config repositories.laraknife vcs https://github.com/hamatoma/laraknife
  else
    composer config repositories.laraknife '{"type": "path", "url": "../laraknife", "options": {"symlink": true}}'
  fi
  # Branch main:
  composer require hamatoma/laraknife:dev-main
  composer update
  vendor/hamatoma/laraknife/scripts/laraknife-tool.sh build-links
  echo "= run: cd $BASE"
  echo "= run: larascripts/laraknife-tool.sh rest"
fi
