<?php

class DataController extends Zend_Controller_Action {

    public function importAction( )
    {

        // Need to create the form for file import.
        $form = new Traction_Form_Upload( );
        $this->view->uploadForm = $form;

    }

    public function uploadAction( )
    {

        if ( $this->getRequest( )->isPost( ) )
        {

            // Create array to define filters
            $filters = array(
                'importKey' => array( 'StringTrim',
                                      'Alnum' )
            );
    
            // Create array to define validators
            $validators = array(
                'importKey' => array( 'NotEmpty',
                                      new Traction_Validate_ImportKey( ),
                                      array( 'StringLength', 64, 64 ),
                                      'presence' => 'required' ),
                'Filedata' => array( 'NotEmpty',
                                     new Traction_Validate_ValidFile( ) )
            );
    
            // Pass filters, validators and data to the input filter.
            $input = new Zend_Filter_Input( $filters, $validators, 
                                            $this->getRequest( )->getPost( ) );
    
            if ( $input->isValid( ) )
            {
    
                $tempDirectory = sys_get_temp_dir( ) . DIRECTORY_SEPARATOR 
                               . $input->importKey;
    
                // If this fails then we should really throw an exception...
                if ( !file_exists( $tempDirectory ) )
                {
    
                    mkdir( $tempDirectory );
    
                }
    
                // Check to see if it a zip file...
                // First get the extension
                $dotPos = strrpos( $_FILES['Filedata']['name'], '.' );
                
                if ( $dotPos === false )
                {
                    
                    $extension = '';
                    
                }
                else 
                {
                    
                    $extension = substr( $_FILES['Filedata']['name'], $dotPos + 1 );
                    
                }
            
                if ( strtoupper( $extension ) === 'ZIP' )
                {
    
                    // Unpack the file
                    $za = new ZipArchive( );
                    $za->open( $_FILES['Filedata']['tmp_name'] );
                    $za->extractTo( $tempDirectory );
    
                }
                else
                {
    
                    move_uploaded_file( $_FILES['Filedata']['tmp_name'],
                                        $tempDirectory . DIRECTORY_SEPARATOR .
                                        $_FILES['Filedata']['name'] );
    
                }
            
            
                $this->view->message = 'File uploaded successfully!';
    
            }
            else
            {
    
                // Check the response codes are actually correct...
                $this->getResponse( )->setHttpResponseCode( 400 );
                $this->view->message = 'Error: Invalid information provided';
    
            }

        }
            
    }

    public function processAction( )
    {

        // Create filters and validators for the _GET array, although we are
        // only interested in Import Key
        $filters = array(
            'importKey' => array(
                'Alnum',
                'StringTrim'
            )
        );
        $validators = array(
            'importKey' => array(
                'NotEmpty',
                array( 'StringLength', 64, 64 ),
                new Traction_Validate_ImportKey( ),
                'presence' => 'required'
            )
        );

        $input = new Zend_Filter_Input( $filters, $validators, $_POST );

        // Validate the import key
        if ( $input->isValid( ) )
        {
            
            // Get the file import id
            $fileImports = new FileImports( );
            $select = $fileImports->select( )
                                  ->from( array( 'fi' => 'fileImports'),
                                          array( 'fileImportId' ) )
                                  ->where( 'importKey = ?', $input->importKey );
            $result = $fileImports->fetchRow( $select );
            $fileImportId = $result->fileImportId;

            $tempDirectory = sys_get_temp_dir( ) . DIRECTORY_SEPARATOR 
                           . $input->importKey . DIRECTORY_SEPARATOR;
            $filesToImport = array( );

            if ( ( file_exists( $tempDirectory ) ) &&
                 ( is_dir( $tempDirectory ) ) &&
                 ( is_readable( $tempDirectory ) ) )
            {

                // Open the directory holding the files
                $dh = opendir( $tempDirectory );
                
                // Get an instance of the fileQueue model so we can add the file
                // to the queue.
                $fileQueue = new FileQueue( );

                // Loop through the contents
                while ( $file = readdir( $dh ) )
                {

                    // . and .. are always in directories so we want to ignore
                    // them.
                    if ( ( $file != '.' ) &&
                         ( $file != '..' ) )
                    {
                        
                        // Add the details of the file to the queue
                        $data = array( 
                            'fileImportId' => $fileImportId,
                            'filePath' => $tempDirectory . $file
                        );
                        $fileQueue->insert( $data );

                    }

                }

                // Close the directory
                closedir( $dh );           

            }

        }
        else
        {
            
            // Do something more useful here...
            
        }

    }
    
    /**
     * Displays the current status of the files that in the queue for importing
     *
     */
    public function statusAction( )
    {
        
        // Get instance of the file import model
        $fileImports = new FileImports( );
        
        // Get a list of currently running imports and their status
        $this->view->processingImports = $fileImports->getProcessing( );
        
        // Get a list of completed imports
        $this->view->completedImports = $fileImports->getCompleted( );
        
    }
    
    public function statsAction( )
    {

        $fileStatsForm = new Traction_Form_FileStats( );
        
        // Form hasn't been submitted, display the form.
        if ( ( !$this->getRequest( )->isPost( ) ) ||
             ( !$fileStatsForm->isValid( $_POST ) ) )
        {
         
            $this->view->fileStatsForm = $fileStatsForm;
            
            // Get the last ten files imported and pass that to the view
            $files = new Files( );
            $this->view->recentImported = $files->getRecentlyImportedPaymentFiles( 10 );
            
        }
        else if ( ( $this->getRequest( )->isPost( ) ) &&
                  ( $fileStatsForm->isValid( $_POST ) ) )
        {
           
            // Form has been submitted and is valid, process.
            $input = $fileStatsForm->getValues( );
            
            if ( ( !isset( $input['newCap'] ) ) ||
                 ( $input['newCap'] == '' ) )
            {
                
                $input['newCap'] = 150;
                
            }                
            
            $this->view->newCap = $input['newCap'];
            
            // Create the cap form to allow the user to then add for change
            // the cap.
            $fileCapForm = new Traction_Form_FileCap( $input['clientCodeID'],
                                                      $input['fileName'],
                                                      $input['newCap'] );                                                      
            $this->view->fileCapForm = $fileCapForm;
            
            $this->view->fileName = $input['fileName'];
            
            // Get an instance of the payments model
            $payments = new Payments( );
            
            // Get the payments in a file, pass back to stats generator
            $paymentsInFile = $payments->getPaymentsInFile( 
                                $input['fileName'], $input['clientCodeID'] 
                              );
            $actualPayments = $payments->calculateActualPayments( 
                                $paymentsInFile
                              );
            $cappedPayments = array( );
                              
            // If there is a cap, calculate the new payments.
            if ( ( isset( $input['newCap'] ) ) &&
                 ( is_float( floatval( $input['newCap'] ) ) ) )
            {

                $cappedPayments = $payments->calculatePaymentsAfterCap( $actualPayments, $input['newCap'] );
                
            }
                              
            $stats = $payments->generateStats( $paymentsInFile, $actualPayments, $cappedPayments );
            
            $this->view->stats = $stats;
            
        }        
        
    }
    
    public function clearAction( )
    {

        // WHERE statement is required, but we'll just send a blank one because
        // we want to delete everything.
        $where = '';
        
        // Clear out the clientCodes table
        $clientCodes = new ClientCodes( );
        $clientCodes->delete( $where );

        // Clear out the files table
        $files = new Files( );
        $files->delete( $where );

        // Clear out the multiplePayments table
        $multiplePayments = new MultiplePayments( );
        $multiplePayments->delete( $where );

        // Clear out the payers table
        $payers = new Payers( );
        $payers->delete( $where );
        
        // Clear out the payments table
        $payments = new Payments( );
        $payments->delete( $where );
        
    }
    
    /**
     * Server side of the file autocomplete on the file stats generation page.
     * Needs to return a JSON encoded array.
     *
     */
    public function filesearchAction( )
    {
        
        // The variable to search with will be delivered as a POST request
        if ( $this->getRequest( )->isPost( ) )
        {

            // Should be a string with no white space on either end, no html.
            // Should only contain alphanumerics and a decimal place. Trim it,
            // remove any tags and if it contains anything else just don't 
            // return anything.
            $filters = array( 
                'search' => array(
                    'StripTags', 
                    'StringTrim'
                )
            );
            
            // Remember, this will only allow files without spaces in them.
            $validators = array( 
                'search' => array( 
                    array( 'Regex', '/^[a-z0-9]+\.?[a-z0-9]*$/i' )
                )
            );
            
            $input = new Zend_Filter_Input( $filters, $validators, $_POST );

            if ( $input->isValid( ) )
            {
                
                // Search and return.
                $files = new Files( );
                $results = $files->search( $input->search );
                
                $this->_helper->json( $results );
                
            }
            
        }
        
    }

}