<?php

/**
 *  gateway to the database.
 * 
 */

class UsersGateway {

    public function selectAll($order,$link) {
        if ( !isset($order) ) {
            $order = "first";
        }
        try{
			$query = $link->prepare("SELECT * FROM ".DB_TABLE." ORDER BY $order ASC");
			$query->execute();
		}
		catch(PDOException $e) {
			echo 'Exception: '. $e->getMessage();
        exit;
    	}	
		$users = array();
		$users = $query->fetchAll(PDO::FETCH_OBJ);
        return $users;
    }
    
    public function selectById($id, $link) {
        /* decrypt password to allow user edit */
        try{        
        	$query = $link->prepare("SELECT first, last, email,aes_decrypt(password,'secret') as password FROM ".DB_TABLE." WHERE id=:id");
        	$query->bindParam(':id',$id);
        	$query->execute();
		}
		catch(PDOException $e) {
			echo 'Exception: '. $e->getMessage();
        exit;
    	}	

        return $query->fetch(PDO::FETCH_OBJ);
    }
    
    public function insert( $first, $last, $email, $password, $link ) {
                
        /* passwords are stored into database with AES encrypting algorythm */
		try {
        	$query=$link->prepare("INSERT INTO ".DB_TABLE." (first, last, email, password) VALUES (:first, :last, :email, AES_ENCRYPT(:password,'secret'))");
        	$query->bindParam(':first', $first,PDO::PARAM_STR);
        	$query->bindParam(':last', $last,PDO::PARAM_STR);
        	$query->bindParam(':email', $email,PDO::PARAM_STR);
        	$query->bindParam(':password', $password,PDO::PARAM_STR);        
        	$query->execute();
        	}
		catch(PDOException $e) {
			echo 'Exception: '. $e->getMessage();
        exit;
    	}	
 		return $link->lastInsertId();;        
    }
    
    public function delete($id, $link) {
		try {
			$query=$link->prepare("DELETE FROM ".DB_TABLE." WHERE id=:id");
        	$query->bindParam(':id',$id);
        	$query->execute();
		}
		catch(PDOException $e) {
			echo 'Exception: '. $e->getMessage();
        exit;
    	}	

    }
    
    public function edit($first, $last, $email, $password, $id, $link) {
    	try {
        	/* passwords are stored into database with AES encrypting algorythm */    
        	$query=$link->prepare("UPDATE ".DB_TABLE." SET first=:first, last=:last, email=:email, password=AES_ENCRYPT(:password,'secret') WHERE id=$id");
        	$query->bindParam(':first', $first,PDO::PARAM_STR);
        	$query->bindParam(':last', $last,PDO::PARAM_STR);
        	$query->bindParam(':email', $email,PDO::PARAM_STR);
        	$query->bindParam(':password', $password,PDO::PARAM_STR);        
        	$query->execute();
		}
		catch(PDOException $e) {
			echo 'Exception: '. $e->getMessage();
        exit;
    	}	

    }
    
}

?>
