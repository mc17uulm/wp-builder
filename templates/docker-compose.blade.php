version: "3.3"

services:

    db:
        image: mysql:5.7
        volumes:
            - db_data:/var/lib/mysql
        environment:
            MYSQL_DATABASE: {{ $mysql_database }}
            MYSQL_USER: {{ $mysql_user }}
            MYSQL_PASSWORD: {{ $mysql_password }}
            MYSQL_ROOT_PASSWORD: {{ $mysql_root_password }}
        networks:
            - intranet

    wordpress:
        depends_on:
            - db
        container_name: wordpress_test
        build: ./.dev/docker/
        restart: always
        stdin_open: true
        volumes:
            - ./:/var/www/html/wp-content/plugins/{{ $slug }}
        tty: true
        environment:
            WORDPRESS_DB_HOST: db:3306
            WORDPRESS_DB_USER: {{ $mysql_user }}
            WORDPRESS_DB_PASSWORD: {{ $mysql_password }}
            WORDPRESS_DB_NAME: {{ $mysql_database }}
        networks:
        - intranet


volumes:
    db_data: {}
networks:
    intranet:
