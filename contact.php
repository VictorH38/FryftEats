<?php
	require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Contact</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/styles.css">

		<style>
			
		</style>
	</head>

	<body>
		<div id="header">
			<a href="home.html" id="brand-name">FryftEats</a>

			<div id="nav-wrapper">
				<div id="nav">
					<div class="nav-link">
						<a href="home.html">Home</a>
					</div>

					<div class="nav-link">
						<a href="search.html">Search</a>
					</div>

					<div class="nav-link">
						<a href="contact.html" class="active">Contact</a>
					</div>
				</div>
			</div>
		</div>

		<div id="contact-form">
	        <h2 id="contact-us">Contact Us</h2>

	        <form action=".php" method="POST">
	            <div class="form-section">
	                <label for="name">Name:</label>
	                <input type="text" id="name" name="name" required>
	            </div>
	            
	            <div class="form-section">
	                <label for="email">Email:</label>
	                <input type="email" id="email" name="email" required>
	            </div>
	            
	            <div class="form-section">
	                <label for="message">Message:</label>
	                <textarea id="message" name="message" rows="4" required></textarea>
	            </div>
	            
	            <button id="submit-button" type="submit">Send</button>
	        </form>
	    </div>

		<script src="js/scripts.js"></script>
	</body>
</html>
