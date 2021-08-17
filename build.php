<?php

try {

    $file = "wp-builder.phar";

    if(file_exists($file)) {
        unlink($file);
    }

    if(file_exists($file . '.gz')) {
        unlink($file . '.gz');
    }

    if(file_exists('build')) {
        shell_exec("rm -rf build 2>&1");
    }

    mkdir('build');

    echo shell_exec("cp -r src build/ 2>&1");
    echo shell_exec("cp -r templates build/ 2>&1");
    echo shell_exec("cp composer.json build/ 2>&1");
    echo shell_exec("cp composer.lock build/ 2>&1");
    echo shell_exec("cp index.php build/ 2>&1");
    echo shell_exec("cp wp-builder.schema.json build/ 2>&1");
    echo shell_exec("cd build && composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader --prefer-dist --no-suggest 2>&1");

    $phar = new Phar($file);

    $phar->startBuffering();

    $defaultStub = $phar->createDefaultStub('index.php');

    $phar->buildFromDirectory(__DIR__ . '/build/');

    $stub = "#!/usr/bin/env php\n" . $defaultStub;

    $phar->setStub($stub);
    $phar->stopBuffering();
    $phar->compressFiles(Phar::GZ);

    chmod(__DIR__ . "/$file", 0770);

    shell_exec("rm -rf build");

    echo "$file successfully created" . PHP_EOL;

} catch(Exception $e) {
    echo $e->getMessage();
}