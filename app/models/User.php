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

    //Function to get last failed attempt for a user.
    public function get_last_failed_attempt($username) {
        $db = db_connect();
        $statement = $db->prepare("SELECT time FROM log WHERE username = :username AND attempt = 'bad' ORDER BY time DESC LIMIT 1");
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC)['time'];
    }

    // Function to validate user credentials  
    public function authenticate($username, $password) {
       
      $db = db_connect();
      $statement = $db->prepare("SELECT * FROM users WHERE LOWER(username) = :username");
      $statement->bindParam(':username', strtolower($username), PDO::PARAM_STR);
      $statement->execute();
      $user = $statement->fetch(PDO::FETCH_ASSOC);

      $failed_attempts = $this->get_failed_attempts($username);
      $last_failed_attempt = $this->get_last_failed_attempt($username);

      if ($failed_attempts >= 3) {
          if (time() - strtotime($last_failed_attempt) < 60) {
            $_SESSION['error_message'] = "Too many failed login attempts.Please try again after 60 seconds.";
              $this->log_attempt($username, 'bad');
              header('Location: /login');
              exit;
          }
      }
		
      if ($user && password_verify($password, $user['password'])) {
          $_SESSION['auth'] = 1;
          $_SESSION['username'] = $username;
          $this->log_attempt($username, 'good');
          unset($_SESSION['failedAuth']);
          header('Location: /home');
          exit;
      }  
      else {
          if (isset($_SESSION['failedAuth'])) {
              $_SESSION['failedAuth']++; // Increment
          } else {
              $_SESSION['failedAuth'] = 1;
          }
          $_SESSION['error_message'] = "Invalid username or password.";
          $this->log_attempt($username, 'bad');
          header('Location: /login');
          exit;
      }
    }

}
