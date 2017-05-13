<?php
/**
 * ValidationException class
 *
 * The class handles Exception thrown from the model and catched by the 
 * controller
 *
 * @package    FSEA-test
 * @author     Fabio Marzocca <fabio@marzocca.net>
 */

class ValidationException extends Exception {
    
    private $errors = NULL;
    
    public function __construct($errors) {
        parent::__construct("Validation error!");
        $this->errors = $errors;
    }
    
    
    public function getErrors() {
        return $this->errors;
    }
    
}

?>
