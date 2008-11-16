<?php

require_once( 'PHPUnit/Extensions/Database/TestCase.php' );
require_once( 'Zend/Config/Ini.php' );
require_once( 'Zend/Db/Table.php' );
require_once( 'Zend/Db/Table/Abstract.php' );
require_once( 'models/Files.php' );
require_once( 'models/FileImports.php' );
require_once( 'models/Payers.php' );
require_once( 'models/PaymentTypes.php' );

class FileImportsTest extends PHPUnit_Extensions_Database_TestCase
{

    /**
     * Gets the connection for the test.
     *
     * @todo Populate the connection settings from the project configuration
     *       file
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection( )
    {

        $pdo = new PDO( 'mysql:host=localhost;dbname=audittest',
                        'testuser', 'testing' );
        return $this->createDefaultDBConnection( $pdo, 'audittest' );

    }

    protected function getDataSet( )
    {

        return $this->createFlatXMLDataSet( dirname( __FILE__ )
                                          . DIRECTORY_SEPARATOR . 'datasets'
                                          . DIRECTORY_SEPARATOR . 'fileImports.xml' );

    }

    protected function setUp( )
    {

        if ( !extension_loaded( 'pdo_mysql' ) )
        {

            $this->markTestSkipped( 'MySQL PDO Driver is not loaded' );

        }
        else
        {

            $config = new Zend_Config_Ini( '../config/config.ini', 'testing' );
            $db = Zend_Db::factory( $config->db->adapter, $config->db->toArray( ) );
            $db->query( "SET NAMES 'utf8'" );
            Zend_Db_Table::setDefaultAdapter( $db );

            parent::setUp( );

        }

    }

    public function testGenerateImportKey( )
    {
        
        $fileImports = new FileImports( );
        
        // Get the current number of import keys for user 1
        $select = $fileImports->select( )
                              ->where( 'userId = ?', 1 );
        $results = $fileImports->fetchAll( $select );
        $previousCount = count( $results );
        
        // Create a new import key
        $importKey = $fileImports->generateImportKey( 1 );
        
        // Check that it is 64 characters long and contains just numbers and 
        // letters
        $this->assertEquals( strlen( $importKey ), 64 );
        $this->assertRegExp( '/^[a-z0-9]+$/i', $importKey );
        
        // Get the new number of import keys for user 1
        $results = $fileImports->fetchAll( $select );
        $newCount = count( $results );
        
        // Make sure that it is 1 higher then the previous number
        $this->assertEquals( $previousCount + 1, $newCount );

    }

    public function testImportKeyExists( )
    {

        $fileImports = new FileImports( );
        
        $knownImportKey = 'eb0d6792c3385b9664209f6cf0943e89eb8917e16b0c23381c40c9033a08d5b8';
        $knownUser = 1;
        
        $this->assertTrue( $fileImports->importKeyExists( $knownImportKey, $knownUser ) );

    }

    public function testPrepare( )
    {

        $fileImports = new FileImports( );
        $fileImports->prepare( );
        
        // Test Client Codes have been loaded correctly
        $controlClientCodes = array(
            'ABCD' => 1,
            'BCDE' => 2,
            'CDEF' => 3,
            'XYZ1' => 4,
            'YZ12' => 5,
            'Z123' => 6,
            '1234' => 7,
            'THLR' => 8,
            'BCON' => 9,
            'THRC' => 10
        );
        $clientCodes = $this->readAttribute( $fileImports, '_clientCodes' );
        $this->assertEquals( $controlClientCodes, $clientCodes );
        
        // Test Payment Types have been loaded correctly
        $controlPaymentTypes = array( 
            'P' => array( 'PO' => 1 ),
            'T' => array( 'PP' => 2 ),
            'N' => array( 'TDC' => 3,
                          'TCC' => 4,
                          'TC' => 5
                   ),
            'Z' => array( 'PZ' => 6 ),
            'D' => array( 'DD' => 7 ),
            'E' => array( 'EPY' => 8 ),
            'Q' => array( 'CQE' => 9 ),
            'C' => array( 'CSH' => 10 ),
            'W' => array( 'WO' => 11 ),
            'A' => array( 'PO' => 1,
                          'AN' => 12 )
        );
        $paymentTypes = $this->readAttribute( $fileImports, '_paymentTypes' );
        $this->assertEquals( $controlPaymentTypes, $paymentTypes );
        
        // Test Payers have been loaded correctly
        $controlPayers = array( 
            'Test001' => 1
        );
        $payers = $this->readAttribute( $fileImports, '_payers' );
        $this->assertEquals( $controlPayers, $payers );

    }

    public function testImport( )
    {

        $fileImports = new FileImports( );
        
        // Get the current number of files in the system
        $files = new Files( );
        $currentFileCount = count( $files->fetchAll( ) );
        
        // Get the current number of payments in the system
        $payments = new Payments( );
        $currentPaymentCount = count( $payments->fetchAll( ) );
        
        // Get the current number of payers in the system
        $payers = new Payers( );
        $currentPayersCount = count( $payers->fetchAll( ) );
        
        // Prepare for the file import
        $fileImports->prepare( );
        
        // Area of test files to import
        $filesToImport = array(
            dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'files'
            . DIRECTORY_SEPARATOR . 'THLR2816.PO'
        );
        
        // Create the control report
        $controlReport = array( 
            array( 'file' => dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'files'
                           . DIRECTORY_SEPARATOR . 'THLR2816.PO',
                   'message' => '',
                   'result' => 'success' )
        );
        
        // Import the files
        $report = $fileImports->import( $filesToImport );
        
        $this->assertEquals( $controlReport, $report );
        
        // Create an array of known file information
        $controlFileInformation = array( 
            array( 'fileName' => 'THLR2816.PO',
                   'payers' => 48,
                   'payments' => 82,
                   'total' => 8184.14 )
        );
        
        $payersCount = 0;
        $paymentsCount = 0;        
        
        foreach( $controlFileInformation as $singleFileInformation ) {
            
            $payersCount += $singleFileInformation['payers'];
            $paymentsCount += $singleFileInformation['payments'];
            
        }
        
        // Make sure the number of files in the system has increased by the 
        // correct amount
        $newFileCount = count( $files->fetchAll( ) );
        $this->assertEquals( $currentFileCount + count( $controlFileInformation ),
                             $newFileCount );
        
        // Make sure the number of payments in the system has increased by the
        // correct amount
        $newPaymentCount = count( $payments->fetchAll( ) );
        $this->assertEquals( $currentPaymentCount + $paymentsCount,
                             $newPaymentCount );
        
        // Make sure the number of payers in the system has increased by the
        // correct amount
        $newPayersCount = count( $payers->fetchAll( ) );
        $this->assertEquals( $currentPayersCount + $payersCount, 
                             $newPayersCount );

    }
    
    public function testDuplicateFileImport( )
    {

        $fileImports = new FileImports( );
        
        // Prepare for the file import
        $fileImports->prepare( );
        
        // Area of test files to import
        $filesToImport = array(
            dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'files'
            . DIRECTORY_SEPARATOR . 'THLR2598.PO',
            dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'files'
            . DIRECTORY_SEPARATOR . 'THLR2598.PO'
        );
        
        // Create the control report
        $controlReport = array( 
            array( 'file' => dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'files'
                           . DIRECTORY_SEPARATOR . 'THLR2598.PO',
                   'message' => '',
                   'result' => 'success' ),
            array( 'file' => dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'files'
                           . DIRECTORY_SEPARATOR . 'THLR2598.PO',                   
                   'message' => 'This file has been previously imported.',
                   'result' => 'failed' )            
        );
        
        // Import the files
        $report = $fileImports->import( $filesToImport );
        
        $this->assertEquals( $controlReport, $report );
        
    }
    
    public function testCreateTempDirectory( )
    {

        // Create instance of the file imports model
        $fileImports = new FileImports( );
        
        // Use fileImportId = 1 to test as that is in the dataset
        $fileImportId = 1;
        
        // Attempt to get the temporary directory for the file import
        $tempDir = $fileImports->createTempDirectory( $fileImportId );
        
        // Test to see that it matches the expected value
        $controlTempDir = sys_get_temp_dir( ) . DIRECTORY_SEPARATOR 
                        . 'eb0d6792c3385b9664209f6cf0943e89eb8917e16b0c23381c40c9033a08d5b8'
                        . DIRECTORY_SEPARATOR;
        $this->assertEquals( $tempDir, $controlTempDir );
        
        // Test to see that the directory exists
        $this->assertTrue( file_exists( $tempDir ), 'Directory does not exist' );
        
        // Test to see that the directory is a directory
        $this->assertTrue( is_dir( $tempDir ), 'Directory is not actually a directory' );
        
        // Test to see that the directory is readable
        $this->assertTrue( is_readable( $tempDir ), 'Directory is not readable' );        
        
    }
    
    public function testGetProcessing( )
    {
        
        $this->markTestIncomplete( );
        
    }
    
    public function testGetCompleted( )
    {

        // Create instance of the file imports model
        $fileImports = new FileImports( );
        
        // Create the control array of the information we expect to get back
        $expected = array(
/*            array( 
                'importTime' => '07:44:31',
                'importDate' => '2008-11-06',
                'firstname'  => 'Barack',
                'files'      => '',
                'imported'   => '',
                'failed'     => ''
            ) */
        );
        
        $actual = $fileImports->getCompleted( );
        
        $this->assertEquals( $expected, $actual );

    }

}