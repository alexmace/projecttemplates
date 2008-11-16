<?php

require_once( 'PHPUnit/Extensions/Database/TestCase.php' );
require_once( 'Zend/Config/Ini.php' );
require_once( 'Zend/Db/Table.php' );
require_once( 'Zend/Db/Table/Abstract.php' );
require_once( 'models/Payments.php' );

class PaymentsTest extends PHPUnit_Extensions_Database_TestCase
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
    
    public function testGetPaymentsInFile( )
    {

    	$payments = new Payments( );
    	$paymentsInFile = $payments->getPaymentsInFile( 'THLR2598.PO', 8 );
    	
    	$this->assertEquals( 89, count( $paymentsInFile ) );
    	
    	$total = 0;
    	
    	foreach( $paymentsInFile as $payment )
    	{
    		
    		$total += $payment->amount;
    		
    	}
    	
    	$this->assertEquals( round( 8346.22, 2 ), round( $total, 2 ) );
    	
    }
    
    public function testCalculateActualPayments( )
    {

    	$payments = new Payments( );
    	$paymentsInFile = $payments->getPaymentsInFile( 'THLR2598.PO', 8 );
    	
    	$actualPayments = $payments->calculateActualPayments( $paymentsInFile );
    	
    	$this->assertEquals( 60, count( $actualPayments ) );
    	
    	$total = 0;
    	
    	$controlActualPayments = array(
           array( 'clientCodeID' => 8,
                  'reference' => 'ACH009',
                  'amount' => '73.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 8,
                  'originalPayments' => array(
                    '134' => '73.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'AKT002',
                  'amount' => '5.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 9,
                  'originalPayments' => array(
                    '135' => '5.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BAR111',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 10,
                  'originalPayments' => array(
                    '136' => '100.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BAX005',
                  'amount' => '279.21',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 11,
                  'originalPayments' => array(
                    '137' => '150.00',
                    '138' => '129.21'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BEL013',
                  'amount' => '350.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 12,
                  'originalPayments' => array(
                    '139' => '150.00',
                    '140' => '150.00',
                    '141' => '50.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BLA050',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 13,
                  'originalPayments' => array(
                    '142' => '100.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BOU003',
                  'amount' => '40.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 14,
                  'originalPayments' => array(
                    '143' => '40.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BRI014',
                  'amount' => '400.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 15,
                  'originalPayments' => array(
                    '144' => '150.00',
                    '145' => '150.00',
                    '146' => '100.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BYE002',
                  'amount' => '432.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 16,
                  'originalPayments' => array(
                    '147' => '150.00',
                    '148' => '150.00',
                    '149' => '132.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CAR045',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 17,
                  'originalPayments' => array(
                    '150' => '10.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CAR645',
                  'amount' => '8.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 18,
                  'originalPayments' => array(
                    '151' => '8.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CIN001',
                  'amount' => '110.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 19,
                  'originalPayments' => array(
                    '152' => '110.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CUN006',
                  'amount' => '7.16',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 20,
                  'originalPayments' => array(
                    '153' => '7.16'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'DAR003',
                  'amount' => '50.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 21,
                  'originalPayments' => array(
                    '154' => '50.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'DOU014',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 22,
                  'originalPayments' => array(
                    '155' => '100.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'DYK001',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 23,
                  'originalPayments' => array(
                    '156' => '10.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'EDD01',
                  'amount' => '204.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 24,
                  'originalPayments' => array(
                    '157' => '150.00',
                    '158' => '54.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'FIT501',
                  'amount' => '76.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 25,
                  'originalPayments' => array(
                    '159' => '76.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'FOR005',
                  'amount' => '117.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 26,
                  'originalPayments' => array(
                    '160' => '117.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G840117',
                  'amount' => '300.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 27,
                  'originalPayments' => array(
                    '161' => '150.00',
                    '162' => '150.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G841473',
                  'amount' => '250.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 28,
                  'originalPayments' => array(
                    '163' => '150.00',
                    '164' => '100.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G841972',
                  'amount' => '43.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 29,
                  'originalPayments' => array(
                    '165' => '43.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G842186',
                  'amount' => '500.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 30,
                  'originalPayments' => array(
                    '166' => '150.00',
                    '167' => '150.00',
                    '168' => '150.00',
                    '169' => '50.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'GOD003',
                  'amount' => '5.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 31,
                  'originalPayments' => array(
                    '170' => '5.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HAR063',
                  'amount' => '70.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 32,
                  'originalPayments' => array(
                    '171' => '70.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HAZ003',
                  'amount' => '55.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 33,
                  'originalPayments' => array(
                    '172' => '55.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HEW005',
                  'amount' => '7.40',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 34,
                  'originalPayments' => array(
                    '173' => '7.40'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HIN005',
                  'amount' => '98.45',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 35,
                  'originalPayments' => array(
                    '174' => '98.45'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HOB501',
                  'amount' => '131.34',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 36,
                  'originalPayments' => array(
                    '175' => '131.34'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HON004',
                  'amount' => '512.70',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 37,
                  'originalPayments' => array(
                    '176' => '150.00',
                    '177' => '150.00',
                    '178' => '150.00',
                    '179' => '62.70'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'KAN005',
                  'amount' => '60.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 38,
                  'originalPayments' => array(
                    '180' => '60.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'KIN012',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 39,
                  'originalPayments' => array(
                    '181' => '100.00'
                  ) 
            ),
           array( 'clientCodeID' => 8,
                  'reference' => 'KUR004',
                  'amount' => '150.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 40,
                  'originalPayments' => array(
                    '182' => '150.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'LAD001',
                  'amount' => '26.50',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 41,
                  'originalPayments' => array(
                    '183' => '26.50'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MAS050',
                  'amount' => '18.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 42,
                  'originalPayments' => array(
                    '184' => '18.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MER008',
                  'amount' => '240.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 43,
                  'originalPayments' => array(
                    '185' => '150.00',
                    '186' => '90.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MOR035',
                  'amount' => '39.82',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 44,
                  'originalPayments' => array(
                    '187' => '39.82'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MUR032',
                  'amount' => '160.90',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 45,
                  'originalPayments' => array(
                    '188' => '150.00',
                    '189' => '10.90'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'NAK001',
                  'amount' => '150.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 46,
                  'originalPayments' => array(
                    '190' => '150.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'NOL003',
                  'amount' => '25.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-30',
                  'fileID' => 8,
                  'payerID' => 47,
                  'originalPayments' => array(
                    '191' => '25.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PAR011',
                  'amount' => '70.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 48,
                  'originalPayments' => array(
                    '192' => '70.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PEA005',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 49,
                  'originalPayments' => array(
                    '193' => '150.00',
                    '194' => '50.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PIN002',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 50,
                  'originalPayments' => array(
                    '195' => '10.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'POV501',
                  'amount' => '236.50',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 51,
                  'originalPayments' => array(
                    '196' => '150.00',
                    '197' => '86.50'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PRI007',
                  'amount' => '400.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 52,
                  'originalPayments' => array(
                    '198' => '150.00',
                    '199' => '150.00',
                    '200' => '100.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'RAF008',
                  'amount' => '60.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 53,
                  'originalPayments' => array(
                    '201' => '60.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'REG005',
                  'amount' => '20.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 54,
                  'originalPayments' => array(
                    '202' => '20.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'ROD503',
                  'amount' => '140.40',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 55,
                  'originalPayments' => array(
                    '203' => '140.40'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'ROU005',
                  'amount' => '247.84',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 56,
                  'originalPayments' => array(
                    '204' => '150.00',
                    '205' => '97.84'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SAI002',
                  'amount' => '18.26',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 57,
                  'originalPayments' => array(
                    '206' => '18.26'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SAL002',
                  'amount' => '230.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 58,
                  'originalPayments' => array(
                    '207' => '150.00',
                    '208' => '80.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SHO007',
                  'amount' => '110.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 59,
                  'originalPayments' => array(
                    '209' => '110.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SIM001',
                  'amount' => '300.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 60,
                  'originalPayments' => array(
                    '210' => '150.00',
                    '211' => '150.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'STA007',
                  'amount' => '350.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 61,
                  'originalPayments' => array(
                    '212' => '150.00',
                    '213' => '150.00',
                    '214' => '50.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TAW001',
                  'amount' => '8.60',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 62,
                  'originalPayments' => array( 
                    '215' => '8.60'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TOW001',
                  'amount' => '103.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-30',
                  'fileID' => 8,
                  'payerID' => 63,
                  'originalPayments' => array( 
                    '216' => '103.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TSH001',
                  'amount' => '187.14',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 64,
                  'originalPayments' => array( 
                    '217' => '150.00',
                    '218' => '37.14'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TUR012',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-30',
                  'fileID' => 8,
                  'payerID' => 65,
                  'originalPayments' => array( 
                    '219' => '10.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'UTH001',
                  'amount' => '220.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 66,
                  'originalPayments' => array( 
                    '220' => '150.00',
                    '221' => '70.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'VUK001',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 67,
                  'originalPayments' => array( 
                    '222' => '10.00'
                  ) 
            )
    	);
    	
    	$this->assertEquals( $actualPayments, $controlActualPayments );
    	
    	foreach( $actualPayments as $payment )
    	{

    	    $total += $payment['amount'];

    	}
    	
        $this->assertEquals( round( 8346.22, 2 ), round( $total, 2 ) );
    	
    }
    
    public function testGenerateStats( )
    {

        $payments = new Payments( );

        $paymentsInFile = $payments->getPaymentsInFile( 'THLR2598.PO', 8 );
        $actualPayments = $payments->calculateActualPayments( $paymentsInFile );
        $cappedPayments = $payments->calculatePaymentsAfterCap( $actualPayments, 100.00 );
        
        $stats = $payments->generateStats( $paymentsInFile, $actualPayments, $cappedPayments );
        
        $this->assertEquals( count( $stats ), 9 );
        $this->assertEquals( $stats['paymentsCount'], 89 );
        $this->assertEquals( $stats['actualPaymentsCount'], 60 );
        $this->assertEquals( $stats['difference'], 29 );
        $this->assertEquals( round( $stats['percentageIncrease'], 2 ),
                             round( 48.333, 2 )  );
        $this->assertEquals( round( $stats['total'], 2 ),
                             round( 8346.22, 2 ) );
                             
        $controlStatsPayments = array(
           array( 'clientCodeID' => 8,
                  'reference' => 'ACH009',
                  'amount' => '73.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 8,
                  'originalPayments' => array(
                    '134' => '73.00'
                  ), 
                  'cappedPayments' => array(
                    '73.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'AKT002',
                  'amount' => '5.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 9,
                  'originalPayments' => array(
                    '135' => '5.00'
                  ), 
                  'cappedPayments' => array(
                    '5.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BAR111',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 10,
                  'originalPayments' => array(
                    '136' => '100.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BAX005',
                  'amount' => '279.21',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 11,
                  'originalPayments' => array(
                    '137' => '150.00',
                    '138' => '129.21'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '79.21'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BEL013',
                  'amount' => '350.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 12,
                  'originalPayments' => array(
                    '139' => '150.00',
                    '140' => '150.00',
                    '141' => '50.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '100.00',
                    '50.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BLA050',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 13,
                  'originalPayments' => array(
                    '142' => '100.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BOU003',
                  'amount' => '40.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 14,
                  'originalPayments' => array(
                    '143' => '40.00'
                  ), 
                  'cappedPayments' => array(
                    '40.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BRI014',
                  'amount' => '400.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 15,
                  'originalPayments' => array(
                    '144' => '150.00',
                    '145' => '150.00',
                    '146' => '100.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '100.00',
                    '100.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BYE002',
                  'amount' => '432.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 16,
                  'originalPayments' => array(
                    '147' => '150.00',
                    '148' => '150.00',
                    '149' => '132.00'
                  ) , 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '100.00',
                    '100.00',
                    '32.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CAR045',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 17,
                  'originalPayments' => array(
                    '150' => '10.00'
                  ), 
                  'cappedPayments' => array(
                    '10.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CAR645',
                  'amount' => '8.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 18,
                  'originalPayments' => array(
                    '151' => '8.00'
                  ), 
                  'cappedPayments' => array(
                    '8.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CIN001',
                  'amount' => '110.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 19,
                  'originalPayments' => array(
                    '152' => '110.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '10.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CUN006',
                  'amount' => '7.16',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 20,
                  'originalPayments' => array(
                    '153' => '7.16'
                  ), 
                  'cappedPayments' => array(
                    '7.16'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'DAR003',
                  'amount' => '50.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 21,
                  'originalPayments' => array(
                    '154' => '50.00'
                  ), 
                  'cappedPayments' => array(
                    '50.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'DOU014',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 22,
                  'originalPayments' => array(
                    '155' => '100.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'DYK001',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 23,
                  'originalPayments' => array(
                    '156' => '10.00'
                  ), 
                  'cappedPayments' => array(
                    '10.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'EDD01',
                  'amount' => '204.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 24,
                  'originalPayments' => array(
                    '157' => '150.00',
                    '158' => '54.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '4.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'FIT501',
                  'amount' => '76.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 25,
                  'originalPayments' => array(
                    '159' => '76.00'
                  ), 
                  'cappedPayments' => array(
                    '76.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'FOR005',
                  'amount' => '117.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 26,
                  'originalPayments' => array(
                    '160' => '117.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '17.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G840117',
                  'amount' => '300.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 27,
                  'originalPayments' => array(
                    '161' => '150.00',
                    '162' => '150.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '100.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G841473',
                  'amount' => '250.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 28,
                  'originalPayments' => array(
                    '163' => '150.00',
                    '164' => '100.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '50.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G841972',
                  'amount' => '43.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 29,
                  'originalPayments' => array(
                    '165' => '43.00'
                  ), 
                  'cappedPayments' => array(
                    '43.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G842186',
                  'amount' => '500.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 30,
                  'originalPayments' => array(
                    '166' => '150.00',
                    '167' => '150.00',
                    '168' => '150.00',
                    '169' => '50.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '100.00',
                    '100.00',
                    '100.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'GOD003',
                  'amount' => '5.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 31,
                  'originalPayments' => array(
                    '170' => '5.00'
                  ), 
                  'cappedPayments' => array(
                    '5.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HAR063',
                  'amount' => '70.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 32,
                  'originalPayments' => array(
                    '171' => '70.00'
                  ), 
                  'cappedPayments' => array(
                    '70.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HAZ003',
                  'amount' => '55.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 33,
                  'originalPayments' => array(
                    '172' => '55.00'
                  ), 
                  'cappedPayments' => array(
                    '55.00'
                  ) 
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HEW005',
                  'amount' => '7.40',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 34,
                  'originalPayments' => array(
                    '173' => '7.40'
                  ), 
                  'cappedPayments' => array(
                    '7.40'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HIN005',
                  'amount' => '98.45',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 35,
                  'originalPayments' => array(
                    '174' => '98.45'
                  ), 
                  'cappedPayments' => array(
                    '98.45'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HOB501',
                  'amount' => '131.34',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 36,
                  'originalPayments' => array(
                    '175' => '131.34'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '31.34'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HON004',
                  'amount' => '512.70',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 37,
                  'originalPayments' => array(
                    '176' => '150.00',
                    '177' => '150.00',
                    '178' => '150.00',
                    '179' => '62.70'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '100.00',
                    '100.00',
                    '100.00',
                    '12.70'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'KAN005',
                  'amount' => '60.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 38,
                  'originalPayments' => array(
                    '180' => '60.00'
                  ), 
                  'cappedPayments' => array(
                    '60.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'KIN012',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 39,
                  'originalPayments' => array(
                    '181' => '100.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00'
                  )
            ),
           array( 'clientCodeID' => 8,
                  'reference' => 'KUR004',
                  'amount' => '150.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 40,
                  'originalPayments' => array(
                    '182' => '150.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '50.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'LAD001',
                  'amount' => '26.50',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 41,
                  'originalPayments' => array(
                    '183' => '26.50'
                  ), 
                  'cappedPayments' => array(
                    '26.50'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MAS050',
                  'amount' => '18.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 42,
                  'originalPayments' => array(
                    '184' => '18.00'
                  ), 
                  'cappedPayments' => array(
                    '18.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MER008',
                  'amount' => '240.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 43,
                  'originalPayments' => array(
                    '185' => '150.00',
                    '186' => '90.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '40.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MOR035',
                  'amount' => '39.82',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 44,
                  'originalPayments' => array(
                    '187' => '39.82'
                  ), 
                  'cappedPayments' => array(
                    '39.82'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MUR032',
                  'amount' => '160.90',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 45,
                  'originalPayments' => array(
                    '188' => '150.00',
                    '189' => '10.90'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '60.90'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'NAK001',
                  'amount' => '150.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 46,
                  'originalPayments' => array(
                    '190' => '150.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '50.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'NOL003',
                  'amount' => '25.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-30',
                  'fileID' => 8,
                  'payerID' => 47,
                  'originalPayments' => array(
                    '191' => '25.00'
                  ), 
                  'cappedPayments' => array(
                    '25.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PAR011',
                  'amount' => '70.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 48,
                  'originalPayments' => array(
                    '192' => '70.00'
                  ), 
                  'cappedPayments' => array(
                    '70.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PEA005',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 49,
                  'originalPayments' => array(
                    '193' => '150.00',
                    '194' => '50.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PIN002',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 50,
                  'originalPayments' => array(
                    '195' => '10.00'
                  ), 
                  'cappedPayments' => array(
                    '10.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'POV501',
                  'amount' => '236.50',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 51,
                  'originalPayments' => array(
                    '196' => '150.00',
                    '197' => '86.50'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '36.50'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PRI007',
                  'amount' => '400.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 52,
                  'originalPayments' => array(
                    '198' => '150.00',
                    '199' => '150.00',
                    '200' => '100.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '100.00',
                    '100.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'RAF008',
                  'amount' => '60.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 53,
                  'originalPayments' => array(
                    '201' => '60.00'
                  ), 
                  'cappedPayments' => array(
                    '60.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'REG005',
                  'amount' => '20.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 54,
                  'originalPayments' => array(
                    '202' => '20.00'
                  ), 
                  'cappedPayments' => array(
                    '20.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'ROD503',
                  'amount' => '140.40',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 55,
                  'originalPayments' => array(
                    '203' => '140.40'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '40.40'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'ROU005',
                  'amount' => '247.84',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 56,
                  'originalPayments' => array(
                    '204' => '150.00',
                    '205' => '97.84'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '47.84'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SAI002',
                  'amount' => '18.26',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 57,
                  'originalPayments' => array(
                    '206' => '18.26'
                  ), 
                  'cappedPayments' => array(
                    '18.26'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SAL002',
                  'amount' => '230.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 58,
                  'originalPayments' => array(
                    '207' => '150.00',
                    '208' => '80.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '30.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SHO007',
                  'amount' => '110.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 59,
                  'originalPayments' => array(
                    '209' => '110.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '10.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SIM001',
                  'amount' => '300.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 60,
                  'originalPayments' => array(
                    '210' => '150.00',
                    '211' => '150.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '100.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'STA007',
                  'amount' => '350.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 61,
                  'originalPayments' => array(
                    '212' => '150.00',
                    '213' => '150.00',
                    '214' => '50.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '100.00',
                    '50.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TAW001',
                  'amount' => '8.60',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 62,
                  'originalPayments' => array( 
                    '215' => '8.60'
                  ), 
                  'cappedPayments' => array(
                    '8.60'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TOW001',
                  'amount' => '103.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-30',
                  'fileID' => 8,
                  'payerID' => 63,
                  'originalPayments' => array( 
                    '216' => '103.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '3.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TSH001',
                  'amount' => '187.14',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 64,
                  'originalPayments' => array( 
                    '217' => '150.00',
                    '218' => '37.14'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '87.14'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TUR012',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-30',
                  'fileID' => 8,
                  'payerID' => 65,
                  'originalPayments' => array( 
                    '219' => '10.00'
                  ), 
                  'cappedPayments' => array(
                    '10.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'UTH001',
                  'amount' => '220.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 66,
                  'originalPayments' => array( 
                    '220' => '150.00',
                    '221' => '70.00'
                  ), 
                  'cappedPayments' => array(
                    '100.00',
                    '100.00',
                    '20.00'
                  )
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'VUK001',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 67,
                  'originalPayments' => array( 
                    '222' => '10.00'
                  ), 
                  'cappedPayments' => array(
                    '10.00'
                  )
            )
        );
                             
        $this->assertEquals( $controlStatsPayments, $stats['payments'] );
        
    }
    
    public function testCalculatePaymentsAfterLowerCap( )
    {
        
        $payments = new Payments( );
        
        // Get the payments in a file, then calculate what the original payments
        // were, so that we can then use this information to pass in with a new
        // cap and see what the payments would be at that level.
        $paymentsInFile = $payments->getPaymentsInFile( 'THLR2598.PO', 8 );
        $actualPayments = $payments->calculateActualPayments( $paymentsInFile );
        
        $cappedPayments = $payments->calculatePaymentsAfterCap( $actualPayments, 100.00 );
        
        $controlCappedPayments = array(
           array( 'clientCodeID' => 8,
                  'reference' => 'ACH009',
                  'amount' => '73.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 8
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'AKT002',
                  'amount' => '5.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 9
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BAR111',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 10
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BAX005',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 11
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BAX005',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 11
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BAX005',
                  'amount' => '79.21',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 11
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BEL013',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 12
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BEL013',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 12
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BEL013',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 12
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BEL013',
                  'amount' => '50.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 12
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BLA050',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 13
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BOU003',
                  'amount' => '40.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 14
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BRI014',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 15
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BRI014',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 15
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BRI014',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 15
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BRI014',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 15
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BYE002',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 16
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BYE002',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 16
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BYE002',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 16
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BYE002',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 16
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BYE002',
                  'amount' => '32.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 16
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CAR045',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 17
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CAR645',
                  'amount' => '8.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 18
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CIN001',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 19
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CIN001',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 19
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CUN006',
                  'amount' => '7.16',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 20
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'DAR003',
                  'amount' => '50.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 21
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'DOU014',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 22
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'DYK001',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 23
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'EDD01',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 24
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'EDD01',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 24
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'EDD01',
                  'amount' => '4.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 24
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'FIT501',
                  'amount' => '76.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 25
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'FOR005',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 26
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'FOR005',
                  'amount' => '17.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 26
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G840117',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 27
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G840117',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 27
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G840117',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 27
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G841473',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 28
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G841473',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 28
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G841473',
                  'amount' => '50.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 28
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G841972',
                  'amount' => '43.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 29
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G842186',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 30
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G842186',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 30
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G842186',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 30
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G842186',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 30
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G842186',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 30
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'GOD003',
                  'amount' => '5.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 31
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HAR063',
                  'amount' => '70.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 32
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HAZ003',
                  'amount' => '55.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 33
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HEW005',
                  'amount' => '7.40',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 34
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HIN005',
                  'amount' => '98.45',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 35
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HOB501',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 36
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HOB501',
                  'amount' => '31.34',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 36
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HON004',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 37
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HON004',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 37
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HON004',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 37
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HON004',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 37
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HON004',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 37
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HON004',
                  'amount' => '12.70',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 37
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'KAN005',
                  'amount' => '60.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 38
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'KIN012',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 39
            ),
           array( 'clientCodeID' => 8,
                  'reference' => 'KUR004',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 40
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'KUR004',
                  'amount' => '50.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 40
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'LAD001',
                  'amount' => '26.50',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 41
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MAS050',
                  'amount' => '18.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 42
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MER008',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 43
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MER008',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 43
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MER008',
                  'amount' => '40.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 43
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MOR035',
                  'amount' => '39.82',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 44
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MUR032',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 45
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MUR032',
                  'amount' => '60.90',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 45
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'NAK001',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 46
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'NAK001',
                  'amount' => '50.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 46
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'NOL003',
                  'amount' => '25.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-30',
                  'fileID' => 8,
                  'payerID' => 47
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PAR011',
                  'amount' => '70.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 48
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PEA005',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 49
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PEA005',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 49
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PIN002',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 50
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'POV501',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 51
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'POV501',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 51
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'POV501',
                  'amount' => '36.50',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 51
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PRI007',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 52
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PRI007',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 52
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PRI007',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 52
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PRI007',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 52
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'RAF008',
                  'amount' => '60.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 53
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'REG005',
                  'amount' => '20.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 54
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'ROD503',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 55
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'ROD503',
                  'amount' => '40.40',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 55
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'ROU005',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 56
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'ROU005',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 56
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'ROU005',
                  'amount' => '47.84',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 56
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SAI002',
                  'amount' => '18.26',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 57
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SAL002',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 58
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SAL002',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 58
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SAL002',
                  'amount' => '30.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 58
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SHO007',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 59
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SHO007',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 59
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SIM001',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 60
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SIM001',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 60
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SIM001',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 60
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'STA007',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 61
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'STA007',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 61
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'STA007',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 61
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'STA007',
                  'amount' => '50.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 61
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TAW001',
                  'amount' => '8.60',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 62
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TOW001',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-30',
                  'fileID' => 8,
                  'payerID' => 63
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TOW001',
                  'amount' => '3.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-30',
                  'fileID' => 8,
                  'payerID' => 63
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TSH001',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 64
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TSH001',
                  'amount' => '87.14',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 64
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TUR012',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-30',
                  'fileID' => 8,
                  'payerID' => 65
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'UTH001',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 66
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'UTH001',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 66
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'UTH001',
                  'amount' => '20.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 66
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'VUK001',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 67
            )
        );
        
        // Test that the generated payments match what we expected
        $this->assertEquals( $cappedPayments, $controlCappedPayments );
        
    }
    
    public function testCalculatePaymentsAfterHigherCap( )
    {
        
        $payments = new Payments( );
        
        // Get the payments in a file, then calculate what the original payments
        // were, so that we can then use this information to pass in with a new
        // cap and see what the payments would be at that level.
        $paymentsInFile = $payments->getPaymentsInFile( 'THLR2598.PO', 8 );
        $actualPayments = $payments->calculateActualPayments( $paymentsInFile );
        
        $cappedPayments = $payments->calculatePaymentsAfterCap( $actualPayments, 200.00 );
        
        $controlCappedPayments = array(
           array( 'clientCodeID' => 8,
                  'reference' => 'ACH009',
                  'amount' => '73.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 8
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'AKT002',
                  'amount' => '5.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 9
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BAR111',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 10
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BAX005',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 11
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BAX005',
                  'amount' => '79.21',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 11
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BEL013',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 12
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BEL013',
                  'amount' => '150.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 12
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BLA050',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 13
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BOU003',
                  'amount' => '40.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 14
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BRI014',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 15
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BRI014',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 15
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BYE002',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 16
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BYE002',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 16
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'BYE002',
                  'amount' => '32.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 16
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CAR045',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 17
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CAR645',
                  'amount' => '8.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 18
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CIN001',
                  'amount' => '110.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 19
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'CUN006',
                  'amount' => '7.16',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 20
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'DAR003',
                  'amount' => '50.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 21
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'DOU014',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 22
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'DYK001',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 23
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'EDD01',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 24
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'EDD01',
                  'amount' => '4.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 24
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'FIT501',
                  'amount' => '76.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 25
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'FOR005',
                  'amount' => '117.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 26
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G840117',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 27
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G840117',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 27
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G841473',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 28
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G841473',
                  'amount' => '50.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 28
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G841972',
                  'amount' => '43.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 29
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G842186',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 30
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G842186',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 30
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'G842186',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 30
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'GOD003',
                  'amount' => '5.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 31
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HAR063',
                  'amount' => '70.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 32
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HAZ003',
                  'amount' => '55.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 33
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HEW005',
                  'amount' => '7.40',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 34
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HIN005',
                  'amount' => '98.45',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 35
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HOB501',
                  'amount' => '131.34',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 36
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HON004',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 37
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HON004',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 37
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'HON004',
                  'amount' => '112.70',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 37
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'KAN005',
                  'amount' => '60.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 38
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'KIN012',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 39
            ),
           array( 'clientCodeID' => 8,
                  'reference' => 'KUR004',
                  'amount' => '150.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 40
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'LAD001',
                  'amount' => '26.50',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 41
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MAS050',
                  'amount' => '18.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 42
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MER008',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 43
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MER008',
                  'amount' => '40.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 43
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MOR035',
                  'amount' => '39.82',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 44
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'MUR032',
                  'amount' => '160.90',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 45
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'NAK001',
                  'amount' => '150.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 46
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'NOL003',
                  'amount' => '25.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-30',
                  'fileID' => 8,
                  'payerID' => 47
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PAR011',
                  'amount' => '70.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 48
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PEA005',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 49
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PIN002',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 50
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'POV501',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 51
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'POV501',
                  'amount' => '36.50',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 51
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PRI007',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 52
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'PRI007',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 52
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'RAF008',
                  'amount' => '60.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 53
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'REG005',
                  'amount' => '20.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 54
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'ROD503',
                  'amount' => '140.40',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 55
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'ROU005',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 56
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'ROU005',
                  'amount' => '47.84',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 56
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SAI002',
                  'amount' => '18.26',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 57
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SAL002',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 58
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SAL002',
                  'amount' => '30.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 58
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SHO007',
                  'amount' => '110.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 59
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SIM001',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 60
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'SIM001',
                  'amount' => '100.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 60
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'STA007',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 61
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'STA007',
                  'amount' => '150.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 61
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TAW001',
                  'amount' => '8.60',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 62
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TOW001',
                  'amount' => '103.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-30',
                  'fileID' => 8,
                  'payerID' => 63
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TSH001',
                  'amount' => '187.14',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 64
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'TUR012',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-30',
                  'fileID' => 8,
                  'payerID' => 65
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'UTH001',
                  'amount' => '200.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 66
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'UTH001',
                  'amount' => '20.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 66
           ),
           array( 'clientCodeID' => 8,
                  'reference' => 'VUK001',
                  'amount' => '10.00',
                  'paymentTypeID' => 1,
                  'paymentDate' => '2007-10-31',
                  'fileID' => 8,
                  'payerID' => 67
            )
        );
        
        // Test that the generated payments match what we expected
        $this->assertEquals( $cappedPayments, $controlCappedPayments );
        
    }

}