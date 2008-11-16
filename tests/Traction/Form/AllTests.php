<?php

if ( !defined( 'PHPUnit_MAIN_METHOD' ) )
{

    define( 'PHPUnit_MAIN_METHOD', 'Traction_Form_AllTests::main' );

}

require_once( 'PHPUnit/Framework.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Traction/Form/FileCapTest.php' );
require_once( 'Traction/Form/FileStatsTest.php' );
require_once( 'Traction/Form/LoginTest.php' );
require_once( 'Traction/Form/UploadTest.php' );
require_once( 'Traction/Form/Decorator/AllTests.php' );
require_once( 'Traction/Form/Element/AllTests.php' );

class Traction_Form_AllTests
{

    public static function main( )
    {

        PHPUnit_TextUI_TestRunner::run( self::suite( ) );

    }

    public static function suite( )
    {

        $suite = new PHPUnit_Framework_TestSuite( 'Traction Library Form' );

        $suite->addTest( Traction_Form_Decorator_AllTests::suite( ) );
        $suite->addTest( Traction_Form_Element_AllTests::suite( ) );
        $suite->addTestSuite( 'Traction_Form_FileCapTest' );
        $suite->addTestSuite( 'Traction_Form_FileStatsTest' );
        $suite->addTestSuite( 'Traction_Form_LoginTest' );
        $suite->addTestSuite( 'Traction_Form_UploadTest' );

        return $suite;

    }

}

if ( PHPUnit_MAIN_METHOD == 'Traction_Form_AllTests::main' )
{

    Traction_Form_AllTests::main( );

}