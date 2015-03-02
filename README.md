# angular-codeigniter-seed

## Features

AngularJS,
Bootstrap 3,
Lodash,
jQuery,
ngTable,
Moment.js,
JSON Web Tokens,
CDNJS,
CodeIgniter 2

## Requires

mcrypt

````
sudo apt-get install php5-mcrypt
sudo php5enmod mcrypt
sudo service apache2 restart
````

## DB Schema

````
CREATE TABLE IF NOT EXISTS `acl` (
  `key` varchar(64) NOT NULL,
  `value` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
````
