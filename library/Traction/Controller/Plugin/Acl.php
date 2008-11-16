<?php

class Traction_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{

    protected $_auth = null;

    protected $_acl = null;

    public function __construct( Zend_Auth $auth, Zend_Acl $acl )
    {

        $this->_auth = $auth;
        $this->_acl = $acl;

    }

    public function preDispatch( Zend_Controller_Request_Abstract $request )
    {

        // Check to see if the user has an identity
        if ( $this->_auth->hasIdentity( ) )
        {

            // Check user against the database to see what role they have
            $role = $this->_auth->getIdentity( )->role;

        }
        else
        {

            $role = 'guest';

        }

        $resource = $request->getControllerName( );
        $permission = $request->getActionName( );

        if ( !$this->_acl->has( $resource ) )
        {

            $resource = null;

        }

        if ( !$this->_acl->isAllowed( $role, $resource, $permission ) )
        {

            // Logged in, but don't have access to this resource. Send to
            // homepage.
            if ( $this->_auth->hasIdentity( ) )
            {

                $request->setControllerName( 'index' );
                $request->setActionName( 'index' );

            }
            else
            {

                // Not logged in, must send them to the login page
                $request->setControllerName( 'auth' );
                $request->setActionName( 'login' );

            }

        }

    }

}