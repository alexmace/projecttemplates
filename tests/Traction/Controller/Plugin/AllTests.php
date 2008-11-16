<?php

if ( !defined( 'PHPUnit_MAIN_METHOD' ) )
{

    define( 'PHPUnit_MAIN_METHOD', 'Traction_Controller_Plugin_AllTests::main' );

}

require_once( 'PHPUnit/Framework.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Traction/Controller/Plugin/AclTest.php' );

class Traction_Controller_Plugin_AllTests
{

    public static function main( )
    {

        PHPUnit_TextUI_TestRunner::run( self::suite( ) );

    }

    public static function suite( )
    {

        $suite = new PHPUnit_Framework_TestSuite( 'Traction Library Controller Plugin' );

        $suite->addTestSuite( 'Traction_Controller_Plugin_AclTest' );

        return $suite;

    }

}

if ( PHPUnit_MAIN_METHOD == 'Traction_Controller_Plugin_AllTests::main' )
{

    Traction_Controller_Plugin_AllTests::main( );

}