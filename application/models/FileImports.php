<?php

require_once( 'ClientCodes.php' );
require_once( 'Files.php' );
require_once( 'Payers.php' );
require_once( 'Payments.php' );
require_once( 'PaymentTypes.php' );

/**
 * Model for managing file imports.
 *
 */
class FileImports extends Zend_Db_Table_Abstract
{

    protected $_name = 'fileImports';

    protected $_clientCodes = array( );

    protected $_paymentTypes = array( );

    protected $_payers = array( );

    /**
     * Generates an import key for the given userID
     *
     * @param int $userId
     * @return string
     */
    public function generateImportKey( $userId )
    {

        // Create a hash from the userId and a unique ID. Should be unique,
        // unless the user somehow managages to be running this twice at the
        // same time...
        $key = hash( 'SHA256', uniqid( ) . $userId );

        // Data insert into the table
        $data = array(
            'importKey' => $key,
            'userId' => $userId
        );

        // Insert the data
        $this->insert( $data );

        return $key;

    }

    /**
     * Checks the the import key is valid by seeing if it is in the database.
     * The userId is optional because when the file is uploaded using
     * FancyUpload we don't have any session information, so we just want to
     * know if the key exists at all.
     *
     * @param string $importKey
     * @param int $userId
     * @return boolean
     */
    public function importKeyExists( $importKey, $userId = null )
    {

        // Create query for the database
        $select = $this->select( )->where( 'importKey = ?', $importKey );

        if ( null !== $userId )
        {

            $select ->where( 'userId = ?', $userId );

        }

        // Run it
        $row = $this->fetchRow( $select );

        $valid = false;

        // $row will be null if nothing is found. Therefore if this is the case
        // we know that the key is not valid.
        if ( !is_null( $row ) )
        {

            $valid = true;

        }

        return $valid;

    }

    /**
     * Makes sure that all of the required data is loaded into the system for a
     * file import, like client codes, payment types, etc.
     *
     */
    public function prepare( )
    {

        // Load all of the client codes currently in the system
        $ccs = new ClientCodes( );
        $results = $ccs->fetchAll( );

        $this->_clientCodes = array( );

        foreach( $results as $row )
        {

            $this->_clientCodes[$row->clientCode] = $row->clientCodeID;

        }

        // Load all of the different payment types
        $pts = new PaymentTypes( );
        $results = $pts->getPaymentTypeIdentifers( );

        $this->_paymentTypes = array( );

        foreach( $results as $row )
        {

            $this->_paymentTypes[$row->identifier][$row->fileExtension] = $row->paymentTypeID;

        }

        // Load all of the existing payers
        $ps = new Payers( );
        $results = $ps->fetchAll( );

        $payers = array( );

        foreach( $results as $row )
        {

            $this->_payers[$row->reference] = $row->payerID;

        }

    }

    /**
     * Import the given array of files into the database.
     *
     * @param array $filesToImport
     */
    public function import( $filesToImport )
    {

        $ccs = new ClientCodes( );
        $files = new Files( );
        $ps = new Payers( );
        $payments = new Payments( );
        
        $fileReport = array( );

        // Process each value in the array
        foreach( $filesToImport as $file )
        {
            
            $result = 'success';
            $message = '';

            // Check it is a file, it is readable and that we can open it.
            if ( ( is_file( $file ) ) &&
                 ( is_readable( $file ) ) &&
                 ( false !== ( $fh = fopen( $file, 'r' ) ) ) )
            {
                
                // Test to see if this file is already in the system. If it is, 
                // then we cannot import this file
                if ( !$files->checkIfFilePresent( $file ) )
                {

                    // Get the details of the filename
                    $fileName = basename( $file );
                    $clientCode = strtoupper( substr( $fileName, 0, 4 ) );
                    $fileNameParts = explode( '.', $fileName );
                    $extension = strtoupper( $fileNameParts[ count( $fileNameParts ) - 1] );
        
                    // If we don't know about this client code, insert it into the
                    // database.
                    if ( !isset( $this->_clientCodes[$clientCode] ) )
                    {
        
                        $ccData = array( 'clientCode' => $clientCode );
                        $this->_clientCodes[$clientCode] = $ccs->insert( $ccData );
        
                    }
        
                    $fileData = array(
                        'fileName' => $fileName,
                        'clientCodeID' => $this->_clientCodes[$clientCode],
                        'hash' => sha1_file( $file ),
                        'imported' => new Zend_Db_Expr( 'NOW( )' )
                    );
                    $fileID = $files->insert( $fileData );
        
                    $references = array( );
                    $footerTotal = -1;
                    $footerCount = -1;
                    $runningTotal = 0;
                    $runningCount = 0;
        
                    while ( false !== ( $data = fgets( $fh ) ) )
                    {
        
                        $transaction = false;
        
                        if ( strlen( $data ) == 47 )
                        {
        
                            // Statement Data
        
                        }
                        else if ( ( strlen( $data ) == 45 ) ||
                                  ( strlen( $data ) == 64 ) ||
                                  ( strlen( $data ) == 226 ) )
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
                        else if ( strlen( $data ) == 53 )
                        {
        
                            // Transaction total Line
                                                        
                            // Find the position of the colon.
                            $colonPos = strpos( $data, ':' );
                            
                            if ( $colonPos !== false )
                            {
                            
                                $footerTotal = floatval( substr( $data, $colonPos + 1 ) );
                                
                            }
        
                        }
                        else if ( strlen( $data ) == 59 )
                        {
                            
                            // Transaction count line
                            
                            // Find the position of the colon
                            $colonPos = strpos( $data, ':' );
                            
                            if ( $colonPos !== false )
                            {
                                
                                $footerCount = trim( substr( $data, $colonPos + 1 ) );
                                
                            }
                            
                        }
        
                        if ( $transaction )
                        {
        
                            // Trim the data
                            $reference = trim( $reference );
                            $amount = trim( $amount );
                            
                            $runningCount++;
                            $runningTotal += $amount;
        
                            if ( !isset( $this->_payers[$reference] ) )
                            {
        
                                $payersData = array( 'reference' => $reference,
                                                     'clientCodeID' => $this->_clientCodes[$clientCode] );
                                $this->_payers[$reference] = $ps->insert( $payersData );
        
                            }
        
                            // Rearrange the date ready for the database
                            $dateParts = explode( '/', $date );
                            $date = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
        /*
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
        */
                            $paymentData = array( 'clientCodeID' => $this->_clientCodes[$clientCode],
                                                  'reference' => $reference,
                                                  'amount' => $amount,
                                                  'paymentTypeID' => $this->_paymentTypes[$paymentType][$extension],
                                                  'paymentDate' => $date,
                                                  'fileID' => $fileID,
                                                  'payerID' => $this->_payers[$reference] );
                            $payments->insert( $paymentData );
        
                        }
        
                    }
                    
                    // Check the running totals match the totals taken from the
                    // footer
                    if ( ( ( $footerCount != -1 ) &&
                           ( $footerCount != $runningCount ) ) ||
                         ( ( $footerTotal != -1 ) &&
                           ( round( $footerTotal, 2 ) != round( $runningTotal, 2 ) ) ) )
                    {
                        
                        $where = $files->getAdapter()->quoteInto( 'fileID = ?', $fileID );
                        
                        // Delete the file and its payments from the database.
                        $files->delete( $where );
                        $payments->delete( $where );
                        
                        $result = 'failed';
                        $message = 'Some of the transactions in this file were '
                                 . 'not imported, so the whole file has been '
                                 . 'rejected.';
                        
                    }
                    
                }
                else 
                {
                    
                    $result = 'failed';
                    $message = 'This file has been previously imported.';
                    
                }
                


                // Close the file when we are done with it.
                fclose( $fh );

            }
            else 
            {
                
                $result = 'failed';
                $message = 'The file could not be accessed';
                
            }
            
            $fileReport[] = array( 'file' => $file,
                                   'message' => $message,
                                   'result' => $result );

        }
        
        return $fileReport;

    }
    
    /**
     * If a directory does not already exist in the systems temp folder for 
     * this file import, this function will create one and return the path
     * of that directory. If it does already exist, then it will just create the
     * path name of it
     *
     */
    public function createTempDirectory( $fileImportId )
    {

        $tempDirectory = '';
        
        // Get the import key
        $fileImports = new FileImports( );
        $select = $this->select( )
                       ->where( 'fileImportId = ?', $fileImportId );
        $row = $fileImports->fetchRow( $select );

        // If the row isn't null then we got a result
        if ( !is_null( $row ) )
        {
        
            // Assemble the path name
            $tempDirectory = sys_get_temp_dir( ) . DIRECTORY_SEPARATOR 
                           . $row->importKey . DIRECTORY_SEPARATOR;
                           
            // Check to see if the directory already exists. If it doesn't, 
            // create it.
            if ( !file_exists( $tempDirectory ) )
            {
                
                mkdir( $tempDirectory );
                
            }
                           
        }
        
        return $tempDirectory;
        
    }
    
    public function getProcessing( )
    {
        
    }
    
    public function getCompleted( )
    {

        // We need this sub query to check if the fileImportId has any files
        // in the queue that are still queued or processing. If there are then
        // the import is not yet completed.
        $subSelect = $this->select( )->setIntegrityCheck( false );
        $subSelect->from( array( 'fq' => 'fileQueue' ),
                                  array( 'fileImportId' ) )
                          ->where( "status='Queued' OR status='Processing'" );
        $uncompleted = $this->fetchAll( $subSelect )->toArray( );
        
        // Turn off the integrity check because we want to perform a join with
        // some other tables. Then we want to get the details of the completed
        // imports
        $select = $this->select( )->setIntegrityCheck( false );
        $select->from( array( 'fi' => 'fileImports' ),
                       array( 'importTime' => 'TIME( importTime )',
                              'importDate' => 'DATE( importTime )' ) )
               ->join( array( 'u' => 'users' ),
                       'fi.userId=u.userId',
                       array( 'firstname' ) )
               ->join( array( 'fq' => 'fileQueue' ),
                       'fi.fileImportId=fq.fileImportId',
                       array( 'files' => 'COUNT( fileQueueId )' ) )
               ->where( "fi.fileImportId NOT IN ( ? )", $uncompleted ) //new Zend_Db_Expr( $subSelect ) )
               ->group( 'fq.fileImportId' )
               ->order( array( 'importDate DESC', 'importTime DESC' ) );
        $results = $this->fetchAll( $select );
        
        return $results->toArray( );
        
        // SELECT TIME( importTime ) AS importTime, DATE( importTime ) AS importDate, firstname, COUNT( fileQueueId ) AS files 
        // FROM fileImports, fileQueue, users 
        // WHERE fileImports.userId=users.userId 
        // AND fileImports.fileImportId=fileQueue.fileImportId 
        // GROUP BY fileQueue.fileImportId
        
    }

}