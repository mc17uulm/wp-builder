<?xml version="1.0"?>
<phpunit
        bootstrap="tests/bootstrap.php"
        backupGlobals="false"
        colors="true"
        convertErrorsToExceptions="true"
        convertNoticesToExceptions="true"
        convertWarningsToExceptions="true"
>
    <testsuites>
        <testsuite name='{{ $slug }}'>
            <directory prefix="test-" suffix=".php">./tests/</directory>
            <exclude>./tests/test-sample.php</exclude>
        </testsuite>
    </testsuites>
</phpunit>