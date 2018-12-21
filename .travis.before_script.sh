#!/usr/bin/env bash
# OXID Installieren
cd ~/
composer create-project oxid-esales/oxideshop-project OXID dev-b-6.1-ce
cd OXID
sed -i -e "s@<dbHost>@127.0.0.1@g" source/config.inc.php
sed -i -e "s@<dbName>@oxid@g" source/config.inc.php
sed -i -e "s@<dbUser>@root@g" source/config.inc.php
sed -i -e "s@<dbPwd>@@g" source/config.inc.php
sed -i -e "s@<sShopURL>@http://127.0.0.1@g" source/config.inc.php
sed -i -e "s@<sShopDir>@/home/travis/OXID/source@g" source/config.inc.php
sed -i -e "s@<sCompileDir>@/home/travis/OXID/source/tmp@g" source/config.inc.php
sed -i -e "s@partial_module_paths: null@partial_module_paths: oxcom/moduleinstaller@g" test_config.yml
sed -i -e "s@run_tests_for_shop: true@run_tests_for_shop: false@g" test_config.yml

# Registrieren
composer config repositories.travis path ${TRAVIS_BUILD_DIR}
#if composer fails to install the following commands may help to get things running:
#composer config repo.packagist false & composer clear-cache
composer config minimum-stability dev

composer require "oxid-community/moduleinstaller:*"
