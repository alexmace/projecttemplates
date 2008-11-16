<?php

class Traction_View_Helper_ValueToggle /*extends Zend_View_Helper_Abstract */ {
    
    private $_count = 0;
    
    public function valueToggle( $value1, $value2 )
    {
        
        if ( ( $this->_count % 2 ) == 0 )
        {
            
            $valueToReturn = $value1;
            
        }
        else 
        {
            
            $valueToReturn = $value2;
            
        }
        
        return $valueToReturn;
        
    }    
    
}

?>