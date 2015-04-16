<?php

/**
*
*/
class DbConnector
{
	private $userName = 'root';
	private $pass = 'Shivguru096';
	private $db = 'foobank';

	private $conn;

	function __construct()
	{
		$this->conn = new mysqli('localhost', $this->userName, $this->pass, $this->db) or die("Failed to connect to database");
	}

	public function execQuery($query) {
		return mysqli_query($this->conn,$query);
	}
	
	public function getUser($username){
		
		$stmt = $this->conn -> prepare("SELECT user_id, email FROM users where username=?");
		$stmt -> bind_param("s", $username);
		$stmt -> execute();
		$stmt -> bind_result($user_id,$email);
		$stmt -> fetch();
		$result['user_id'] = $user_id;
		$result['email'] = $email;
		return $result;

	}
	
	public function updateResetCode($code,$username){
		
		$stmt = $this->conn -> prepare("Update users SET passreset =? WHERE username =?");
		$stmt -> bind_param("ss", $code,$username);
		$stmt -> execute();

	}
	
	public function getPassResetInfo($resetcode){
		
		$stmt = $this->conn -> prepare("SELECT user_id,passreset,username FROM users where passreset=?");
		$stmt -> bind_param("s", $resetcode);
		$stmt -> execute();
		$stmt -> bind_result($user_id,$passreset,$username);
		$stmt -> fetch();
		$result['user_id'] = $user_id;
		$result['passreset'] = $passreset;
		$result['username'] = $username;
		return $result;

	}
	
	public function updatePassword($newpass,$username){
		
		$stmt = $this->conn -> prepare("UPDATE users SET password =?, passreset = 'NULL' WHERE username=?");
		$stmt -> bind_param("ss", $newpass,$username);
		$stmt -> execute();

	}

	public function closeConnection()
	{
		mysqli_close($this->conn);
	}

}
