function viewStats( fileName, clientCodeID )
{

    // Get the form that needs to be submitted
    var form = $( document.body ).getElement( 'form' );
    
    // Get the inputs that we want to set
    var fileNameInput = form.getElement( 'input[name=fileName]' );
    var clientCodeIDInput = form.getElement( 'select' );
    
    // Set them to be what we want 
    fileNameInput.value = fileName;
    clientCodeIDInput.value = clientCodeID;
    
    // Submit the form
    form.submit( );

}