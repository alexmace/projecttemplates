<?php

class Payments extends Zend_Db_Table_Abstract
{

    protected $_name = 'payments';
    
    public function getPaymentsInFile( $fileName, $clientCodeID )
    {
    	
    	$select = $this->select( )->setIntegrityCheck( false );
    	$select->from( array( 'p' => 'payments') )
    	       ->join( array( 'f' => 'files' ), 
    	               'f.fileID=p.fileID', 
    	               array( ) )
    	       ->where( 'f.fileName = ?', $fileName )
    	       ->where( 'f.clientCodeID = ?', $clientCodeID );
    	return $this->fetchAll( $select );
    	
    }
    
    /**
     * This function calculates the actual payments that have been made from the
     * array of payments that have been passed to it.
     *
     * @todo This should also include the amounts of the payments that made up
     *       the reconstructed payments. This will make displaying the 
     *       information easier.
     * @param array $payments
     * @return array
     */
    public function calculateActualPayments( $payments )
    {
        
        
        
        $actualPayments = array( );
        
        foreach( $payments as $payment )
        {
            
            $key = $payment->reference . '-' . $payment->paymentDate;
            
            if ( !isset( $actualPayments[$key] ) )
            {
                
                $actualPayments[$key] = array( 
                    'clientCodeID' => $payment->clientCodeID,
                    'reference' => $payment->reference,
                    'amount' => 0,
                    'paymentTypeID' => $payment->paymentTypeID,
                    'paymentDate' => $payment->paymentDate,
                    'fileID' => $payment->fileID,
                    'payerID' => $payment->payerID,
                    'originalPayments' => array( )
                );
                
            }
            
            $actualPayments[$key]['amount'] += $payment->amount;
            $actualPayments[$key]['originalPayments'][$payment->paymentID] = $payment->amount;
            
            
        }
        
        foreach( $actualPayments as $key => $actualPayment )
        {
            
            $actualPayments[$key]['amount'] = number_format( $actualPayment['amount'], 2, '.', '' );
            
        }
        
        return array_values( $actualPayments );
        
    }
    
    public function generateStats( $payments, $actualPayments, $cappedPayments = array( ) )
    {

        $stats = array( );
        
        // Woork out the difference between the two number of payments.
        $paymentsCount = count( $payments );
        $actualPaymentsCount = count( $actualPayments );
        $cappedPaymentsCount = count( $cappedPayments );
        $difference = $paymentsCount - $actualPaymentsCount;
        $cappedDifference = $cappedPaymentsCount - $actualPaymentsCount;
        
        // Work out the total of the payments
        $total = 0;
        
        foreach( $actualPayments as $payment )
        {
            
            $total += $payment['amount'];
            
        }
        
        $stats['payments'] = array( );
             
        // Add the capped payments into the array.
        foreach( $actualPayments as $payment )
        {
            
            // Where the reference and the date of a capped payment match 
            // the reference and date of the actual payment, add it in as 
            // a capped payment
            foreach( $cappedPayments as $capped )
            {
                
                if ( ( $capped['reference'] == $payment['reference'] ) &&
                     ( $capped['paymentDate'] == $payment['paymentDate'] ) )
                {
                
                    $payment['cappedPayments'][] = $capped['amount'];
                
                }
                
            }
            
            $stats['payments'][] = $payment;
            
        }

        // Assign all the stats that we want to pass back.
        $stats['paymentsCount'] = $paymentsCount;
        $stats['actualPaymentsCount'] = $actualPaymentsCount;
        $stats['cappedPaymentsCount'] = $cappedPaymentsCount;
        $stats['difference'] = $difference;
        $stats['cappedDifference'] = $cappedDifference;
        $stats['percentageIncrease'] = round( ( $difference / $actualPaymentsCount ) * 100, 2 );
        $stats['cappedPercentageIncrease'] = round( ( $cappedDifference / $actualPaymentsCount ) * 100, 2 );
        $stats['total'] = $total;
        
        return $stats;

    }
    
    /**
     * Takes the array of payments passed to it and applies the required cap,
     * returning a new array of payments.
     *
     * @param array $payments
     * @param float $cap
     * @returns array
     */
    public function calculatePaymentsAfterCap( $payments, $cap )
    {

        $cappedPayments = array( );
        
        foreach ( $payments as $payment )
        {
            
            if ( isset( $payment['originalPayments'] ) )
            {
                
                unset( $payment['originalPayments'] );
                
            }

            // Does the payment need to be capped?
            if ( round( $payment['amount'], 2 ) > round( $cap, 2 ) )
            {
                
                // Payment exceeds cap, needs to be split.
                
                // Payment must be split into number of payments the size of
                // the cap, plus one with the remainder if needed.
                $wholePayments = floor( $payment['amount'] / $cap );
                $cappedPayment = $payment;
                $cappedPayment['amount'] = number_format( $cap, 2 );
                
                for( $loop = 0; $loop < $wholePayments; $loop++ )
                {
                    
                    $cappedPayments[] = $cappedPayment;                    
                    
                }
                
                // Now check to see if there is any remaining amount
                $remaining = $payment['amount'] - ( $cap * $wholePayments );
                
                if ( round( $remaining, 2 ) > 0.00 )
                {
                    
                    // There is some remaining, add another payment
                    $cappedPayment['amount'] = number_format( $remaining, 2 );
                    $cappedPayments[] = $cappedPayment;
                    
                }
                
            }
            else 
            {
                
                // Does not need to be capped, append it to the array.
                $cappedPayments[] = $payment;
                
            }
            
        }
        
        return $cappedPayments;

    }

}