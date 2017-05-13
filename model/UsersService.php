<?php

/*
*
*    Model API used by the Controller
*
*/

require_once 'model/UsersGateway.php';
require_once 'model/ValidationException.php';


class UsersService {
    private $usersGateway    = NULL;
    private $mysqli = NULL;
    
    private function openDb() {
    	$this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS,DB_NAME);
		
		if (mysqli_connect_errno()) {
    		throw new Exception("Failed connection to the database!");
    		}
		return $this->mysqli;
    }
    
    private function closeDb() {
        mysqli_close($this->mysqli);
    }
  
    public function __construct() {
        $this->usersGateway = new UsersGateway();
    }
    
    public function getAllUsers($order) {
        try {
            $this->openDb();
            $res = $this->usersGateway->selectAll($order,$this->mysqli);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }
    
    public function getUser($id) {
        try {
            $this->openDb();
            $res = $this->usersGateway->selectById($id,$this->mysqli);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
        return $this->usersGateway->find($id);
    }
    
    private function validateUserParams( $first, $last, $email, $password ) {
        $errors = array();
        if ( !isset($first) || empty($first) ) {
            $errors[] = 'First Name is required';
        }
        if ( !isset($last) || empty($last) ) {
            $errors[] = 'Last Name is required';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  			$errors[] = 'Invalid email format'; 
		}
        if ( !isset($password) || empty($password) ) {
            $errors[] = 'Password is required';
        }

        if ( empty($errors) ) {
            return;
        }
        throw new ValidationException($errors);
    }
    
    public function createNewUser( $first, $last, $email, $password ) {
        try {
            $this->openDb();
            $this->validateUserParams($first, $last, $email, $password);
            $res = $this->usersGateway->insert($first, $last, $email, $password, $this->mysqli);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }
    
    public function deleteUser( $id ) {
        try {
            $this->openDb();
            $res = $this->usersGateway->delete($id, $this->mysqli);
            $this->closeDb();
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }
    
    public function editUser ($first, $last, $email, $password,$id) {
        try {
            $this->openDb();
            $this->validateUserParams($first, $last, $email, $password);
            $res = $this->usersGateway->edit($first, $last, $email, $password, $id, $this->mysqli);
            $this->closeDb();
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
     
    
    }
    
}

?>
