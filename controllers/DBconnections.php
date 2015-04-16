<?php
class DBconnections {

 

    public function login( $id,$pass,$type )
    {
        $mysqli = new mysqli('localhost', 'root', 'Shivguru096', 'foobank');

   if(mysqli_connect_errno()) {
      echo "Connection Failed: " . mysqli_connect_errno();
      exit();
   }

   /* Create a prepared statement */
   if($stmt = $mysqli -> prepare("SELECT user_id,role FROM users WHERE username=? AND password=? AND is_active = 1")) {
       
       /* Bind parameters
         s - string, b - blob, i - int, etc */
      $stmt -> bind_param("ss", $id, $pass);
      /* Execute it */
      $stmt -> execute();
       /* store result */
    $stmt->store_result();
       if($stmt->num_rows!=1)
       {
           return 'fail';
       }
      /* Bind results */
      $stmt -> bind_result($userid,$role);

      /* Fetch the value */
        
      while($stmt->fetch())
      {
          
           if($role==$type)
           {
			   session_start();
			   $_SESSION['uid']=$userid;
               if($type=='user')
               {
               return 'userlogin';
               }
               elseif($type=='employee')
               {
                   return 'employeelogin';
               }
               elseif($type=='admin')
               {
                   return 'adminlogin';
               }
           }
           else
           {
                  return 'roleerror';
           }
          
          
      }
      /* Close statement */
      $stmt -> close();
   }

   /* Close connection */
   $mysqli -> close();
        

    }
    
    public function isUserExist( $id )
    {
    	$mysqli = new mysqli('localhost', 'root', 'Shivguru096', 'foobank');
    	
    	if(mysqli_connect_errno()) {
    		echo "Connection Failed: " . mysqli_connect_errno();
    		exit();
    	}
    	
    	/* Create a prepared statement */
    	if($stmt = $mysqli -> prepare("SELECT * FROM users WHERE username=?")) {
    		 
    		/* Bind parameters
    		 s - string, b - blob, i - int, etc */
    		$stmt -> bind_param("s", $id);
    	
    		/* Execute it */
    		$stmt -> execute();
    		/* store result */
    		$stmt->store_result();
    	
    	if($stmt->num_rows==1)
    		{
    			return 'exist';
    		}
    		else
    		{
    			return 'notexist';
    		}
    		/* Close statement */
    		$stmt -> close();
    	}
    }
    
    public function insertUser( $username, $fullname,$pass,$email,$select,$timestamp,$user_id,$tranauth)
    {
    	$mysqli = new mysqli('localhost', 'root', 'Shivguru096', 'foobank');
    	 
    	if(mysqli_connect_errno()) {
    		echo "Connection Failed: " . mysqli_connect_errno();
    		exit();
    	}
    	 
    	/* Create a prepared statement */
    	if($stmt = $mysqli -> prepare("INSERT INTO users(username,fullname,password,email,role,tranauth,registration_date,user_id,is_active) VALUES (?,?,?,?,?,?,?,?,0) ;")) 
       {
    		 
    		/* Bind parameters
    		 s - string, b - blob, i - int, etc */
    		$stmt -> bind_param("sssssssi", $username, $fullname,$pass,$email,$select,$tranauth,$timestamp,$user_id);
    		/* Execute it */
    		
    		if(!$stmt -> execute()){
    			echo "Insert error: ";
    			exit();
    		}
    		/* Close statement */
    		$stmt -> close();
    		return 1;
    	}
    }
    
    public function isAccountExist($accno)
    {
    	$mysqli = new mysqli('localhost', 'root', 'Shivguru096', 'foobank');
    	
    	if(mysqli_connect_errno()) {
    		echo "Connection Failed: " . mysqli_connect_errno();
    		exit();
    	}
    	/* Create a prepared statement */
    	if($stmt = $mysqli -> prepare("SELECT * FROM accounts WHERE account_num=?")) 
    	{
    		 
    		/* Bind parameters
    		 s - string, b - blob, i - int, etc */
    		$stmt -> bind_param("s", $accno);
    	
    		/* Execute it */
    		$stmt -> execute();
    		/* store result */
    		$stmt->store_result();
    		if($stmt->num_rows==1)
    		{
    			return 1;
    		}
    		else
    		{
    			return 0;
    		}
    		/* Close statement */
    		$stmt -> close();
    		
    	}
    }

	public function getFailedAttempts($username){
		$mysqli = new mysqli('localhost', 'root', 'Shivguru096', 'foobank');
    	
    	if(mysqli_connect_errno()) {
    		echo "Connection Failed: " . mysqli_connect_errno();
    		exit();
    	}
		$query = "Select failed_attempt_count from users where username = '$username'";
		$result = mysqli_query($mysqli, $query); 
		$result = mysqli_fetch_assoc($result);
		$mysqli -> close(); 
		return $result['failed_attempt_count'];
	}
	
	public function setFailedAttempts($username){
		$mysqli = new mysqli('localhost', 'root', 'Shivguru096', 'foobank');
    	
    	if(mysqli_connect_errno()) {
    		echo "Connection Failed: " . mysqli_connect_errno();
    		exit();
    	}
    	$count = $this->getFailedAttempts($username); 
    	$count = $count + 1;
		$query = "Update users set failed_attempt_count = '$count' where username = '$username'";
		$result = mysqli_query($mysqli, $query);
		$mysqli -> close();
	}
	
	public function resetFailedAttempts($username){
		$mysqli = new mysqli('localhost', 'root', 'Shivguru096', 'foobank');
    	
    	if(mysqli_connect_errno()) {
    		echo "Connection Failed: " . mysqli_connect_errno();
    		exit();
    	}

		$query = "Update users set failed_attempt_count = 0 where username = '$username'";
		$result = mysqli_query($mysqli, $query);
		$mysqli -> close();
	}
}

?>
