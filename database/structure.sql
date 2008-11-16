CREATE TABLE `clientCodes` (
  `clientCodeID` mediumint(9) NOT NULL auto_increment,
  `clientCode` char(4) NOT NULL,
  PRIMARY KEY  (`clientCodeID`)
);

CREATE TABLE `fileImports` (
  `fileImportId` mediumint(9) NOT NULL auto_increment,
  `importKey` char(64) NOT NULL,
  `userId` mediumint(9) NOT NULL,
  `importTime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`fileImportId`)
) ENGINE=MEMORY;

CREATE TABLE `files` (
  `fileID` mediumint(9) NOT NULL auto_increment,
  `fileName` varchar(32) NOT NULL,
  `clientCodeID` mediumint(9) NOT NULL,
  `hash` char(40) NOT NULL,
  `imported` DATETIME NOT NULL,
  PRIMARY KEY  (`fileID`),
  UNIQUE( `hash` )
);

CREATE TABLE `fileQueue` (
  `fileQueueID` MEDIUMINT NOT NULL auto_increment,
  `fileImportId` MEDIUMINT NOT NULL,
  `filePath` VARCHAR( 128 ) NOT NULL,
  `status` ENUM( 'Queued', 'Processing', 'Imported', 'Failed' ) NOT NULL DEFAULT 'Queued',
  `message` VARCHAR( 64 ) NOT NULL DEFAULT '',
  PRIMARY KEY ( `fileQueueID` )
);

CREATE TABLE `multiplepayments` (
  `multiplePaymentID` mediumint(9) NOT NULL auto_increment,
  `payerID` mediumint(9) NOT NULL,
  `paymentDate` date NOT NULL,
  PRIMARY KEY  (`multiplePaymentID`),
  KEY `payer_paymentDate_idx` (`paymentDate`,`payerID`)
);

CREATE TABLE `payers` (
  `payerID` mediumint(9) NOT NULL auto_increment,
  `reference` varchar(16) NOT NULL,
  `clientCodeID` mediumint(9) NOT NULL,
  PRIMARY KEY  (`payerID`)
);

# A particular payment type could be identified in more than one ways. e.g.
# Post Office files can have the extension .PO and the identifier P, but some 
# Bristol Post Office files could have the extension .PO but the identifier A.
# Therefore we need a table of criteria and then a lookup table to tie the 
# criteria to the payment type.
CREATE TABLE `paymentTypes` (
  `paymentTypeID` mediumint(9) NOT NULL auto_increment,
  `description` varchar(32) NOT NULL,
  PRIMARY KEY  (`paymentTypeID`)
);

CREATE TABLE `paymentTypesCriteria` (
  `criteriaID` MEDIUMINT NOT NULL,
  `fileExtension` varchar(3) NOT NULL,
  `identifier` char(1) NOT NULL,
  PRIMARY KEY( `criteriaID` )
);

CREATE TABLE `paymentTypesCriteriaLookup` (
  `paymentTypeID` MEDIUMINT NOT NULL,
  `criteriaID` MEDIUMINT NOT NULL,
  PRIMARY KEY ( `paymentTypeID`, `criteriaID` )
);

CREATE TABLE `payments` (
  `paymentID` mediumint(9) NOT NULL auto_increment,
  `clientCodeID` mediumint(9) NOT NULL,
  `reference` varchar(16) NOT NULL,
  `amount` float(8,2) NOT NULL,
  `paymentTypeID` mediumint(9) NOT NULL,
  `paymentDate` date NOT NULL,
  `fileID` mediumint(9) NOT NULL,
  `payerID` mediumint(9) NOT NULL,
  PRIMARY KEY  (`paymentID`),
  KEY `payer_paymentDate_idx` (`paymentDate`,`payerID`)
);

CREATE TABLE `users` (
  `userid` mediumint(9) NOT NULL auto_increment,
  `email` varchar(128) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `surname` varchar(32) NOT NULL,
  `role` enum('client','consultant','admin') NOT NULL default 'client',
  PRIMARY KEY  (`userid`),
  UNIQUE KEY `email` (`email`)
);