#!/bin/bash
### create file ~/.netrc
#machine gitlab.com
#       login mylogin
#       password mypassword
### git config
#git config --global user.email "name@server"
#git config --global user.name "name"

git status
echo ---------------------------------------------------------------------------
read -r -p "git add . > Run? [Y/n]" yesno
yesno=${yesno,,}
if [[ $yesno =~ ^(yes|y| ) ]] | [ -z $yesno ]; then
  git add .
  echo ---------------------------------------------------------------------------
  git status
  echo ---------------------------------------------------------------------------
  echo Введите текст сообщения
  read msg
  read -r -p "git commit -m $msg > Run? [Y/n]" yesno
  yesno=${yesno,,}
  if [[ $yesno =~ ^(yes|y| ) ]] | [ -z $yesno ]; then
    git commit -m "$msg"
    echo ---------------------------------------------------------------------------
    git status
    echo ---------------------------------------------------------------------------
    read -r -p "git push > Run? [Y/n]" yesno
    yesno=${yesno,,}
    if [[ $yesno =~ ^(yes|y| ) ]] | [ -z $yesno ]; then
      git push
    fi
  fi
fi
