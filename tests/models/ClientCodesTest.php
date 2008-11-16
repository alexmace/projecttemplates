<?php

require_once( 'PHPUnit/Extensions/Database/TestCase.php' );
require_once( 'Zend/Config/Ini.php' );
require_once( 'Zend/Db/Table.php' );
require_once( 'Zend/Db/Table/Abstract.php' );
require_once( 'models/ClientCodes.php' );

class ClientCodesTest extends PHPUnit_Extensions_Database_TestCase
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

    public function testGetStats( )
    {

        $clientCodes = new ClientCodes( );
        $clientCodesStats = $clientCodes->getStats( );

        $count = 0;

        // Set up the values that we are expecting to get back based on the
        // dataset in datasets/clientcodes.xml
        $correctValues = array(
            array(
                'clientCode' => '1234',
                'count' => '10',
                'paymentsTotal' => '200.00',
                'postOfficeCount' => '0',
                'payPointCount' => '10',
                'terminalDebitCardCount' => '0',
                'terminalCreditCardCount' => '0',
                'terminalCashCount' => '0',
                'payZoneCount' => '0',
                'directDebitCount' => '0',
                'ePayCount' => '0',
                'chequeCount' => '0',
                'cashCount' => '0',
                'woolworthsCount' => '0'
            ),
            array(
                'clientCode' => 'ABCD',
                'count' => '20',
                'paymentsTotal' => '200.00',
                'postOfficeCount' => '20',
                'payPointCount' => '0',
                'terminalDebitCardCount' => '0',
                'terminalCreditCardCount' => '0',
                'terminalCashCount' => '0',
                'payZoneCount' => '0',
                'directDebitCount' => '0',
                'ePayCount' => '0',
                'chequeCount' => '0',
                'cashCount' => '0',
                'woolworthsCount' => '0'
            ),
            array(
                'clientCode' => 'BCDE',
                'count' => '30',
                'paymentsTotal' => '601.00',
                'postOfficeCount' => '30',
                'payPointCount' => '0',
                'terminalDebitCardCount' => '0',
                'terminalCreditCardCount' => '0',
                'terminalCashCount' => '0',
                'payZoneCount' => '0',
                'directDebitCount' => '0',
                'ePayCount' => '0',
                'chequeCount' => '0',
                'cashCount' => '0',
                'woolworthsCount' => '0'
            ),
            array(
                'clientCode' => 'CDEF',
                'count' => '25',
                'paymentsTotal' => '500.00',
                'postOfficeCount' => '0',
                'payPointCount' => '25',
                'terminalDebitCardCount' => '0',
                'terminalCreditCardCount' => '0',
                'terminalCashCount' => '0',
                'payZoneCount' => '0',
                'directDebitCount' => '0',
                'ePayCount' => '0',
                'chequeCount' => '0',
                'cashCount' => '0',
                'woolworthsCount' => '0'
            ),
            array( 
                'clientCode' => 'THLR',
                'count' => '89',
                'paymentsTotal' => '8346.22',
                'postOfficeCount' => '89',
                'payPointCount' => '0',
                'terminalDebitCardCount' => '0',
                'terminalCreditCardCount' => '0',
                'terminalCashCount' => '0',
                'payZoneCount' => '0',
                'directDebitCount' => '0',
                'ePayCount' => '0',
                'chequeCount' => '0',
                'cashCount' => '0',
                'woolworthsCount' => '0'
            ),
            array(
                'clientCode' => 'XYZ1',
                'count' => '15',
                'paymentsTotal' => '300.00',
                'postOfficeCount' => '15',
                'payPointCount' => '0',
                'terminalDebitCardCount' => '0',
                'terminalCreditCardCount' => '0',
                'terminalCashCount' => '0',
                'payZoneCount' => '0',
                'directDebitCount' => '0',
                'ePayCount' => '0',
                'chequeCount' => '0',
                'cashCount' => '0',
                'woolworthsCount' => '0'
            ),
            array(
                'clientCode' => 'YZ12',
                'count' => '16',
                'paymentsTotal' => '320.00',
                'postOfficeCount' => '0',
                'payPointCount' => '16',
                'terminalDebitCardCount' => '0',
                'terminalCreditCardCount' => '0',
                'terminalCashCount' => '0',
                'payZoneCount' => '0',
                'directDebitCount' => '0',
                'ePayCount' => '0',
                'chequeCount' => '0',
                'cashCount' => '0',
                'woolworthsCount' => '0'
            ),
            array(
                'clientCode' => 'Z123',
                'count' => '17',
                'paymentsTotal' => '340.00',
                'postOfficeCount' => '17',
                'payPointCount' => '0',
                'terminalDebitCardCount' => '0',
                'terminalCreditCardCount' => '0',
                'terminalCashCount' => '0',
                'payZoneCount' => '0',
                'directDebitCount' => '0',
                'ePayCount' => '0',
                'chequeCount' => '0',
                'cashCount' => '0',
                'woolworthsCount' => '0'
            )
        );

        // Check that we got stats back for all of the expected client codes.
        $this->assertEquals( count( $correctValues ), count( $clientCodesStats ) );

        // Loop through the stats and make sure they equal what we expect
        foreach ( $clientCodesStats as $stats )
        {

            $this->assertEquals( $stats['clientCode'], $correctValues[$count]['clientCode'] );
            $this->assertEquals( $stats['count'], $correctValues[$count]['count'] );
            $this->assertEquals( $stats['paymentsTotal'], $correctValues[$count]['paymentsTotal'] );
            $this->assertEquals( $stats['postOfficeCount'], $correctValues[$count]['postOfficeCount'] );
            $this->assertEquals( $stats['payPointCount'], $correctValues[$count]['payPointCount'] );
            $this->assertEquals( $stats['terminalDebitCardCount'], $correctValues[$count]['terminalDebitCardCount'] );
            $this->assertEquals( $stats['terminalCreditCardCount'], $correctValues[$count]['terminalCreditCardCount'] );
            $this->assertEquals( $stats['terminalCashCount'], $correctValues[$count]['terminalCashCount'] );
            $this->assertEquals( $stats['payZoneCount'], $correctValues[$count]['payZoneCount'] );
            $this->assertEquals( $stats['directDebitCount'], $correctValues[$count]['directDebitCount'] );
            $this->assertEquals( $stats['ePayCount'], $correctValues[$count]['ePayCount'] );
            $this->assertEquals( $stats['chequeCount'], $correctValues[$count]['chequeCount'] );
            $this->assertEquals( $stats['cashCount'], $correctValues[$count]['cashCount'] );
            $this->assertEquals( $stats['woolworthsCount'], $correctValues[$count]['woolworthsCount'] );

            $count++;

        }

    }
    
    public function testListAsArray( )
    {
     
        $cc = new ClientCodes( );

        $ccList = $cc->listAsArray( );
        
        $this->assertEquals( count( $ccList ), 8 );
        
        $controlCCList = array( 
            '1' => 'ABCD',
            '2' => 'BCDE',
            '3' => 'CDEF',
            '4' => 'XYZ1',
            '5' => 'YZ12',
            '6' => 'Z123',
            '7' => '1234',
            '8' => 'THLR'
        );
        
        $count = 0;
        
        $clientCodeIDs = array_keys( $controlCCList );
        
        foreach( $ccList as $clientCodeID => $clientCode )
        {

            $this->assertEquals( $clientCodeIDs[$count], $clientCodeID );
            $this->assertEquals( $controlCCList[$clientCodeIDs[$count]], $clientCode );
            
            $count++;
            
        }

    }

}