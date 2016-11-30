# Sync keys

A little command line php app to sync public keys to a list of droplets (digital ocean) or by single ip.

Built with Slim framework and PSR-7 middleware
Requires PHP 5.6|7.0 or higher.

## Usage

- run composer install
- copy config.sample.php to config.php
- add your public keys to sync to the `authorized_keys` file
- make bin/console excutable
- run ./bin/console to get more help


## Planned features

- add config file for servers not in digtal ocean, with host and username
- add environments, so there will be different authorized_keys files for different environments, eg: production, development
- implementation for different cloud storage api's
- caching
