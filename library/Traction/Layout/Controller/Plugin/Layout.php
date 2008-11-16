<?php

class Traction_Layout_Controller_Plugin_Layout extends Zend_Layout_Controller_Plugin_Layout {

    public function preDispatch( Zend_Controller_Request_Abstract $request )
    {
        
        if ( $request->getControllerName( ) == 'auth' )
        {

            $this->getLayout( )->setLayout( 'basic' );

        }
        else if ( ( $request->getControllerName( ) == 'data' ) &&
                  ( $request->getActionName( ) == 'upload' ) )
        {
            
            $this->getLayout( )->setLayout( 'empty' );
            
        }

    }

}