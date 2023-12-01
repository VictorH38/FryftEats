<?php
	require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>FryftEats</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/styles.css">

		<style>
			.remove-button {
				padding: 10px 20px;
				background-color: red;
				color: white;
				border: none;
				border-radius: 5px;
				cursor: pointer;
				font-weight: bold;
				transition: all 0.3s ease;
			}

			.remove-button:hover {
			 	background-color: #a33f2f;
			}
		</style>
	</head>

	<body>
		<div id="header">
			<a href="home.html" id="brand-name">FryftEats</a>

			<div id="nav-wrapper">
				<div id="nav">
					<div class="nav-link">
						<a href="home.html" class="active">Home</a>
					</div>

					<div class="nav-link">
						<a href="search.html">Search</a>
					</div>

					<div class="nav-link">
						<a href="contact.html">Contact</a>
					</div>
				</div>
			</div>
		</div>

		<div id="content">
			<div id="background"></div>

			<div id="user-content">
				<h1>Hi, User!</h1>

				<h3>Here are your favorites restaurants</h3>
			</div>

			<div class="restaurant-grid">
			    <div class="card">
			        <img src="images/chc.jpeg" alt="California Hot Chicken" class="restaurant-photo"/>
			        <h3 class="restaurant-name">California Hot Chicken</h3>
			        <p class="restaurant-address">3335 S Figueroa St Los Angeles, CA 90007</p>
			        <p class="restaurant-rating">4 Stars</p>
			        <button class="remove-button">Remove</button>
			    </div>

			    <div class="card">
			        <img src="images/subway.jpeg" alt="Subway" class="restaurant-photo"/>
			        <h3 class="restaurant-name">Subway</h3>
			        <p class="restaurant-address">2805 S Figueroa St Los Angeles, CA 90007</p>
			        <p class="restaurant-rating">2.9 Stars</p>
			        <button class="remove-button">Remove</button>
			    </div>

			    <div class="card">
			        <img src="images/panda.jpeg" alt="Panda Express" class="restaurant-photo"/>
			        <h3 class="restaurant-name">Panda Express</h3>
			        <p class="restaurant-address">3607 Trousdale Pkwy Los Angeles, CA 90089</p>
			        <p class="restaurant-rating">2.2 Stars</p>
			        <button class="remove-button">Remove</button>
			    </div>

			    <div class="card">
			        <img src="images/spudnuts.jpeg" alt="Spudnuts Donuts" class="restaurant-photo"/>
			        <h3 class="restaurant-name">Spudnuts Donuts</h3>
			        <p class="restaurant-address">3001 S Figueroa St Los Angeles, CA 90007</p>
			        <p class="restaurant-rating">3.8 Stars</p>
			        <button class="remove-button">Remove</button>
			    </div>

			    <div class="card">
			        <img src="images/kobunga.jpeg" alt="KOBUNGA" class="restaurant-photo"/>
			        <h3 class="restaurant-name">KOBUNGA</h3>
			        <p class="restaurant-address">929 W Jefferson Blvd Ste 1610 Los Angeles, CA 90089</p>
			        <p class="restaurant-rating">4.3 Stars</p>
			        <button class="remove-button">Remove</button>
			    </div>
			</div>
		</div>
		
		<script src="js/scripts.js"></script>
	</body>
</html>
