<?php

class Traction_Form_Login extends Traction_Form {

    public function init( )
    {

        $this->setAction( '/auth/login' );
        /*
        $filters = array( '*' => array( 'StringTrim', 'StripTags' ) );
filters?
*/
        $this->addElement( 'text', 'emailaddress', array(
            'decorators' => $this->_standardElementDecorator,
            'label' => 'E-Mail Address',
            'required' => true,
            'validators' => array( 'EmailAddress' )
        ) );

        $this->addElement( 'password', 'password', array(
            'decorators' => $this->_standardElementDecorator,
            'label' => 'Password',
            'required' => true
        ) );

        // Need to add the buttons class to this p...
        $button = $this->createElement( 'button', 'submit', array(
            'decorators' => $this->_buttonElementDecorator,
            'label' => 'Log in'
        ) );
        $button->setAttrib( 'type', 'submit' );
        $this->addElement( $button );

    }

}