<?php

if ( !defined( 'PHPUnit_MAIN_METHOD' ) )
{

    define( 'PHPUnit_MAIN_METHOD', 'Traction_AllTests::main' );

}

require_once( 'PHPUnit/Framework.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Traction/AclTest.php' );
require_once( 'Traction/FormTest.php' );
require_once( 'Traction/Controller/AllTests.php' );
require_once( 'Traction/Form/AllTests.php' );
require_once( 'Traction/Layout/AllTests.php' );
require_once( 'Traction/Validate/AllTests.php' );

class Traction_AllTests
{

    public static function main( )
    {

        PHPUnit_TextUI_TestRunner::run( self::suite( ) );

    }

    public static function suite( )
    {

        $suite = new PHPUnit_Framework_TestSuite( 'Traction Library' );

        $suite->addTest( Traction_Controller_AllTests::suite( ) );
        $suite->addTest( Traction_Form_AllTests::suite( ) );
        $suite->addTest( Traction_Layout_AllTests::suite( ) );
        $suite->addTest( Traction_Validate_AllTests::suite( ) );
        $suite->addTestSuite( 'Traction_AclTest' );
        $suite->addTestSuite( 'Traction_FormTest' );

        return $suite;

    }

}

if ( PHPUnit_MAIN_METHOD == 'Traction_AllTests::main' )
{

    Traction_AllTests::main( );

}
