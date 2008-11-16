<?php

if ( !defined( 'PHPUnit_MAIN_METHOD' ) )
{

    define( 'PHPUnit_MAIN_METHOD', 'Models_AllTests::main' );

}

require_once( 'PHPUnit/Framework.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'models/ClientCodesTest.php' );
require_once( 'models/FileImportsTest.php' );
require_once( 'models/FilesTest.php' );
require_once( 'models/MultiplePaymentsTest.php' );
require_once( 'models/PayersTest.php' );
require_once( 'models/PaymentsTest.php' );
require_once( 'models/PaymentTypesTest.php' );
require_once( 'models/UsersTest.php' );

class Models_AllTests
{

    public static function main( )
    {

        PHPUnit_TextUI_TestRunner::run( self::suite( ) );

    }

    public static function suite( )
    {

        $suite = new PHPUnit_Framework_TestSuite( 'Traction Models' );

        $suite->addTestSuite( 'ClientCodesTest' );
        $suite->addTestSuite( 'FileImportsTest' );
        $suite->addTestSuite( 'FilesTest' );
//      $suite->addTestSuite( 'FileQueueTest' );
//        $suite->addTestSuite( 'MultiplePaymentsTest' );
//        $suite->addTestSuite( 'PayersTest' );
        $suite->addTestSuite( 'PaymentsTest' );
        $suite->addTestSuite( 'PaymentTypesTest' );
//        $suite->addTestSuite( 'UsersTest' );

        return $suite;

    }

}