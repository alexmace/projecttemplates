<?php

class Files extends Zend_Db_Table_Abstract
{

    protected $_name = 'files';
    
    /**
     * Checks to see if the given file has a hash that is already in the 
     * database.
     *
     * @param string $fileName
     * @return boolean
     */
    public function checkIfFilePresent( $fileName ) 
    {
    
        // Create a hash from the file 
        $hash = sha1_file( $fileName );
        
        // Query the database for the hash
        $select = $this->select( )->where( 'hash = ?', $hash );
        $results = $this->fetchAll( $select );
        
        $exists = false;
        
        if ( count( $results ) > 0 )
        {
            
            $exists = true;
            
        }
        
        return $exists;
        
    }
    
    /**
     * Searches for a file in the database that matches the given file name. 
     * Returns an array containing the file name and the client code that it 
     * is associated with.
     *
     * @param string $fileName
     * @return array
     */
    public function search( $fileName )
    {
        
        $select = $this->select( )
                       ->from( array( 'f' => 'files' ),
                               array( 'clientCodeID', 'fileName' ) )
                       ->where( 'fileName LIKE ?', $fileName . '%' );
        return $this->fetchAll( $select )->toArray( );
        
    }
    
    public function getRecentlyImportedPaymentFiles( $number )
    {
        
        $select = $this->select( )
                       ->distinct( )
                       ->from( array( 'f' => 'files'),
                               array( 'clientCodeID', 'fileName' ) )
                       ->join( array( 'p' => 'payments' ), 
                               'f.fileID=p.fileID', 
                               array( ) )
                       ->where( 'f.fileID=p.fileID' )
                       ->order( 'imported DESC' )
                       ->limit( $number, 0 );

        return $this->fetchAll( $select );
    
    }

}