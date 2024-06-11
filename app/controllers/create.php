<?php

class Create extends Controller {

    public function index() {		
	    $this->view('create/index');
    }

    public function register(){

         if ($_SERVER['REQUEST_METHOD'] == 'POST'){
             $username = $_REQUEST['username'];
             $password = $_REQUEST['password'];
             $confirm_password = $_REQUEST['confirm_password'];

             $validation_errors = [];

             // Check if passwords match
             if ($password !== $confirm_password) {
                 $validation_errors[] = "Passwords do not match.";
             }

             // Password strength checks
             if (strlen($password) < 8 || 
                 !preg_match('/[A-Z]/', $password) || 
                 !preg_match('/[a-z]/', $password) || 
                 !preg_match('/[0-9]/', $password) || 
                 !preg_match('/[\W_]/', $password)) {
                 $validation_errors[] = "Password do not meet the security requirements.";
             }

             // Create a new User instance
             $user = $this->model('User');

             // Check if the username already exists
             if ($user->get_username($username)) {
                 $validation_errors[]  = "Username already exists.";
             }

             // If there are no errors, proceed with user creation
             if (empty($validation_errors)) {
                 // Hash the password
                 $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                 // Create the user in the database
                 $user->create_user($username, $hashed_password);

                 // Redirect to the login page
                 header("Location: /login");
                 exit();

             }else{
                $_SESSION['validation_errors'] = $validation_errors;
                $this->view('create/index');
                exit();

             }

         }



      }
}
