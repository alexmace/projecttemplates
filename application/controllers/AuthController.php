<?php

class AuthController extends Zend_Controller_Action
{

    public function indexAction( )
    {

        // We don't want anyone to navigate to 'auth/index' so redirect to the
        // homepage.
        $this->_redirect( '/' );

    }

    public function loginAction( )
    {

        $form = new Traction_Form_Login( );

        // Form has been submitted, so process the contents.
        if ( ( !$this->getRequest( )->isPost( ) ) ||
             ( !$form->isValid( $_POST ) ) )
        {

            $this->view->loginForm = $form;

        }
        else if ( ( $this->getRequest( )->isPost( ) ) &&
                  ( $form->isValid( $_POST ) ) )
        {

            $values = $form->getValues( );

            // Get the database connection out of the registry
            $db = Zend_Registry::get( 'database' );

            // Create the adapter we will use to query the database
            $authAdapter = new Zend_Auth_Adapter_DbTable(  $db,
                                                           'users',
                                                           'email',
                                                           'hash',
                                                           'MD5(?)' );
            $authAdapter->setIdentity( $values['emailaddress'] );
            $authAdapter->setCredential( $values['emailaddress']
                                       . $values['password'] );

            // Get an instance of Zend Auth
            $auth = Zend_Auth::getInstance( );

            // Attempt authentication
            $result = $auth->authenticate( $authAdapter );

            // Did we authenticate successfully?
            if ( $result->isValid( ) )
            {

                // Success, we need to store the user infrormation. Even
                // though their password is not stored and we just use a
                // has to authenticate against, probably a good idea not to
                // store the hash.
                $userData = $authAdapter->getResultRowObject( null, 'hash' );
                $auth->getStorage( )->write( $userData );

                // Redirect to the homepage.
                $this->_redirect( '/' );

            }
            else
            {

                $this->view->failedAuthentication = true;
                $this->view->loginForm = $form;

            }

        }

    }

    public function logoutAction( )
    {

        Zend_Auth::getInstance( )->clearIdentity( );
        $this->_redirect( '/' );

    }

}