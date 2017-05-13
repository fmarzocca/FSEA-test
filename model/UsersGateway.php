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
        $dbOrder =  mysqli_real_escape_string($link, $order);
        $sql1 = "SELECT * FROM ".DB_TABLE." ORDER BY $dbOrder ASC";
        $dbres=$link->query($sql1) ;
        if (!$dbres) {
    		printf("Errormessage: %s\n", mysqli_error($link));
		}
        $users = array();
        
        while ($obj = $dbres->fetch_object()) {
            $users[] = $obj;
        }
            /* free result set */
        mysqli_free_result($dbres);

        return $users;
    }
    
    public function selectById($id, $link) {
        $dbId = mysqli_real_escape_string($link,$id);
        
        /* decrypt password to allow user edit */
        $sql1 = "SELECT first, last, email,aes_decrypt(password,'secret') as password FROM ".DB_TABLE." WHERE id=$dbId";
        $dbres = $link->query($sql1);
        if (!$dbres) {
    		printf("Errormessage: %s\n", mysqli_error($link));
		}
        
        return $dbres->fetch_object();
		
    }
    
    public function insert( $first, $last, $email, $password, $link ) {
        
    	$dbFirst = ($first != NULL)?"'".mysqli_real_escape_string($link,$first)."'":'NULL';
        $dbLast = ($last != NULL)?"'".mysqli_real_escape_string($link,$last)."'":'NULL';
        $dbEmail = ($email != NULL)?"'".mysqli_real_escape_string($link,$email)."'":'NULL';
        $dbPassword = ($password != NULL)?"'".mysqli_real_escape_string($link,$password)."'":'NULL';
        
        /* passwords are stored into database with AES encrypting algorythm */
        $sql1 = "INSERT INTO ".DB_TABLE." (first, last, email, password) VALUES ($dbFirst, $dbLast, $dbEmail, AES_ENCRYPT($dbPassword,'secret'))";
        $dbres = $link->query($sql1);
        if (!$dbres) {
    		printf("Errormessage: %s\n", mysqli_error($link));
		}
        
        return mysqli_insert_id();
    }
    
    public function delete($id, $link) {
        $dbId = mysqli_real_escape_string($link,$id);
        $sql1 = "DELETE FROM ".DB_TABLE." WHERE id=$dbId";
        $dbres = $link->query($sql1);
        if (!$dbres) {
    		printf("Errormessage: %s\n", mysqli_error($link));
		}
    }
    
    public function edit($first, $last, $email, $password, $id, $link) {
    	$dbId = mysqli_real_escape_string($link,$id);
    	$dbFirst = ($first != NULL)?"'".mysqli_real_escape_string($link,$first)."'":'NULL';
        $dbLast = ($last != NULL)?"'".mysqli_real_escape_string($link,$last)."'":'NULL';
        $dbEmail = ($email != NULL)?"'".mysqli_real_escape_string($link,$email)."'":'NULL';  
        $dbPassword = ($password != NULL)?"'".mysqli_real_escape_string($link,$password)."'":'NULL';

        /* passwords are stored into database with AES encrypting algorythm */
		$sql1 = "UPDATE ".DB_TABLE." SET first=$dbFirst, last=$dbLast, email=$dbEmail, password=AES_ENCRYPT($dbPassword,'secret') WHERE id=$dbId";
		$dbres = $link->query($sql1);
        if (!$dbres) {
    		printf("Errormessage: %s\n", mysqli_error($link));
		}
    }
    
}

?>
