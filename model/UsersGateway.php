<?php
/**
 * UsersGateway class
 *
 * The class implements a gateway to the database.
 *
 * @package    FSEA-test
 * @author     Fabio Marzocca <fabio@marzocca.net>
 */
Namespace FSEA\Model;
use \PDO;
class UsersGateway
{

    /**
     * Retrieve the full users list
     *
     * @param string $order The ASC order of the list
     * @param PDO $link A valid PDO object
     * @return the users list
     * @access public
     */
    public function selectAll($order, $link)
    {
        if (!isset($order)) {
            $order = "first";
        }
        try {
            $query = $link->prepare("SELECT * FROM ".DB_TABLE." ORDER BY $order ASC");
            $query->execute();
        } catch (PDOException $e) {
            echo 'Exception: '. $e->getMessage();
            exit;
        }
        $users = array();
        $users = $query->fetchAll(PDO::FETCH_OBJ);
        return $users;
    }
    
    
    /**
    * Retrieve a single user based on ID
    *
    * @param  string  $id the ID
    * @param PDO $link A valid PDO object
    * @return the user record
    */
    public function selectById($id, $link)
    {
        /** decrypt password to allow user edit */
        try {
            $query = $link->prepare("SELECT first, last, email,aes_decrypt(password,'secret') as password FROM ".DB_TABLE." WHERE id=:id");
            $query->bindParam(':id', $id);
            $query->execute();
        } catch (PDOException $e) {
            echo 'Exception: '. $e->getMessage();
            exit;
        }
        return $query->fetch(PDO::FETCH_OBJ);
    }
    
    /**
     * Creates a new user
     *
     * @param string $first, $last, $email, $password
     * @param PDO $link A valid PDO object
     * @return Last used ID
     */
    public function insert($first, $last, $email, $password, $link)
    {
                
        /** passwords are stored into database with AES encrypting algorythm */
        try {
            $query=$link->prepare("INSERT INTO ".DB_TABLE." (first, last, email, password) VALUES (:first, :last, :email, AES_ENCRYPT(:password,'secret'))");
            $query->bindParam(':first', $first, PDO::PARAM_STR);
            $query->bindParam(':last', $last, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':password', $password, PDO::PARAM_STR);
            $query->execute();
        } catch (PDOException $e) {
            echo 'Exception: '. $e->getMessage();
            exit;
        }
        return $link->lastInsertId();
        ;
    }

    /**
     * Deletes single user
     *
     * @param int $id The ID of the user to be deleted
     * @param PDO $link A valid PDO object
     * @access public
     */
    public function delete($id, $link)
    {
        try {
            $query=$link->prepare("DELETE FROM ".DB_TABLE." WHERE id=:id");
            $query->bindParam(':id', $id);
            $query->execute();
        } catch (PDOException $e) {
            echo 'Exception: '. $e->getMessage();
            exit;
        }
    }

    /**
     * Edits user by ID
     *
     * @param string $first, $last, $email, $password
     * @param PDO $link A valid PDO object
     * @access public
     */
    public function edit($first, $last, $email, $password, $id, $link)
    {
        try {
            /** passwords are stored into database with AES encrypting algorythm */
            $query=$link->prepare("UPDATE ".DB_TABLE." SET first=:first, last=:last, email=:email, password=AES_ENCRYPT(:password,'secret') WHERE id=$id");
            $query->bindParam(':first', $first, PDO::PARAM_STR);
            $query->bindParam(':last', $last, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':password', $password, PDO::PARAM_STR);
            $query->execute();
        } catch (PDOException $e) {
            echo 'Exception: '. $e->getMessage();
            exit;
        }
    }
}
