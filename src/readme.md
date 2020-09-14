# For Running,
php -S localhost:8000 -t public

# install docker

https://docs.gitlab.com/ee/ci/examples/laravel_with_gitlab_and_envoy/

https://gitlab.com/herzcthu/lumen-docker/tree/master

https://ohdear.app/blog/our-gitlab-ci-pipeline-for-laravel-applications#how-to-use-our-gitlab-ci

# better code
Installing the Code Quality Tools

https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md

> using PHP_CodeSniffer requires PHP
> add phpcs.xml for execute php_codesniffer

<?xml version="1.0"?>
<ruleset name="PHP_CodeSniffer">
    <description>The coding standard for gandagang-lumen project.</description>
    <rule ref="PSR2"/>

    <file>app</file>
    <file>bootstrap</file>
    <file>config</file>
    <file>resources</file>
    <file>routes</file>
    <file>tests</file>

    <exclude-pattern>bootstrap/cache/*</exclude-pattern>
    <exclude-pattern>tests/TestCase.php</exclude-pattern>
    <exclude-pattern>*/migrations/*</exclude-pattern>
    <exclude-pattern>*/seeds/*</exclude-pattern>
    <exclude-pattern>*.blade.php</exclude-pattern>
    <exclude-pattern>*.js</exclude-pattern>

    <!-- Show progression -->
    <arg value="p"/>
</ruleset>

# standard code
# Download using curl
curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar
curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar


# install phpc
sudo apt install php-codesniffer

# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/lumen-framework/v/unstable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


setup redis password
https://medium.com/@umutuluer/resolving-the-err-client-sent-auth-but-no-password-is-set-error-b81438d10843

https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-redis-on-ubuntu-18-04

sudo nano /etc/redis/redis.conf
# requirepass r46bGYWPtIqIXm6X (yourpassword)

# api.gandagang-default.conf local
server {
    listen      8080 default_server;
    server_name localhost;

    root /var/www/html/gandagang-lumen/src/public;
    index index.php index.html index.htm;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # pass to php-fpm
    location ~ \.php(?:$|/) {
      fastcgi_split_path_info ^(.+\.php)(/.+)$;
      fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
      fastcgi_pass unix:/var/run/php/php7.3-fpm.sock;
      fastcgi_index index.php;
      fastcgi_intercept_errors on;
      include fastcgi_params;
    }


    location ~* \.(?:jpg|jpeg|gif|png|ico|cur|gz|svg|svgz|mp4|ogg|ogv|webm|htc|svg|woff|woff2|ttf)\$ {
      expires 1M;
      access_log off;
      add_header Cache-Control "public";
    }

    location ~* \.(?:css|js)\$ {
      expires 7d;
      access_log off;
      add_header Cache-Control "public";
    }
    
    location ~ /\.ht {
        deny  all;
    }
    
    charset utf-8;

    access_log off;
    sendfile off;

    client_max_body_size 100m;  

    location ~ /\.ht {
        deny all;
    }


    #error_page  404              /404.html;

    # redirect server error pages to the static page /50x.html
    #
    error_page   500 502 503 504  /50x.html;
    location = /50x.html {
        root   html;
    }
}

