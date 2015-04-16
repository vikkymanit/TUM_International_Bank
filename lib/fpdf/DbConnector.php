<?php

/**
*
*/
class DbConnector
{
	private $userName = 'root';
	private $pass = '';
	private $db = 'foobank';

	private $conn;

	function __construct()
	{
		$this->conn = new mysqli('localhost', $this->userName, $this->pass, $this->db) or die("Failed to connect to database");
	}

	public function execQuery($query) {
		return mysqli_query($this->conn,$query);
	}

	public function closeConnection()
	{
		mysqli_close($this->conn);
	}

}