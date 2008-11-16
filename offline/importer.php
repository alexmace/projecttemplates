<?php

/*
 * This script should be run as a cron job, importing the contents of the 
 * fileQueue table in the database
 */
set_include_path( '../application/models' . PATH_SEPARATOR . get_include_path( ) );

require_once( 'Zend/Config/Ini.php' );
require_once( 'Zend/Db/Table.php' );
require_once( 'FileImports.php' );
require_once( 'FileQueue.php' );

$config = new Zend_Config_Ini( '../config/config.ini', 'general' );
$db = Zend_Db::factory( $config->db->adapter, $config->db->toArray( ) );
$db->query( "SET NAMES 'utf8'" );
Zend_Db_Table::setDefaultAdapter( $db );

// Get an instance of the queue
$fileQueue = new FileQueue( );

// Get an instance of the FileImports model to actually process the import
$fileImports = new FileImports( );

// Get the information required for the import loaded
$fileImports->prepare( );

// To stop the process overlapping, select one record and immediately update
// it's status to 'Processing', then import the file and update it again to 
// 'Imported' or 'Failed' depending on what happened.

// Use this to test if we are still importing files. Set to false when we run
// out
$importing = true;

// Set up the select query to use
$select = $fileQueue->select( )
                    ->where( "status = 'Queued'" );

while ( $importing )
{
    
    $row = $fileQueue->fetchRow( $select );
    
    if ( is_null( $row ) )
    {
        
        $importing = false;
        
    }
    else 
    {
        
        // Update the status of the row to show we are processing it
        $row->status = 'Processing';
        $row->save( );
        
        // Get the temporary directory for this file
        $tempDir = $fileImports->createTempDirectory( $row->fileImportId );
        
        // Import the file and get the report
        $report = $fileImports->import( array( $row->filePath ) );
        
        if ( $report[0]['result'] != 'success' )
        {
            
            $row->status = 'Failed';
            $row->message = $report[0]['message'];
            $row->save( );
            
        }
        else
        {
            
            $row->status = 'Imported';
            $row->save( );
            
        }
        
    }
    
}

?>