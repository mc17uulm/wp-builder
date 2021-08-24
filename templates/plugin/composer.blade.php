{
    "name": "{{ $author_slug }}/{{ $slug }}",
    "description": "{{ $description }}",
    "type": "library",
    "authors": [
        {
            "name": "{{ $author_name }}",
            "email": "{{ $author_email }}"
        }
    ],
    "require": {
        "php": ">={{ $php_version }}",
        "ext-json": "*"
        @if ($api)
        ,
        "opis/json-schema": "^1.0"
        @endif
    },
    "require-dev": {
        "phpstan/phpstan": "^0.12.82",
        "szepeviktor/phpstan-wordpress": "^0.7.5",
        "phpunit/phpunit": "^9.5"
    },
    "autoload": {
        "psr-4": {
            "{{ $namespace }}\\": "./plugin/{{ $namespace }}"
        }
    }
}