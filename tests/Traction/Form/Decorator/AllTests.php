<?php

if ( !defined( 'PHPUnit_MAIN_METHOD' ) )
{

    define( 'PHPUnit_MAIN_METHOD', 'Traction_Form_Decorator_AllTests::main' );

}

require_once( 'PHPUnit/Framework.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Traction/Form/Decorator/LabelErrorTest.php' );

class Traction_Form_Decorator_AllTests
{

    public static function main( )
    {

        PHPUnit_TextUI_TestRunner::run( self::suite( ) );

    }

    public static function suite( )
    {

        $suite = new PHPUnit_Framework_TestSuite( 'Traction Library Form Decorator' );

        $suite->addTestSuite( 'Traction_Form_Decorator_LabelErrorTest' );

        return $suite;

    }

}

if ( PHPUnit_MAIN_METHOD == 'Traction_Form_Decorator_AllTests::main' )
{

    Traction_Form_Decorator_AllTests::main( );

}