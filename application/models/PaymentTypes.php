<?php

class PaymentTypes extends Zend_Db_Table_Abstract
{

    protected $_name = 'paymentTypes';
    
    public function getPaymentTypeIdentifers( )
    {
        
        $select = $this->select( )->setIntegrityCheck( false );
        $select->from( array( 'pt' => 'paymentTypes' ),
                                          array( 'paymentTypeID', 'description' ) )
                                  ->joinLeft( array( 'lookup' => 'paymentTypesCriteriaLookup' ),
                                              'pt.paymentTypeID=lookup.paymentTypeID',
                                              array( ) )
                                  ->joinLeft( array( 'criteria' => 'paymentTypesCriteria' ),
                                              'lookup.criteriaID=criteria.criteriaID',
                                              array( 'fileExtension', 'identifier' ) );
        
        return $this->fetchAll( $select );
        
    }

}