<?php

class Traction_Form_Upload extends Traction_Form {

    public function init( )
    {

        $this->addElementPrefixPath( 'Traction', 'Traction/' );
        $this->setAction( '/data/upload' );
        $this->setAttrib( 'enctype', 'multipart/form-data' );
        $this->setAttrib( 'id', 'upload' );

        $userId = Zend_Auth::getInstance( )->getIdentity( )->userid;
        $fileImport = new FileImports( );

        $this->addElement( 'hidden', 'importKey', array(
            'decorators' => $this->_standardElementDecorator,
            'filters' => array( 'StringTrim',
                                'Alnum' ),
            'required' => true,
            'validators' => array( 'ImportKey',
                                   array( 'StringLength', 64, 64 ) ),
            'value' => $fileImport->generateImportKey( $userId )
        ) );

        $file = new Traction_Form_Element_File( 'file', array(
            'id' => 'fileToUpload',
            'decorators' => $this->_standardElementDecorator,
            'label' => 'Select File: ',
            'name' => 'Filedata',
            'required' => true
        ) );
        $this->addElement( $file );

        // Need to add the buttons class to this p...
        $button = $this->createElement( 'button', 'upload', array(
            'decorators' => $this->_buttonElementDecorator,
            'label' => 'Start Upload'
        ) );
        $button->setAttrib( 'type', 'submit' );
        $this->addElement( $button );

    }

}