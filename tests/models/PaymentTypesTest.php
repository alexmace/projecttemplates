<?php

require_once( 'PHPUnit/Extensions/Database/TestCase.php' );
require_once( 'Zend/Config/Ini.php' );
require_once( 'Zend/Db/Table.php' );
require_once( 'Zend/Db/Table/Abstract.php' );
require_once( 'models/PaymentTypes.php' );

class PaymentTypesTest extends PHPUnit_Extensions_Database_TestCase
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
    
    public function testGetPaymentTypeIdentifiers( )
    {

        $paymentTypes = new PaymentTypes( );
        
        $controlPaymentTypeIdentifiers = array( 
            array( 
                'paymentTypeID' => 1,
                'description' => 'Post Office',
                'fileExtension' => 'PO',
                'identifier' => 'P'
            ), 
            array( 
                'paymentTypeID' => 1,
                'description' => 'Post Office',
                'fileExtension' => 'PO',
                'identifier' => 'A'
            ), 
            array( 
                'paymentTypeID' => 2,
                'description' => 'PayPoint',
                'fileExtension' => 'PP',
                'identifier' => 'T'
            ), 
            array( 
                'paymentTypeID' => 3,
                'description' => 'Terminal Debit Card',
                'fileExtension' => 'TDC',
                'identifier' => 'N'
            ), 
            array( 
                'paymentTypeID' => 4,
                'description' => 'Terminal Credit Card',
                'fileExtension' => 'TCC',
                'identifier' => 'N'
            ), 
            array( 
                'paymentTypeID' => 5,
                'description' => 'Terminal Cash',
                'fileExtension' => 'TC',
                'identifier' => 'N'
            ), 
            array( 
                'paymentTypeID' => 6,
                'description' => 'PayZone',
                'fileExtension' => 'PZ',
                'identifier' => 'Z'
            ), 
            array( 
                'paymentTypeID' => 7,
                'description' => 'Direct Debit',
                'fileExtension' => 'DD',
                'identifier' => 'D'
            ), 
            array( 
                'paymentTypeID' => 8,
                'description' => 'EPay',
                'fileExtension' => 'EPY',
                'identifier' => 'E'
            ),
            array( 
                'paymentTypeID' => 9,
                'description' => 'Cheque',
                'fileExtension' => 'CQE',
                'identifier' => 'Q'
            ), 
            array( 
                'paymentTypeID' => 10,
                'description' => 'Cash',
                'fileExtension' => 'CSH',
                'identifier' => 'C'
            ), 
            array( 
                'paymentTypeID' => 11,
                'description' => 'Woolworths',
                'fileExtension' => 'WO',
                'identifier' => 'W'
            ),
            array( 
                'paymentTypeID' => 12,
                'description' => 'AnPost',
                'fileExtension' => 'AN',
                'identifier' => 'A'
            ),
        );
        
        $paymentIdentifiers = $paymentTypes->getPaymentTypeIdentifers( );
        
        $this->assertEquals( count( $paymentIdentifiers ), count( $controlPaymentTypeIdentifiers ) );
        
        $count = 0;
        
        foreach( $paymentIdentifiers as $identifier )
        {

            $this->assertEquals( $identifier->paymentTypeID, $controlPaymentTypeIdentifiers[$count]['paymentTypeID'] );
            $this->assertEquals( $identifier->description, $controlPaymentTypeIdentifiers[$count]['description'] );
            $this->assertEquals( $identifier->fileExtension, $controlPaymentTypeIdentifiers[$count]['fileExtension'] );
            $this->assertEquals( $identifier->identifier, $controlPaymentTypeIdentifiers[$count]['identifier'] );
            
            $count++;
            
        }
        
    }

}