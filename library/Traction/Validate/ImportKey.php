<?php

require_once( 'Zend/Validate/Abstract.php' );

class Traction_Validate_ImportKey extends Zend_Validate_Abstract
{

    const UNKNOWN = 'Unknown'; // Value: 1; The provided import key is not
                               // known to the system

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::UNKNOWN => 'The provided import key is not known to the system',
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if the import key is present and is valid
     *
     * @param array $value
     * @return boolean
     */
    public function isValid( $value )
    {

        $result = false;

        if ( strlen( $value ) == 64 )
        {

            $fileImport = new FileImports( );

            if ( $fileImport->importKeyExists( $value ) )
            {

                $result = true;

            }
            else
            {

                $this->_error( self::UNKNOWN );

            }

        }

        return $result;

    }

}