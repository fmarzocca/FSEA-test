<?php
/**
 * UserService class
 *
 * The class represents the Model API used by the Controller
 *
 * @package    FSEA-test
 * @author     Fabio Marzocca <fabio@marzocca.net>
 */

require_once 'model/UsersGateway.php';
require_once 'model/ValidationException.php';


class UsersService
{
    private $usersGateway    = null;
    private $DB = null;
    
    /**
    * PDO connection to the database
    *
    * @return a PDO object on success
    * @access private
    */
    private function openDb()
    {

        try {
            $DB = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            die("Could not connect to database");
        }
                
        $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $DB;
    }
    
    /**
    * Sets the PDO object to NULL
    *
    * @access private
    */
    private function closeDb()
    {
        $this->DB= null;
    }
  
    /**
    * Instantiates a new gateway
    *
    * @access public
    */
    public function __construct()
    {
        $this->usersGateway = new UsersGateway();
    }
    
    /**
    * Instructs the gateway to retrieve the complete list of users
    *
    * @param  string  $order The ascending order of the list
    * @access public
    */
    public function getAllUsers($order)
    {
        try {
            $link=$this->openDb();
            $res = $this->usersGateway->selectAll($order, $link);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }
    
    /**
    * Retrieve a single user based on ID
    *
    * @param  string  $id the ID
    * @return the user record
    */
    public function getUser($id)
    {
        try {
            $link=$this->openDb();
            $res = $this->usersGateway->selectById($id, $link);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
        return $this->usersGateway->find($id);
    }
    
    /**
     * Validate user parameters before saving the record
     *
     * @param string $first, $last, $email, $password
     * @access private
     */
    private function validateUserParams($first, $last, $email, $password)
    {
        $errors = array();
        if (!isset($first) || empty($first)) {
            $errors[] = 'First Name is required';
        }
        if (!isset($last) || empty($last)) {
            $errors[] = 'Last Name is required';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        if (!isset($password) || empty($password)) {
            $errors[] = 'Password is required';
        }

        if (empty($errors)) {
            return;
        }
        throw new ValidationException($errors);
    }
    
    /**
     * Creates a new user
     *
     * @param string $first, $last, $email, $password
     *
     */
    public function createNewUser($first, $last, $email, $password)
    {
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

    /**
     * Deletes single user
     *
     * @param int $id The ID of the user to be deleted
     * @access public
     */
    public function deleteUser($id)
    {
        try {
            $link=$this->openDb();
            $res = $this->usersGateway->delete($id, $link);
            $this->closeDb();
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }
    
    /**
     * Edits user by ID
     *
     * @param int $id The ID of the user to be edited
     * @access public
     */
    public function editUser($first, $last, $email, $password, $id)
    {
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
