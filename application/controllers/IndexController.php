<?php

require_once( 'Zend/Controller/Action.php' );

class IndexController extends Zend_Controller_Action
{

    public function preDispatch( )
    {

        $auth = Zend_Auth::getInstance( );

        if ( !$auth->hasIdentity( ) )
        {

            $this->_redirect( '/auth/login' );

        }

    }

    public function indexAction( )
    {


    }

}
