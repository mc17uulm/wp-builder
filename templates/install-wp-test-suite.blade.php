#!/usr/bin/env bash

WP_VERSION=latest
WP_TESTS_DIR="{{ $dir }}"
WP_DIR_SEPARATOR="{{ $separator }}"
WP_CORE_DIR=/var/www/html

echo "running"

download() {
    if [ `which curl` ]; then
        curl -s "$1" > "$2";
    elif [ `which wget` ]; then
        wget -nv -O "$2" "$1"
    fi
}

if [[ $WP_VERSION =~ ^[0-9]+\.[0-9]+\-(beta|RC)[0-9]+$ ]]; then
    WP_BRANCH=${WP_VERSION%\-*}
    WP_TESTS_TAG="branches/$WP_BRANCH"
elif [[ $WP_VERSION =~ ^[0-9]+\.[0-9]+$ ]]; then
    WP_TESTS_TAG="branches/$WP_VERSION"
elif [[ $WP_VERSION =~ [0-9]+\.[0-9]+\.[0-9]+ ]]; then
    if [[ $WP_VERSION =~ [0-9]+\.[0-9]+\.[0] ]]; then
        # version x.x.0 means the first release of the major version, so strip off the .0 and download version x.x
        WP_TESTS_TAG="tags/${WP_VERSION%??}"
    else
        WP_TESTS_TAG="tags/$WP_VERSION"
    fi
elif [[ $WP_VERSION == 'nightly' || $WP_VERSION == 'trunk' ]]; then
    WP_TESTS_TAG="trunk"
else
    # http serves a single offer, whereas https serves multiple. we only want one
    download http://api.wordpress.org/core/version-check/1.7/ /tmp/wp-latest.json
    grep '[0-9]+\.[0-9]+(\.[0-9]+)?' /tmp/wp-latest.json
    LATEST_VERSION=$(grep -o '"version":"[^"]*' /tmp/wp-latest.json | sed 's/"version":"//')
    if [[ -z "$LATEST_VERSION" ]]; then
        echo "Latest WordPress version could not be found"
        exit 1
    fi
    WP_TESTS_TAG="tags/$LATEST_VERSION"
fi
set -ex

install_test_suite() {

    echo "install test suite"
    # portable in-place argument for both GNU sed and Mac OSX sed
    if [[ $(uname -s) == 'Darwin' ]]; then
        local ioption='-i.bak'
    else
        local ioption='-i'
    fi

    # set up testing suite if it doesn't yet exist
    if [ ! -d "$WP_TESTS_DIR" ]; then
        echo "svn"
        # set up testing suite
        mkdir -p $WP_TESTS_DIR
        rm -rf $WP_TESTS_DIR$WP_DIR_SEPARATOR{includes,data}
        svn export --quiet --ignore-externals https://develop.svn.wordpress.org/${WP_TESTS_TAG}/tests/phpunit/includes/ "$WP_TESTS_DIR$WP_DIR_SEPARATOR"includes
        svn export --quiet --ignore-externals https://develop.svn.wordpress.org/${WP_TESTS_TAG}/tests/phpunit/data/ "$WP_TESTS_DIR$WP_DIR_SEPARATOR"data
    else
        echo "no svn"
    fi

    if [ ! -f wp-tests-config.php ]; then
        download https://develop.svn.wordpress.org/${WP_TESTS_TAG}/wp-tests-config-sample.php "$WP_TESTS_DIR$WP_DIR_SEPARATOR"wp-tests-config.php
        # remove all forward slashes in the end
        WP_CORE_DIR=$(echo $WP_CORE_DIR | sed "s:/\+$::")
        sed $ioption "s:dirname( __FILE__ ) . '/src/':'$WP_CORE_DIR/':" "$WP_TESTS_DIR$WP_DIR_SEPARATOR"wp-tests-config.php
        sed $ioption "s:__DIR__ . '/src/':'$WP_CORE_DIR/':" "$WP_TESTS_DIR$WP_DIR_SEPARATOR"wp-tests-config.php
        sed $ioption "s/youremptytestdbnamehere/{{ $mysql_database }}/" "$WP_TESTS_DIR$WP_DIR_SEPARATOR"wp-tests-config.php
        sed $ioption "s/yourusernamehere/{{ $mysql_user }}/" "$WP_TESTS_DIR$WP_DIR_SEPARATOR"wp-tests-config.php
        sed $ioption "s/yourpasswordhere/{{ $mysql_password }}/" "$WP_TESTS_DIR$WP_DIR_SEPARATOR"wp-tests-config.php
        sed $ioption "s|localhost|db:3306|" "$WP_TESTS_DIR$WP_DIR_SEPARATOR"wp-tests-config.php
    fi

}

install_test_suite