[![Build Status](https://travis-ci.org/rmcdaniel/angular-codeigniter-seed.svg?branch=master)](https://travis-ci.org/rmcdaniel/angular-codeigniter-seed) [![Coverage Status](https://coveralls.io/repos/rmcdaniel/angular-codeigniter-seed/badge.svg)](https://coveralls.io/r/rmcdaniel/angular-codeigniter-seed)
# angular-codeigniter-seed

## Features

- Single-page application (AngularJS)
- Responsive (Bootstrap 3)
- Multi-language
- User manager
- Role-based ACL

## Demo

https://bitlab.co/acs

User: foo@bar.com  
Pass: password123

The demo resets every hour.

## Requires

mcrypt

````
sudo apt-get install php5-mcrypt
sudo php5enmod mcrypt
sudo service apache2 restart
````

## Installation

````
php api/index.php cli install
php api/index.php cli add administrator foo@bar.com password123
````
