<?php

class ClientCodes extends Zend_Db_Table_Abstract
{

    protected $_name = 'clientCodes';

    public function getStats( )
    {
    	
    	$stats = array( );

        $select = $this->select( )->setIntegrityCheck( false );
        $select->from( array( 'cc' => 'clientCodes' ),
                       array( 'clientCodeID', 'clientCode' ) )
               ->join( array( 'p' => 'payments' ),
                       'cc.clientCodeID=p.clientCodeID',
                       array( 'count' => 'COUNT( paymentID )',
                              'paymentsTotal' => 'SUM( amount )' ) )
               ->group( 'clientCode' );

        $results = $this->fetchAll( $select );

        foreach( $results as $row )
        {

            $select = $this->select( )->setIntegrityCheck( false );
            $select->from( array( 'types' => 'paymentTypes' ),
                           array( 'count' => 'COUNT( types.paymentTypeID )',
                                  'description' ) )
                   ->join( array( 'p' => 'payments' ),
                           'p.paymentTypeID=types.paymentTypeID',
                           array( ) )
                   ->where( 'clientCodeID = ?', $row->clientCodeID )
                   ->group( 'types.paymentTypeID' );

            $paymentTypesResults = $this->fetchAll( $select );
            
            $postOfficeCount = 0;
            $payPointCount = 0;
            $terminalDebitCardCount = 0;
            $terminalCreditCardCount = 0;
            $terminalCashCount = 0;
            $payZoneCount = 0;
            $directDebitCount = 0;
            $ePayCount = 0;
            $chequeCount = 0;
            $cashCount = 0;
            $woolworthsCount = 0;
            
            foreach( $paymentTypesResults as $paymentTypeCount )
            {
            	
            	if ( $paymentTypeCount->description == 'Post Office' )
            	{
            		
            		$postOfficeCount = $paymentTypeCount->count;
            		 
            	}
            	else if ( $paymentTypeCount->description == 'PayPoint' )
            	{
            		
            		$payPointCount = $paymentTypeCount->count;
            		
            	}
            	else if ( $paymentTypeCount->description == 'Terminal Debit Card' )
            	{

            		$terminalDebitCardCount = $paymentTypeCount->count;
            		
            	}
            	else if ( $paymentTypeCount->description == 'Terminal Credit Card' )
            	{
            		
            		$terminalCreditCardCount = $paymentTypeCount->count;
            		
            	}
            	else if ( $paymentTypeCount->description == 'Terminal Cash' )
            	{
            		
            		$terminalCashCount = $paymentTypeCount->count;
            		
            	}
            	else if ( $paymentTypeCount->description == 'PayZone' )
            	{
            		
            		$payZoneCount = $paymentTypeCount->count;
            		
            	}
            	else if ( $paymentTypeCount->description == 'Direct Debit' )
            	{
            		
            		$directDebitCount = $paymentTypeCount->count;
            		
            	}
            	else if ( $paymentTypeCount->description == 'EPay' )
            	{
            		
            		$ePayCount = $paymentTypeCount->count;
            		
            	}
            	else if ( $paymentTypeCount->description == 'Cheque' )
            	{
            		
            		$chequeCount = $paymentTypeCount->count;
            		
            	}
            	else if ( $paymentTypeCount->description == 'Cash' )
            	{

            		$cashCount = $paymentTypeCount->count;
            		
            	}
            	else if ( $paymentTypeCount->description == 'Woolworths' )
            	{
            		
            		$woolworthsCount = $paymentTypeCount->count;
            		
            	}            	
            	
            }
            
            $stats[] = array( 
                'clientCode' => $row->clientCode,
                'count' => $row->count,
                'paymentsTotal' => $row->paymentsTotal,
                'postOfficeCount' => $postOfficeCount,
                'payPointCount' => $payPointCount,
                'terminalDebitCardCount' => $terminalDebitCardCount,
                'terminalCreditCardCount' => $terminalCreditCardCount,
                'terminalCashCount' => $terminalCashCount,
                'payZoneCount' => $payZoneCount,
                'directDebitCount' => $directDebitCount,
                'ePayCount' => $ePayCount,
                'chequeCount' => $chequeCount,
                'cashCount' => $cashCount,
                'woolworthsCount' => $woolworthsCount
            );

        }

        return $stats;

    }
    
    public function listAsArray( )
    {

        $results = $this->fetchAll( );
        
        $list = array( );
        
        foreach( $results as $row )
        {

            $list[$row->clientCodeID] = $row->clientCode;
            
        }
        
        return $list;
        
    }

}