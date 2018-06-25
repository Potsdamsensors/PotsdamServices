<?php 
//Utility functions
function verifyRequiredParams($required_fields, $request_params)
{
    //Assuming there is no error
    $error = false;

    //Error fields are blank
    $error_fields = "";
    
    //Looping through all the parameters
    foreach ($required_fields as $field) {

        //if any requred parameter is missing
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            //error is true
            $error = true;

            //Concatnating the missing parameters in error fields
            $error_fields .= $field . ', ';
        }
    }

    //if there is a parameter missing then error is true
    $response = array();

    if ($error) {
        //Creating response array
       

        //Adding values to response array
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';

        return $response;

        //Stopping the app
        //$app->stop();
    }
    else {
       $response["error"] = false;
    }

    
}
