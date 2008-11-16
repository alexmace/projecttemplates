<?php

require_once( 'PHPUnit/Extensions/Database/TestCase.php' );

class UsersTest extends PHPUnit_Extensions_Database_TestCase
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


    }

    protected function setUp( )
    {

        if ( !extension_loaded( 'pdo_mysql' ) )
        {

            $this->markTestSkipped( 'MySQL PDO Driver is not loaded' );

        }

    }

}