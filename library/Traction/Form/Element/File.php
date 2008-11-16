<?php

class Traction_Form_Element_File extends Zend_Form_Element_Xhtml
{

    /**
     * Flag indicator whether or not the ValidFile validator should be added
     * when this file element is required.
     *
     * @var bool
     */
    protected $_autoInsertValidFileValidator = true;

    /**
     * Default view helper to use
     *
     * @var string
     */
    public $helper = 'formFile';

    /**
     * Set flag indicating if the ValidFile validator should be used when this
     * element is required.
     *
     * @param unknown_type $flag
     * @return unknown
     */
    public function setAutoInsertValidFileValidator( $flag )
    {

        $this->_autoInsertValidFileValidator = (bool)$flag;
        return $this;

    }

    /**
     * Get the flag indicating whether or not the ValidFile validator should be
     * used when this element is required.
     *
     * @return unknown
     */
    public function getAutoInsertValidFileValidator( )
    {

        return $this->_autoInsertValidFileValidator;

    }

    /**
     * Test whether or not this element is valid
     *
     */
    public function isValid( $value, $context = null )
    {

        // For a file upload, the data is in the $_FILES array, not $_POST
        $key = $this->getName( );

        if ( ( null === $value )  &&
             ( isset( $_FILES[$key] ) ) )
        {

            $value = $_FILES[$key];

        }

        // Auto insert ValidFile validator
        if ( ( $this->isRequired( ) ) &&
             ( $this->getAutoInsertValidFileValidator( ) ) &&
             ( !$this->getValidator( 'ValidFile' ) ) )
        {

            $validators = $this->getValidators( );
            $validFile = array(
                'validator' => 'ValidFile',
                'breakChainOnFailure' => true
            );
            array_unshift( $validators, $validFile );
            $this->setValidators( $validators );

            // Do not use the NotEmpty validator because the ValidFile replaces
            // it
            $this->setAutoInsertNotEmptyValidator( false );

        }

        return parent::isValid( $value, $context );

    }

}