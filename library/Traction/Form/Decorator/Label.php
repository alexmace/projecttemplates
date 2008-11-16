<?php

class Traction_Form_Decorator_Label extends Zend_Form_Decorator_Label
{

    public function getLabel( )
    {

        $element = $this->getElement( );
        $errors = $element->getMessages( );

        if ( !empty( $errors ) )
        {

            $label = trim( $element->getLabel( ) );
            $label .= ' <strong>'
                    . implode( '</strong><br /><strong>', $errors )
                    . '</strong>';
            $element->setLabel( $label );

        }

        return parent::getLabel( );

    }

}