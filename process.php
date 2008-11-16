<?php

    set_include_path( '/Users/alex/Sites/zf/library' . PATH_SEPARATOR
                    . 'application/models' . PATH_SEPARATOR
                    . get_include_path( ) );

    /**
     * Ok, this is how this program should work:
     *
     * Loop through all the files in a particular directory, load all of the
     * payment information into the database.
     * "Payment Information" means the following
     *
     * Client Codes in use (Done)
     * Payments made on that client code (Done)
     * Reference Numbers of the payers (Done)
     * File that contains that payment (Done)
     * Any useful information - e.g. instances where the same reference appears
     * multiple times in a single file.
     *
     */

    require_once( 'Zend/Db.php' );
    require_once( 'Zend/Db/Table.php' );
    require_once( 'ClientCodes.php' );
    require_once( 'Files.php' );
    require_once( 'MultiplePayments.php' );
    require_once( 'Payers.php' );
    require_once( 'Payments.php' );
    require_once( 'PaymentTypes.php' );

    $db = Zend_Db::factory( 'PDO_MYSQL',
                       array( 'host'     => 'localhost',
                              'username' => 'auditing',
                              'password' => 'm4rtymcfly',
                              'dbname'   => 'audits' ) );
    Zend_Db_Table::setDefaultAdapter( $db );

    define( 'FILESDIR', 'files' );

    // Get client code information from the database
    $ccs = new ClientCodes( );
    $results = $ccs->fetchAll( );

    $clientCodes = array( );

    foreach ( $results as $row )
    {

        $clientCodes[$row->clientCode] = $row->clientCodeID;

    }

    $pts = new PaymentTypes( );
    $results = $pts->fetchAll( );

    // Get the payment information out
    $paymentTypes = array( );

    foreach ( $results as $row )
    {

        $paymentTypes[$row->identifier][$row->fileExtension] = $row->paymentTypeID;

    }

    $ps = new Payers( );
    $results = $ps->fetchAll( );

    // Get known reference numbers
    $payers = array( );

    foreach( $results as $row )
    {

        $payers[$row->reference] = $row->payerID;

    }

    if ( ( is_dir( FILESDIR ) ) &&
         ( is_readable( FILESDIR ) ) )
    {

        $dh = opendir( FILESDIR );

        $transactionCount = 0;
        $networkCount = array( 'P' => 0, // Post Office
                               'T' => 0, // PayPoint
                               'N' => 0, // Terminal
                               'Z' => 0, // PayZone
                               'D' => 0, // Direct Debit
                               'E' => 0, // EPay
                               'Q' => 0, // Cheque
                               'C' => 0  // Cash
                              );
        $largestPayment = 0;
        $smallestPayment = null;
        $revenue = 0;
        $amounts = array( );
        $transactions = array( );
        $clientCodeTransactions = array( );

        $fileContents = array( );

        $repeatedReferences = 0;

        $files = new Files( );
        $multiplePayments = new MultiplePayments( );
        $payments = new Payments( );

        while ( false !== ( $file = readdir( $dh ) ) )
        {

            $filePath = FILESDIR . DIRECTORY_SEPARATOR . $file;

            if ( is_file( $filePath ) )
            {

                $fh = fopen( $filePath, 'r' );

                $clientCode = strtoupper( substr( $file, 0, 4 ) );
                $fileNameParts = explode( '.', $file );
                $extension = strtoupper( $fileNameParts[count( $fileNameParts ) - 1] );

                if ( !isset( $clientCodes[$clientCode] ) )
                {

                    $ccData = array( 'clientCode' => $clientCode );
                    $clientCodes[$clientCode] = $ccs->insert( $ccData );

                }

                $fileData = array( 'fileName' => $file,
                                   'clientCodeID' => $clientCodes[$clientCode] );
                $fileID = $files->insert( $fileData );

                $references = array( );

                while ( false !== ( $data = fgets( $fh ) ) )
                {

                    $transaction = false;

                    if ( strlen( $data ) == 47 )
                    {

                        // echo $data;
                        // Statement Data

                    }
                    else if ( strlen( $data ) == 45 )
                    {

                        // Type A Transaction
                        $reference = substr( $data, 7, 16 );
                        $amount = substr( $data, 24, 8 );
                        $paymentType = substr( $data, 32, 1 );
                        $date = substr( $data, 33, 10 );
                        // echo $date . "\n";

                        $transaction = true;


                    }
                    else if ( strlen( $data ) == 57 )
                    {

                        // Type B Transaction
                        $pan = substr( $data, 0, 19 );
                        $reference = substr( $data, 19, 16 );
                        $amount = substr( $data, 36, 8 );
                        $paymentType = substr( $data, 44, 1 );
                        $date = substr( $data, 45, 10 );
                        //echo $date . "\n";

                        $transaction = true;

                    }
                    else
                    {

                       //echo strlen( $data ) . "\n";

                    }

                    if ( $transaction )
                    {

                        // Trim the data
                        $reference = trim( $reference );
                        $amount = trim( $amount );

                        if ( !isset( $payers[$reference] ) )
                        {

                            $payersData = array( 'reference' => $reference,
                                                 'clientCodeID' => $clientCodes[$clientCode] );
                            $payers[$reference] = $ps->insert( $payersData );

                        }

                        // Rearrange the date ready for the database
                        $dateParts = explode( '/', $date );
                        $date = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];

                        // Assign the reference to a two dimensional array where
                        // the first key is the reference number and the second
                        // key is the date. If this already exists, then insert
                        // a record into the database recording that the payer
                        // apparently made multiple payments on that date.
                        if ( isset( $references[$reference][$date] ) )
                        {

                            if ( $references[$reference][$date] == 1 )
                            {

                                $mpData = array( 'payerID' => $payers[$reference],
                                                 'paymentDate' => $date );
                                $multiplePayments->insert( $mpData );

                            }

                            $references[$reference][$date]++;

                        }
                        else
                        {

                            $references[$reference][$date] = 1;

                        }

                        $paymentData = array( 'clientCodeID' => $clientCodes[$clientCode],
                                              'reference' => $reference,
                                              'amount' => $amount,
                                              'paymentTypeID' => $paymentTypes[$paymentType][$extension],
                                              'paymentDate' => $date,
                                              'fileID' => $fileID,
                                              'payerID' => $payers[$reference] );
                        $payments->insert( $paymentData );

/*
                        $fileContents[] = array( 'reference' => $reference,
                                                 'date' => $date,
                                                 'amount' => $amount,
                                                 'type' => $paymentType );

                        if ( isset( $references[$reference] ) )
                        {

                            $references[$reference]++;
                            $repeatedReferences++;

                        }
                        else
                        {

                            $references[$reference] = 0;

                        }

                        $transactionCount++;

                        if ( array_key_exists( $paymentType, $networkCount ) )
                        {

                            $networkCount[$paymentType]++;

                        }
                        else
                        {

                            echo $file . ': ' . $paymentType . "\n";

                        }

                        if ( floatval( $amount ) > $largestPayment )
                        {

                            $largestPayment = floatval( $amount );

                        }

                        if ( ( is_null( $smallestPayment ) ) ||
                             ( floatval( $amount ) < $smallestPayment ) )
                        {

                            $smallestPayment = floatval( $amount );

                        }

                        if ( isset( $amounts[floatval( $amount )] ) )
                        {

                            $amounts[floatval( $amount)]++;

                        }
                        else
                        {

                            $amounts[floatval( $amount )] = 1;

                        }

                        $revenue += floatval( $amount );
                        $transactions[] = floatval( $amount );

                        if ( isset( $clientCodeTransactions[$clientCode] ) )
                        {

                            $clientCodeTransactions[$clientCode]++;

                        }
                        else
                        {

                            $clientCodeTransactions[$clientCode] = 0;

                        } */

                    }

                }

                fclose( $fh );

            }

        }

        closedir( $dh );
/*
        arsort( $amounts );
        $amountsKeys = array_keys( $amounts );

        sort( $transactions );
        $remainder = count( $transactions ) % 2;

        if ( $remainder == 0 )
        {

            $median = $transactions[count( $transactions ) / 2];

        }
        else
        {

            $lower = $transactions[ ( ( count( $transactions ) - 1 )/ 2 )];
            $higher = $transactions[ ( ( count( $transactions ) + 1 )/ 2 )];
            $median = ( $lower + $higher ) / 2;

        }

        arsort( $clientCodeTransactions );

        echo 'Transactions Found: ' . $transactionCount . "\n";
        echo 'Repeated References in Single File: ' . $repeatedReferences . "\n";
        echo 'Total Payments: ' . $revenue . "\n";
        echo 'Largest Payment: ' . $transactions[count( $transactions ) - 1] . "\n";
        echo 'Second Largest Payment: ' . $transactions[count( $transactions ) - 2] . "\n";
        echo 'Smallest Payment: ' . $transactions[0] . "\n";
        echo 'Second Smallest Payment: ' . $transactions[1] . "\n";
        echo 'Average Payment: ' . $revenue / $transactionCount . "\n";
        echo 'Median Payment: ' . $median . "\n";
        echo 'Most Frequent Payment: ' . $amountsKeys[0] . ' (' . $amounts[$amountsKeys[0]] . " transactions)\n";
        echo 'Second Most Frequency Payment: ' . $amountsKeys[1] . ' (' . $amounts[$amountsKeys[1]] . " transactions)\n";
        echo 'Transactions Per Client Code: ' . "\n";

        foreach ( $clientCodeTransactions as $clientCode => $transactions )
        {

            echo $clientCode . ': ' . $transactions . "\n";

        }
        var_dump( $networkCount );
*/
    }
/*
    var_export( $clientCodes );
    var_dump( $paymentTypes ); */
    var_export( $payers );

?>