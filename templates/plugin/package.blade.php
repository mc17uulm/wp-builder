{
    "name": "{{ $slug }}",
    "version": "0.1.0",
    "description": "{{ $description }}",
    "main": "index.js",
    "author": "{{ $author_name }} <{{ $author_email }}>",
    "license": "GPL-2.0",
    "scripts": {
        "tsc": "tsc",
        "start": "webpack --mode development --watch --progress",
        "build": "webpack --mode production",
        "php:analyse": "./vendor/bin/phpstan analyse --memory-limit=-1 -c phpstan.neon",
        "wp:lang:build": "cp ./languages/{{ $slug }}.pot ./languages/{{ $slug }}.backup.pot && wp i18n make-pot . ./languages/{{ $slug }}.pot --domain='{{ $slug }}' --debug --exclude='tests'",
        "wp:lang:json": "wp i18n make-json ./languages --no-purge"
    }
}