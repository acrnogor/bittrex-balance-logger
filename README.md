# bittrex-balance-logger
Simple client to download balance from Bittrex Exchange, calculate total BTC value, fetch current BTC value in USD, log everything and show a nice graph to track your deneros!
Written in Symfony 4.0 for fun :-)

## Installation
$ git clone git@github.com:acrnogor/bittrex-balance-logger.git bittrex-balance-logger
$ cd bittrex-balance-logger
$ composer install
$ bin/console doctrine:database:create
$ bin/console doctrine:schema:update --force

## Run the command
run the command with: $ bin/console bittrex:fetch
(or install as cron job and keep track every x hours/minutes)
