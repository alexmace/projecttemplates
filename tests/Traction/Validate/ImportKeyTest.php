<?php

require_once( 'PHPUnit/Extensions/Database/TestCase.php' );
require_once( 'Traction/Validate/ImportKey.php' );
require_once( 'Zend/Config/Ini.php' );
require_once( 'Zend/Db/Table.php' );
require_once( 'Zend/Db/Table/Abstract.php' );
require_once( 'models/FileImports.php' );

class Traction_Validate_ImportKeyTest extends PHPUnit_Extensions_Database_TestCase
{

    protected $_validator;
    
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
        
        $this->_validator = new Traction_Validate_ImportKey( );

    }

    public function testIsValid( )
    {

        $this->assertTrue( $this->_validator->isValid( 'eb0d6792c3385b9664209f6cf0943e89eb8917e16b0c23381c40c9033a08d5b8' ) );

    }

}