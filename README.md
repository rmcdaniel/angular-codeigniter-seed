# angular-codeigniter-seed

## Requires

mcrypt

````
sudo apt-get install php5-mcrypt
sudo php5enmod mcrypt
sudo service apache2 restart
````

## DB Schema

````
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
````
