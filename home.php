<?php
	include 'config/config.php';

	session_start();

	if (isset($_SESSION['username'])) {
	    $username = $_SESSION['username'];

	    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	    if ($mysqli->connect_errno) {
			echo $mysqli->connect_error;
			exit();
		}

		$mysqli->set_charset('utf8');

		$query = "SELECT * FROM restaurants 
		          JOIN favorites ON restaurants.restaurant_id = favorites.restaurant_id 
		          JOIN users ON favorites.user_id = users.user_id 
		          WHERE users.username = ?";

		if ($stmt = $mysqli->prepare($query)) {
	        $stmt->bind_param("s", $username);
	        $stmt->execute();
	        $results = $stmt->get_result();
	        $stmt->close();
	    }
	    else {
	        echo $mysqli->error;
	    }

		$mysqli->close();
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Discover your favorite dining spots with FryftEats! Exclusively for USC students, our home page showcases your preferred restaurants within the USC Fryft Zone, tailored to your tastes. Log in to see your personalized list.">

		<title>FryftEats</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/styles.css">

	    <!-- Google Fonts -->
	    <link rel="preconnect" href="https://fonts.googleapis.com">
	    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;1,700&display=swap" rel="stylesheet">

	    <!-- Font Awesome -->
		<link href="fonts/font-awesome.min.css" rel="stylesheet">

		<style>
			.restaurant-grid {
			    margin-top: 20px;
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

		<div id="content">
			<h1 id="home-text">Find restaurants within USC Fryft Zone!</h1>

			<div id="background"></div>

			<div id="user-content">
				<?php
				    if (isset($_SESSION['username'])) {
				        echo '<h1>Hi, ' . $_SESSION['username'] . '!</h1>';
				        echo '<h3>Here are your favorite restaurants</h3>';
				    }
				    else {
				        echo '<h3>Login or Sign Up to add restaurants to your favorites list</h3>';
				    }
			    ?>
			</div>

			<div class="restaurant-grid">
				<?php
					if (isset($_SESSION['username'])) {
						while ($row = $results->fetch_assoc()) {
						    $name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
						    $address = htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8');
						    $phone_number = htmlspecialchars($row['phone_number'], ENT_QUOTES, 'UTF-8');
						    $rating = htmlspecialchars($row['rating'], ENT_QUOTES, 'UTF-8');
						    $imageUrl = htmlspecialchars($row['image_url'], ENT_QUOTES, 'UTF-8');

						    if (empty($imageUrl)) {
						        $imageUrl = 'images/no-image.jpeg';
						    }

						    echo '<div class="card">';
						    echo '<img src="' . $imageUrl . '" alt="' . $name . '" class="restaurant-photo"/>';
						    echo '<h3 class="restaurant-name">' . $name . '</h3>';
						    echo '<p class="restaurant-address">' . $address . '</p>';
						    echo '<p class="restaurant-phone">' . $phone_number . '</p>';
						    echo '<p class="restaurant-rating">';
						    for ($i = 1.0; $i <= $rating; $i += 1.0) {
						        echo '<span class="fa-star star"></span>';
						    }
						    if ($rating - floor($rating) == 0.5) {
						        echo '<span class="fa-star-half"></span>';
						    }
						    echo '</p>';
						    echo '<button class="favorites-button" onclick="removeFromFavorites(\'' . $name . '\')"><span class="fa-star"></span> Remove from Favorites</button>';
						    echo '</div>';
						}
					}
				?>
			</div>
		</div>
		
		<script src="js/scripts.js"></script>

		<script>
			function removeFromFavorites(restaurantName) {
			    var favUrl = new URL(window.location.href);
			    favUrl.pathname = '/FryftEats/removeFromFavorites.php';
			    favUrl.search = 'name=' + encodeURIComponent(restaurantName);

			    fetch(favUrl, { method: 'GET' })
			        .then(response => {
			            if (response.ok) {
			                window.location.reload();
			            }
			        })
			        .catch(err => console.error('Error removing from favorites:', err));
			}
		</script>
	</body>
</html>
