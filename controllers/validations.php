<?php
class validations {
	public function usernameMatch($user) {
		if (preg_match ( "/^[a-zA-Z][a-zA-Z0-9-_\.]{8,20}$/", $user )) {
			return 1;
		} else {
			return 0;
			;
		}
	}
	public function passwordMatch($password) {
		if (preg_match ( "/^((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20})$/", $password )) {
			return 1;
		} else {
			return 0;
			;
		}
	}
	public function emailMatch($email) {
		$regex = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";
		if (preg_match ( $regex, $email )) {
			return 1;
		} else {
			return 0;
		}
	}
	public function fullnameMatch($name) {
		if (preg_match ( "/^[a-zA-Z0-9[:space:]]{8,20}$/", $name )) {
			return 1;
		} else {
			return 0;
			;
		}
	}
	public function tanMatch($tan) {
		if ((preg_match ( "/^[a-zA-Z0-9]{15}$/", $tan )) || (preg_match ( "/^[a-zA-Z0-9]{10}$/", $tan ))) {
			return 1;
		} else {
			return 0;
			;
		}
	}
	public function accnoMatch($accno) {
		if (preg_match ( "/^[0-9]+$/", $accno )) {
			return 1;
		} else {
			return 0;
			;
		}
	}
	public function amountMatch($amount) {
		if (preg_match ( "/^\d+(?:\.\d+)?$/", $amount )) {
			return 1;
		} else {
			return 0;
			;
		}
	}
	public function descriptionMatch($description) {
		if (preg_match ( "/^[a-zA-Z0-9[:space:]]{3,30}$/", $description )) {
			return 1;
		} else {
			return 0;
			;
		}
	}
	public function codecheck($code) {
		if ((preg_match ( "/^[a-zA-Z0-9]{32}$/", $code ))) {
			return 1;
		} else {
			return 0;
		}
	}
	public function checkFilesize($filename) {
		$size = filesize($filename);
		if ($size>0 && $size<10000000) {
			return true;
		} else {
			return false;
		}
	}
}

?>
