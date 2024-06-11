<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {
        
    }

    public function test () {
      $db = db_connect();
      $statement = $db->prepare("select * from users;");
      $statement->execute();
      $rows = $statement->fetch(PDO::FETCH_ASSOC);
      return $rows;
    }

    public function authenticate($username, $password) {
       
      $db = db_connect();
      $statement = $db->prepare("SELECT * FROM users WHERE LOWER(username) = :username");
      $statement->bindParam(':username', strtolower($username), PDO::PARAM_STR);
      $statement->execute();
      $user = $statement->fetch(PDO::FETCH_ASSOC);
		
      if ($user && password_verify($password, $user['password'])) {
          $_SESSION['auth'] = 1;
          $_SESSION['username'] = $username;
          unset($_SESSION['failedAuth']);
          header('Location: /home');
          exit;
      }  
    else {
			if(isset($_SESSION['failedAuth'])) {
				$_SESSION['failedAuth'] ++; //increment
			} else {
				$_SESSION['failedAuth'] = 1;
			}
			header('Location: /login');
			die;
		}
    }

}
