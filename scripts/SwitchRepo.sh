#! /bin/bash
MODE=$1
if [ "$MODE" != 'git' -a "$MODE" != 'local' ]; then
  echo "+++ unknown MODE, expecting 'git' or 'local'"
  echo "Example: SwitchRepo git"
fi
function Switch(){
  local trgRepo=vendor/hamatoma/laraknife
  local srcRepo=../laraknife 
  composer config --unset repositories.vendor/laraknife
  grep -i "repository" composer.json
  if [ "$MODE" = git ]; then
    composer config repositories.laraknife vcs https://github.com/hamatoma/laraknife
    if [ ! -d $repoTrg ]; then
       rm -Rf $repoTrg
       cp -a $srcRepo $trgRepo
    fi
  else
    if [ ! -L $repoTrg ]; then
       rm -f $repoTrg
       ln -s ../../$srcRepo $trgRepo
    fi
    composer config repositories.laraknife '{"type": "path", "url": "../laraknife", "options": {"symlink": true}}'
  fi
}
Switch

