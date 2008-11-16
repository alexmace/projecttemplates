CREATE TABLE `paymentTypes` (
  `paymentTypeID` mediumint(9) NOT NULL auto_increment,
  `description` varchar(32) NOT NULL,
  PRIMARY KEY  (`paymentTypeID`)
);

CREATE TABLE `paymentTypesCriteria` (
  `criteriaID` MEDIUMINT NOT NULL auto_increment,
  `fileExtension` varchar(3) NOT NULL,
  `identifier` char(1) NOT NULL,
  PRIMARY KEY( `criteriaID` )
);

CREATE TABLE `paymentTypesCriteriaLookup` (
  `paymentTypeID` MEDIUMINT NOT NULL,
  `criteriaID` MEDIUMINT NOT NULL,
  PRIMARY KEY ( `paymentTypeID`, `criteriaID` )
);

INSERT INTO paymentTypes ( description ) VALUES
( 'Post Office' ),
( 'PayPoint' ),
( 'Terminal Debit Card' ),
( 'Terminal Credit Card' ),
( 'Terminal Cash' ),
( 'PayZone' ),
( 'Direct Debit' ),
( 'EPay' ),
( 'Cheque' ),
( 'Cash' ),
( 'Woolworths' ),
( 'AnPost' );

INSERT INTO paymentTypesCriteria ( fileExtension, identifier ) VALUES
( 'PO', 'P' ),
( 'PO', 'A' ),
( 'PP', 'T' ),
( 'TDC', 'N' ),
( 'TCC', 'N' ),
( 'TC', 'N' ),
( 'PZ', 'Z' ),
( 'DD', 'D' ),
( 'EPY', 'E' ),
( 'CQE', 'Q' ),
( 'CSH', 'C' ),
( 'WO', 'W' ),
( 'AN', 'A' );

INSERT INTO paymentTypesCriteriaLookup VALUES
( 1, 1 ),   # Post Office, PO, P
( 1, 2 ),   # Post Office, PO, A
( 2, 3 ),   # PayPoint, PP, T
( 3, 4 ),   # Terminal Debit Card, TDC, N
( 4, 5 ),   # Terminal Credit Card, TCC, N
( 5, 6 ),   # Terminal Cash, TC, N
( 6, 7 ),   # PayZone, PZ, Z
( 7, 8 ),   # Direct Debit, DD, D
( 8, 9 ),   # EPay, EPY, E
( 9, 10 ),  # Cheque, CQE, Q
( 10, 11 ), # Cash, CSH, C
( 11, 12 ), # Woolworths, WO, W
( 12, 13 ); # AnPost, AN, A

