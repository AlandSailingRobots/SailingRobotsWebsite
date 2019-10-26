#!/usr/bin/env bash
function start_everything() {
  sudo httpd -k start
  mysql.server start
}
function stop_everything() {
  sudo httpd -k stop
  mysql.server stop
}
pause() {
  sleep 1
  read -n 1 -p "Press any key to stop..."
}

start_everything
pause
stop_everything
