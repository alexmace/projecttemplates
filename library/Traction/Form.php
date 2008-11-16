<?php

class Traction_Form extends Zend_Form {

    // Define how form elements will be decorated
    protected $_standardElementDecorator = array(
        'ViewHelper',
        array( 'Label' ),
        array( 'HtmlTag', array( 'tag' => 'p' ) )
    );

    // Define different decorator settings for buttons as we don't want to
    // prepend the button with a label
    protected $_buttonElementDecorator = array(
        'ViewHelper',
        array( 'HtmlTag', array( 'tag'   => 'p',
                                 'class' => 'buttons' ) )
    );

    public function __construct( $options = null ) {

        $this->addElementPrefixPath( 'Traction_Form_Decorator_',
                                     'Traction/Form/Decorator/',
                                     'decorator' );

        parent::__construct( $options );

        $this->setAttrib( 'accept-charset', 'UTF-8' );
        $this->setDecorators( array(
            'FormElements',
            'Form'
        ) );

    }

}