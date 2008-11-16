<?php

class Traction_Form_FileStats extends Traction_Form {

    public function init( )
    {

        $this->setAction( '/data/stats' );
        /*
        $filters = array( '*' => array( 'StringTrim', 'StripTags' ) );
filters?
*/
        // @todo Need to add a filename validator to this
        $this->addElement( 'text', 'fileName', array(
            'decorators' => $this->_standardElementDecorator,
            'filters' => array( 
                'StringTrim',
                'StripTags'
            ),
            'label' => 'File Name',
            'required' => true
        ) );
        
        $cc = new ClientCodes( );
        
        // @todo Need to Add a Client Code Drop Down here with a client Code
        // validator
        $this->addElement( 'select', 'clientCodeID', array( 
            'decorators' => $this->_standardElementDecorator,
            'filters' => array( 
                'Digits'
            ),
            'label' => 'Client Code',
            'multiOptions' => $cc->listAsArray( ),
            'required' => true
        ) );
        
        // Add a cap amount field
        $this->addElement( 'text', 'newCap', array(
            'decorators' => $this->_standardElementDecorator,
            'filters' => array( 
                'StringTrim',
                'StripTags'
            ),
            'label' => 'New Cap Amount',
            'validators' => array( 
                'Float',
                new Zend_Validate_GreaterThan( 0 )
            )
        ) );

        // Need to add the buttons class to this p...
        $button = $this->createElement( 'button', 'getStats', array(
            'decorators' => $this->_buttonElementDecorator,
            'label' => 'Get Stats'
        ) );
        $button->setAttrib( 'type', 'submit' );
        $this->addElement( $button );

    }

}