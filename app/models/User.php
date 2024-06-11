<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {
        
    }

    //Function to add attempt Logs to log table
    public function log_attempt($username, $attempt) {
        $db = db_connect();
        $statement = $db->prepare("INSERT INTO log (username, attempt, time) VALUES (:username, :attempt, NOW())");
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->bindParam(':attempt', $attempt, PDO::PARAM_STR);
        $statement->execute();
    }

    //Function to get total failed attempts for a user.
    public function get_failed_attempts($username) {
      $db = db_connect();
      $statement = $db->prepare("SELECT COUNT(*) as attempts FROM log WHERE username = :username AND     attempt = 'bad' AND time > (NOW() - INTERVAL 1 MINUTE)");
      $statement->bindParam(':username', $username, PDO::PARAM_STR);
      $statement->execute();
      return $statement->fetch(PDO::FETCH_ASSOC)['attempts'];
    }
  

    

    // Function to validate user credentials  
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
