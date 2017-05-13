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
    private $DB = NULL;
    
    private function openDb() {	

		try {
			$DB = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS);
			} catch (PDOException $e) {
			die("Could not connect to database");
			}
				
		$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $DB;	
    }
    
    private function closeDb() {
        $this->DB= NULL;
     }
  
    public function __construct() {
        $this->usersGateway = new UsersGateway();
    }
    
    public function getAllUsers($order) {
        try {
            $link=$this->openDb();
            $res = $this->usersGateway->selectAll($order,$link);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }
    
    public function getUser($id) {
        try {
            $link=$this->openDb();
            $res = $this->usersGateway->selectById($id,$link);
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
            $link=$this->openDb();
            $this->validateUserParams($first, $last, $email, $password);
            $res = $this->usersGateway->insert($first, $last, $email, $password, $link);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }
    
    public function deleteUser( $id ) {
        try {
            $link=$this->openDb();
            $res = $this->usersGateway->delete($id, $link);
            $this->closeDb();
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }
    
    public function editUser ($first, $last, $email, $password,$id) {
        try {
            $link=$this->openDb();
            $this->validateUserParams($first, $last, $email, $password);
            $res = $this->usersGateway->edit($first, $last, $email, $password, $id, $link);
            $this->closeDb();
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
     
    
    }
    
}

?>
