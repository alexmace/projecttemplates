<?php

class Traction_Form_FileCap extends Traction_Form {
    
    private $_clientCodeID;
    private $_fileName;
    private $_newCap;
    
    public function __construct( $clientCodeID, $fileName, $newCap )
    {
        
        $this->_clientCodeID = $clientCodeID;
        $this->_fileName = $fileName;
        $this->_newCap = $newCap;
        
        parent::__construct( );
        
    }

    public function init( )
    {

        $this->setAction( '/data/stats' );
        
        // @todo Need to add a filename validator to this
        $this->addElement( 'hidden', 'fileName', array(
            'filters' => array( 
                'StringTrim',
                'StripTags'
            ),
            'required' => true,
            'value' => $this->_fileName
        ) );
        
        $cc = new ClientCodes( );
        
        // @todo Need to Add a Client Code Drop Down here with a client Code
        // validator
        $this->addElement( 'hidden', 'clientCodeID', array( 
            'filters' => array( 
                'Digits'
            ),
            'required' => true,
            'value' => $this->_clientCodeID
        ) );
        
        // Add a cap amount field
        $this->addElement( 'text', 'newCap', array(
            'decorators' => $this->_standardElementDecorator,
            'filters' => array( 
                'StringTrim',
                'StripTags'
            ),
            'label' => 'New Cap Amount',
            'validators' => array( 'Float' ),
            'value' => $this->_newCap
        ) );

        // Need to add the buttons class to this p...
        $button = $this->createElement( 'button', 'submit', array(
            'decorators' => $this->_buttonElementDecorator,
            'label' => 'Get Stats'
        ) );
        $button->setAttrib( 'type', 'submit' );
        $this->addElement( $button );
        
    }

}