<?php

require_once 'model/UsersService.php';


class UsersController {
    
    private $usersService = NULL;
    
    public function __construct() {
        $this->usersService = new UsersService();
    }
    
    public function redirect($location) {
        header('Location: '.$location);
    }
    
    public function handleRequest() {
        $op = isset($_GET['op'])?$_GET['op']:NULL;
        try {
            if ( !$op || $op == 'list' ) {
                $this->listUsers();
            } elseif ( $op == 'new' ) {
                $this->saveUser();
            } elseif ( $op == 'delete' ) {
                $this->deleteUser();
            } elseif ( $op == 'edit' ) {
                $this->editUser();
            } else {
                $this->showError("Page not found", "Page for operation ".$op." was not found!");
            }
        } catch ( Exception $e ) {
            // some unknown Exception got through here, use application error page to display it
            $this->showError("Application error", $e->getMessage());
        }
    }
    
    public function listUsers() {
        $orderby = isset($_GET['orderby'])?$_GET['orderby']:NULL;
        $users = $this->usersService->getAllUsers($orderby);
        include 'view/users.php';
    }
    
    public function saveUser() {
       
        $title = 'Add new user';
        
        $first = '';
        $last = '';
        $email = '';
        $password = '';
       
        $errors = array();
        
        if ( isset($_POST['form-submitted']) ) {
            
            $first       = isset($_POST['first']) ?   $_POST['first']  :NULL;
            $last      = isset($_POST['last'])?   $_POST['last'] :NULL;
            $email      = isset($_POST['email'])?   $_POST['email'] :NULL;
            $password    = isset($_POST['password'])? $_POST['password']:NULL;
            
            try {
                $this->usersService->createNewUser($first, $last, $email, $password);
                $this->redirect('index.php');
                return;
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            }
        }
        
        include 'view/user-form.php';
    }
    
    public function deleteUser() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        
        $this->usersService->deleteUser($id);
        
        $this->redirect('index.php');
    }
    
    public function editUser() {
        $title = 'Edit user';
        $errors = array();
        
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $contact = $this->usersService->getUser($id);
        
        if ( isset($_POST['form-submitted']) ) {
            $first       = isset($_POST['first']) ?   $_POST['first']  :NULL;
            $last      = isset($_POST['last'])?   $_POST['last'] :NULL;
            $email      = isset($_POST['email'])?   $_POST['email'] :NULL;
            $password    = isset($_POST['password'])? $_POST['password']:NULL;
            
        try {
                $this->usersService->editUser($first, $last, $email, $password,$id);
                $this->redirect('index.php');
                return;
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            }
        }
        include 'view/edit-user.php';
    }
    
    public function showError($title, $message) {
        include 'view/error.php';
    }
    
    
}
?>
