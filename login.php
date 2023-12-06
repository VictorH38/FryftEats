<?php
	include 'config/config.php';

	session_start();

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ($mysqli->connect_errno) {
	    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	    exit();
	}

	$mysqli->set_charset('utf8');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	    if (isset($_POST['login-username']) && isset($_POST['login-password'])) {
	        processLoginRequest($mysqli);
	    } elseif (isset($_POST['signup-username']) && isset($_POST['signup-password']) && isset($_POST['email'])) {
	        processSignupRequest($mysqli);
	    }
	}

	function processLoginRequest($mysqli) {
	    $username = $_POST['login-username'];
	    $password = $_POST['login-password'];

	    if (validateUser($mysqli, $username, $password)) {
	        $_SESSION['username'] = $username;
	        header('Location: home.php');
	        exit;
	    } else {
	        header('Location: login.php?login-error=true');
	        exit;
	    }
	}

	function processSignupRequest($mysqli) {
	    $email = $_POST['email'];
	    $username = $_POST['signup-username'];
	    $password = $_POST['signup-password'];

	    if (!userExists($mysqli, $username, $email)) {
	        addUserToDatabase($mysqli, $username, $password, $email);
	        $_SESSION['username'] = $username;
	        header('Location: home.php');
	        exit;
	    } else {
	        header('Location: login.php?signup-error=true');
	        exit;
	    }
	}

	function userExists($mysqli, $username, $email) {
	    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
	    $stmt = $mysqli->prepare($query);
	    $stmt->bind_param("ss", $username, $email);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    return $result->num_rows > 0;
	}

	function addUserToDatabase($mysqli, $username, $password, $email) {
	    $query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
	    $stmt = $mysqli->prepare($query);
	    $stmt->bind_param("sss", $username, $password, $email);
	    $stmt->execute();
	}

	function validateUser($mysqli, $username, $password) {
	    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
	    $stmt = $mysqli->prepare($query);
	    $stmt->bind_param("ss", $username, $password);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    return $result->fetch_assoc();
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Log in to FryftEats to personalize your restaurant finding experience! Access your favorite restaurants, save new ones, and explore the best of USC's Fryft Zone dining with just a few clicks.">

		<title>Login</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/styles.css">

	    <!-- Google Fonts -->
	    <link rel="preconnect" href="https://fonts.googleapis.com">
	    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;1,700&display=swap" rel="stylesheet">

	    <!-- Font Awesome -->
		<link href="fonts/font-awesome.min.css" rel="stylesheet">

		<style>
			#content {
				margin: 100px 70px;
				padding: 50px 70px;
				display: flex;
				justify-content: space-between;
				background-color: lightgray;
				border-radius: 30px;
			}

			.row {
			    margin: 0px 0px 30px;
			    width: 100%;
			    display: flex;
			    flex-direction: column;
			}

			.row label {
				margin: 7px 0px;
				color: black;
			}

			.row input {
				height: 30px;
			}

			button {
				cursor: pointer;
			}
		</style>
	</head>

	<body>
		<div id="header">
			<a href="home.php" id="brand-name">FryftEats</a>

			<div id="nav-wrapper">
				<div id="nav">
					<div class="nav-link">
						<a href="home.php" class="active">Home</a>
					</div>

					<div class="nav-link">
						<a href="search.php">Search</a>
					</div>

					<div class="nav-link">
						<a href="contact.php">Contact</a>
					</div>

					<div class="nav-link">
						<a href="login.php">Login</a>
					</div>
				</div>
			</div>
		</div>

		<div id="content">
			<div id="login">
				<p id="login-title">Login</p>
				
				<p id="login-error-message">Invalid username/password</p>
				
				<form id="login-form" action="login.php" method="post">
				    <div class="row">
			            <label for="login-username">Username</label>
			            <input type="text" id="login-username" name="login-username" required>
			        </div>
			        
			        <div class="row">
			            <label for="login-password">Password</label>
			            <input type="password" id="login-password" name="login-password" required>
			        </div>
			        
			        <button type="submit" class="sign-in-button"><span class="fa-sign-in"></span> Sign In</button>
				</form>
			</div>

			<div id="or-text">
				<p>OR</p>
			</div>
			
			<div id="signup">
				<p id="signup-title">Sign Up</p>
				
				<p id="signup-error-message"></p>
				
				<form id="signup-form" action="login.php" method="post">
				    <div class="row">
			            <label for="email">Email</label>
            			<input type="email" id="email" name="email" required>
			        </div>
			        
			        <div class="row">
			            <label for="signup-username">Username</label>
			            <input type="text" id="signup-username" name="signup-username" required>
			        </div>
			        
			         <div class="row">
			            <label for="signup-password">Password</label>
			            <input type="password" id="signup-password" name="signup-password" required>
			        </div>
			        
			        <div class="row">
			            <label for="confirm-password">Confirm Password</label>
			            <input type="password" id="confirm-password" name="confirm-password" required>
			        </div>
			        
			        <div>
			            <input type="checkbox" id="terms" name="terms">
			            <label for="terms">I have read and agree to all terms and conditions of JoesTable.</label>
			        </div>
			        
			        <button type="submit" class="create-account-button"><span class="fa-user-plus"></span> Create Account</button>
				</form>
			</div>
		</div>
		
		<script src="js/scripts.js"></script>
	</body>
</html>
