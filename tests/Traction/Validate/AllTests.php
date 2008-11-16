<?php

if ( !defined( 'PHPUnit_MAIN_METHOD' ) )
{

    define( 'PHPUnit_MAIN_METHOD', 'Traction_Validate_AllTests::main' );

}

require_once( 'PHPUnit/Framework.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Traction/Validate/ImportKeyTest.php' );
require_once( 'Traction/Validate/ValidFileTest.php' );

class Traction_Validate_AllTests
{

    public static function main( )
    {

        PHPUnit_TextUI_TestRunner::run( self::suite( ) );

    }

    public static function suite( )
    {

        $suite = new PHPUnit_Framework_TestSuite( 'Traction Library Validate' );

        $suite->addTestSuite( 'Traction_Validate_ImportKeyTest' );
        $suite->addTestSuite( 'Traction_Validate_ValidFileTest' );

        return $suite;

    }

}

if ( PHPUnit_MAIN_METHOD == 'Traction_Validate_AllTests::main' )
{

    Traction_Validate_AllTests::main( );

}