export XDEBUG_CONFIG="remote_connect_back=0 idekey=intellij remote_host=10.0.2.2"
export PHP_IDE_CONFIG="serverName=manager.dev"
bin/phpunit --stderr --filter=$1
