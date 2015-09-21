CREATE TABLE `population` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `location` varchar(150) NOT NULL,
 `slug` varchar(150) NOT NULL,
 `population` int(10) unsigned NOT NULL
 primary key (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
