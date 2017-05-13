<?php
/**
 * UsersController class
 *
 * The class implements the Controller for the application
 *
 * @package    FSEA-test
 * @author     Fabio Marzocca <fabio@marzocca.net>
 */


require_once 'model/UsersService.php';


class UsersController
{
    
    private $usersService = null;
    
    public function __construct()
    {
        $this->usersService = new UsersService();
    }
    
    public function redirect($location)
    {
        header('Location: '.$location);
    }

    /**
     * Handles request from GETs on url
     * and switches to related function
     */
    public function handleRequest()
    {
        $op = isset($_GET['op'])?$_GET['op']:null;
        try {
            if (!$op || $op == 'list') {
                $this->listUsers();
            } elseif ($op == 'new') {
                $this->saveUser();
            } elseif ($op == 'delete') {
                $this->deleteUser();
            } elseif ($op == 'edit') {
                $this->editUser();
            } else {
                $this->showError("Page not found", "Page for operation ".$op." was not found!");
            }
        } catch (Exception $e) {
            /** some unknown Exception got through here, use application error page to display it */
            $this->showError("Application error", $e->getMessage());
        }
    }
    

    /**
     * Asks the model for the full users list
     * and populate the View.
     */
    public function listUsers()
    {
        $orderby = isset($_GET['orderby'])?$_GET['orderby']:null;
        $users = $this->usersService->getAllUsers($orderby);
        include 'view/users.php';
    }
    

    /**
     * Creates a new user in the Model from
     * the form in the View
     */
    public function saveUser()
    {
       
        $title = 'Add new user';
        $first = '';
        $last = '';
        $email = '';
        $password = '';
        $errors = array();
        if (isset($_POST['form-submitted'])) {
            $first       = isset($_POST['first']) ?   $_POST['first']  :null;
            $last      = isset($_POST['last'])?   $_POST['last'] :null;
            $email      = isset($_POST['email'])?   $_POST['email'] :null;
            $password    = isset($_POST['password'])? $_POST['password']:null;
            
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


    /**
     * Instructs the Model to delete the user
     * based on the ID passed by the url
     */
    public function deleteUser()
    {
        $id = isset($_GET['id'])?$_GET['id']:null;
        if (!$id) {
            throw new Exception('Internal error.');
        }
        
        $this->usersService->deleteUser($id);
        
        $this->redirect('index.php');
    }
    

    /**
     * To edit the user, ask to the Model for the specific
     * user based on ID, then populate the edit form in the View
     * On submit, instruct the Model to update the record.
     */
    public function editUser()
    {
        $title = 'Edit user';
        $errors = array();
        $id = isset($_GET['id'])?$_GET['id']:null;
        if (!$id) {
            throw new Exception('Internal error.');
        }
        $contact = $this->usersService->getUser($id);
        
        if (isset($_POST['form-submitted'])) {
            $first       = isset($_POST['first']) ?   $_POST['first']  :null;
            $last      = isset($_POST['last'])?   $_POST['last'] :null;
            $email      = isset($_POST['email'])?   $_POST['email'] :null;
            $password    = isset($_POST['password'])? $_POST['password']:null;
            try {
                $this->usersService->editUser($first, $last, $email, $password, $id);
                $this->redirect('index.php');
                return;
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            }
        }
        include 'view/edit-user.php';
    }
    
    public function showError($title, $message)
    {
        include 'view/error.php';
    }
}
