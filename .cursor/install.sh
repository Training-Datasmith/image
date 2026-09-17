#!/usr/bin/env bash
# Cloud agent: GD + Imagick + EXIF for intervention/image PHPUnit (phpunit.dist.xml).
set -euo pipefail

repo_root="$(cd "$(dirname "$0")/.." && pwd)"
php_ver="$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')"

need_pkgs=()
php -m 2>/dev/null | grep -q '^gd$' || need_pkgs+=("php${php_ver}-gd")
php -m 2>/dev/null | grep -q '^imagick$' || need_pkgs+=("php${php_ver}-imagick" "imagemagick")
php -m 2>/dev/null | grep -q '^exif$' || need_pkgs+=("php${php_ver}-exif")
php -m 2>/dev/null | grep -q '^mbstring$' || need_pkgs+=("php${php_ver}-mbstring")
php -m 2>/dev/null | grep -q '^xml$' || need_pkgs+=("php${php_ver}-xml")

if ((${#need_pkgs[@]} > 0)); then
  export DEBIAN_FRONTEND=noninteractive
  sudo apt-get update -qq
  if ! apt-cache show "php${php_ver}-imagick" &>/dev/null 2>&1; then
    sudo apt-get install -y --no-install-recommends software-properties-common ca-certificates gnupg
    sudo add-apt-repository -y ppa:ondrej/php
    sudo apt-get update -qq
  fi
  sudo apt-get install -y --no-install-recommends "${need_pkgs[@]}"
fi

php -m | grep -q '^gd$'
php -m | grep -q '^imagick$'
php -m | grep -q '^exif$'

cd "$repo_root"
if [[ -f composer.json ]]; then
  composer install --no-interaction
fi
