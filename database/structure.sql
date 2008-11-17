CREATE TABLE `users` (
  `userid` mediumint(9) NOT NULL auto_increment,
  `email` varchar(128) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `surname` varchar(32) NOT NULL,
  PRIMARY KEY  (`userid`),
  UNIQUE KEY `email` (`email`)
);
