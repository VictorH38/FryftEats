<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Have questions or feedback for FryftEats? Visit our contact page to get in touch! We're here to help USC students enhance their dining experiences and answer any inquiries about our restaurant finder service.">

		<title>Contact</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/styles.css">

	    <!-- Google Fonts -->
	    <link rel="preconnect" href="https://fonts.googleapis.com">
	    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;1,700&display=swap" rel="stylesheet">

	    <!-- Font Awesome -->
		<link href="fonts/font-awesome.min.css" rel="stylesheet">
	</head>

	<body>
		<div id="header">
			<a href="home.php" id="brand-name">FryftEats</a>

			<div id="nav-wrapper">
				<div id="nav">
					<div class="nav-link">
						<a href="home.php">Home</a>
					</div>

					<div class="nav-link">
						<a href="search.php">Search</a>
					</div>

					<div class="nav-link">
						<a href="contact.php" class="active">Contact</a>
					</div>

					<?php
					    if (isset($_SESSION['username'])) {
					        echo '<div class="nav-link"><a href="logout.php">Logout</a></div>';
					    }
					    else {
					        echo '<div class="nav-link"><a href="login.php">Login</a></div>';
					    }
				    ?>
				</div>
			</div>
		</div>

		<div id="contact">
	        <h2 id="contact-us">Contact Us</h2>

	        <form id="contact-form">
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
	            
	            <button id="send-button" type="submit">Send</button>
	        </form>
	    </div>

		<script src="js/scripts.js"></script>

		<script>
			let send_button = document.getElementById('send-button');
			send_button.addEventListener('click', function() {
			    var name = document.getElementById('name').value;
			    var email = document.getElementById('email').value;
			    var message = document.getElementById('message').value;

			    var mailtoLink = 'mailto:victor.h8.2003@gmail.com?subject=FryftEats&body=' + encodeURIComponent('Name: ' + name + '\nEmail: ' + email + '\nMessage: ' + message);

			    window.location.href = mailtoLink;
			});
		</script>
	</body>
</html>
