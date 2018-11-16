<?php 
	// Define variables and initialize with empty values
	$username = $password = "";
	$username_err = $password_err = "";
	$id_here = "";
	if(isset($id)){
		$id_here = "?id=" . $id;
	}
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
	 
	    // Check if username is empty
	    if(empty(trim($_POST["username"]))){
	        $username_err = "Please enter username.";
	    } else{
	        $username = trim($_POST["username"]);
	    }
	    
	    // Check if password is empty
	    if(empty(trim($_POST["password"]))){
	        $password_err = "Please enter your password.";
	    } else{
	        $password = trim($_POST["password"]);
	    }
	    
	    // Validate credentials
	    if(empty($username_err) && empty($password_err)){
	        // Prepare a select statement
	        $sql_conn = "SELECT id, account_type, username, password FROM users WHERE username = ?";
	        
	        if($sql_stmt = mysqli_prepare($link, $sql_conn)){
	            // Bind variables to the prepared statement as parameters
	            mysqli_stmt_bind_param($sql_stmt, "s", $param_username);
	            
	            // Set parameters
	            $param_username = $username;
	            
	            // Attempt to execute the prepared statement
	            if(mysqli_stmt_execute($sql_stmt)){
	                // Store result
	                mysqli_stmt_store_result($sql_stmt);
	                
	                // Check if username exists, if yes then verify password
	                if(mysqli_stmt_num_rows($sql_stmt) == 1){                    
	                    // Bind result variables
	                    mysqli_stmt_bind_result($sql_stmt, $id, $account_type, $username, $hashed_password);
	                    if(mysqli_stmt_fetch($sql_stmt)){
	                        if(password_verify($password, $hashed_password)){
	                            // Password is correct, so start a new session
	                            session_start();
	                            
	                            // Store data in session variables
	                            $_SESSION["loggedin"] = true;
	                            $_SESSION["id"] = $id;
	                            $_SESSION["username"] = $username;                            
	                            $_SESSION["account_type"] = $account_type;                            
	                            // Redirect user to welcome page
	                            header("location: " . htmlspecialchars($_SERVER["PHP_SELF"]) . $id_here);
	                        } else{
	                            // Display an error message if password is not valid
	                            $password_err = "The password you entered was not valid.";
	                        }
	                    }
	                } else{
	                    // Display an error message if username doesn't exist
	                    $username_err = "No account found with that username.";
	                }
	            } else{
	                echo "Oops! Something went wrong. Please try again later.";
	            }
	        }
	        
	        // Close statement
	        mysqli_stmt_close($sql_stmt);
	    }
	    
	    // Close connection
	    mysqli_close($link);
	}
	?>
<div class="header">
	<div class="login">
		<?php
			// Check if the user is already logged in, if yes then redirect him to welcome page
			if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
			 
			echo '
			      <form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . $id_here . '" method="post">
				<div class="form-group form-boep">
			              <label style="color:white;">Username</label>
			              <input type="text" name="username" class="form-control" value="">
			          </div>             
				<div class="form-group form-boep">
			              <label style="color:white;">Password</label>
			              <input type="password" name="password" class="form-control">
			          </div> 			
				<div class="form-group form-boep" style="width:100px;">
					<label>&nbsp;</label>
			              <input type="submit" class="btn btn-default form-control" value="Login" >
			          </div>
				<div class="form-group form-boep" style="width:100px;">
					<label>&nbsp;</label>
			              <a href="register.php" class="btn btn-default form-control"> Register</a>
			          </div>
			      </form>';
			} else {
			echo '
				<div class="form-group form-boep">
			              <label style="padding-top:25px;color:white;font-size:20px;">Welkom, ' . $_SESSION["username"] . '</label>
			          </div>             
				<div class="form-group form-boep" style="width:100px;">
					<label>&nbsp;</label>
			              <a href="logout.php" class="btn btn-default form-control"> Logout</a>
			          </div>';
			}
			
			?>
	</div>
</div>