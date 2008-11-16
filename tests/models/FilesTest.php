<?php

require_once( 'PHPUnit/Extensions/Database/TestCase.php' );

class FilesTest extends PHPUnit_Extensions_Database_TestCase
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
                                          . DIRECTORY_SEPARATOR . 'clientcodes.xml' );

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
    
    public function testCheckIfFilePresent( )
    {

        $files = new Files( );

        // File to test with. This one is already in the system so it should
        // come back with true.
        $fileName = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'files'
                  . DIRECTORY_SEPARATOR . 'THLR2598.PO';
                  
        $this->assertTrue( $files->checkIfFilePresent( $fileName ) );
        
        // This one is not in the system so it should come back false
        $fileName = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'files'
                  . DIRECTORY_SEPARATOR . 'BCON7839.PO';
        $this->assertFalse( $files->checkIfFilePresent( $fileName ) );          

    }
    
    public function testSearch( )
    {
        
        $files = new Files( );
        
        $foundFiles = $files->search( 'THLR2598.PO' );
        $controlFoundFiles = array( 
            array( 
                'clientCodeID' => 8,
                'fileName' => 'THLR2598.PO'
            )
        );
        
        $this->assertEquals( $foundFiles, $controlFoundFiles );
        
        $foundFiles = $files->search( 'A' );
        $controlFoundFiles = array( 
            array( 
                'clientCodeID' => 1,
                'fileName' => 'ABCD0001.PO'
            )
        );
        
    }
    
    public function testGetRecentImportPaymentFiles( )
    {
        
        $files = new Files( );
        $recentFiles = $files->getRecentlyImportedPaymentFiles( 10 );
        
        $controlRecentFiles = array( 
        
            array( 'clientCodeID' => '8',
                   'fileName' => 'THLR2598.PO' ),
            array( 'clientCodeID' => '5',
                   'fileName' => 'YZ120001.PP' ),
            array( 'clientCodeID' => '4',
                   'fileName' => 'XYZ10001.PO' ),
            array( 'clientCodeID' => '1',
                   'fileName' => 'ABCD0001.PO' ),
            array( 'clientCodeID' => '7',
                   'fileName' => '12340001.PP' ),
            array( 'clientCodeID' => '2',
                   'fileName' => 'BCDE0001.PO' ),
            array( 'clientCodeID' => '3',
                   'fileName' => 'CDEF0001.PP' ),
            array( 'clientCodeID' => '6',
                   'fileName' => 'Z1230001.PO' ),
                      
        );
        
        $this->assertEquals( count( $recentFiles ), 8 );
        
        $count = 0;
        
        foreach( $recentFiles as $recentFile )
        {
            
            $this->assertEquals( $recentFile->clientCodeID, $controlRecentFiles[$count]['clientCodeID'] );
            $this->assertEquals( $recentFile->fileName, $controlRecentFiles[$count]['fileName'] );
            
            $count++;
            
        }
        
    }

}