<?php
	include 'config/config.php';
	require_once('config/vendor/autoload.php');

	session_start();

	if (isset($_GET["restaurant"])) {
		$client = new \GuzzleHttp\Client();

		$searchUrl = 'https://api.yelp.com/v3/businesses/search?latitude=34.0259&longitude=-118.2853&term=' . $_GET["restaurant"]. '&radius=1500';
		if ($_GET["price"] != "0") {
		    foreach (str_split($_GET["price"]) as $price) {
		        $searchUrl = $searchUrl . '&price=' . $price;
		    }
		}
		$searchUrl = $searchUrl . '&sort_by=' . $_GET["sort_by"] . '&limit=48';

		$response = $client->request('GET', $searchUrl, [
		  'headers' => [
		    'Authorization' => 'Bearer cZCVq1Zbup36-n8lUeq60arieukA2DznuNsxR14tW2OSh3Uo5A8Ovn4yTf2KX1FrqDJkrJT06W8wcHUZO0wnxL75e4X080QWJt8HHi-qWjokL33wEswAWe_N56k6ZXYx',
		    'accept' => 'application/json',
		  ],
		]);

		$data = json_decode($response->getBody(), true);

		foreach ($data["businesses"] as $business) {
	        $name = $business["name"];
	        $address = implode(", ", $business["location"]["display_address"]);
	        $phone = isset($business["display_phone"]) ? $business["display_phone"] : "";
	        $cuisine = count($business["categories"]) > 0 ? $business["categories"][0]["title"] : "";
	        $rating = isset($business["rating"]) ? $business["rating"] : 0;
	        $price = isset($business["price"]) ? $business["price"] : "";
	        $url = explode("?", $business["url"])[0];
	        $image_url = $business["image_url"];

	        // Database connection
	        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	        if ($mysqli->connect_errno) {
    			echo $mysqli->connect_error;
    			exit();
    		}

	        // Check if restaurant exists
	        $checkStmt = $mysqli->prepare("SELECT COUNT(*) FROM restaurants WHERE name = ?");
	        $checkStmt->bind_param("s", $name);
	        $checkStmt->execute();
	        $result = $checkStmt->get_result();
	        $row = $result->fetch_array();
	        if ($row[0] == 0) {
	            // Insert restaurant into database
	            $insertStmt = $mysqli->prepare("INSERT INTO restaurants (name, address, phone_number, cuisine, rating, price, url, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
	            $insertStmt->bind_param("ssssdsss", $name, $address, $phone, $cuisine, $rating, $price, $url, $image_url);
	            $insertStmt->execute();
	        }

	        $checkStmt->close();
	        $mysqli->close();
	    }
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Explore the best eateries around USC with FryftEats! Search and find restaurants by price, rating, and more. Ideal for USC students seeking convenient and delightful dining experiences within the Fryft Zone.">

		<title>Search</title>

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
						<a href="search.php" class="active">Search</a>
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

		<div class="search-container">
			<form action="search.php" method="get">
		        <input type="text" id="search-bar" name="restaurant" placeholder="Search for restaurants..." required>
		        <input type="text" id="price" name="price" value="0">
		        <input type="text" id="sort-by" name="sort_by" value="best_match">
		        <button type="submit" id="search-button">Search</button>
		    </form>

	        <div class="sort-buttons">
	    		<button type="button" id="price-button" class="sort-by-button"><span id="price-sort-icon" class="fa-sort-amount-desc"></span> Price <span id="price-caret-icon" class="fa-caret-down"></span></button>
	    		
	    		<div id="price-options" class="options">
	                <div class="checkbox-group">
	                    <input type="checkbox" id="price1" class="price-option">
	                    <label for="price1">$</label>
	                </div>
	                <div class="checkbox-group">
	                    <input type="checkbox" id="price2" class="price-option">
	                    <label for="price2">$$</label>
	                </div>
	                <div class="checkbox-group">
	                    <input type="checkbox" id="price3" class="price-option">
	                    <label for="price3">$$$</label>
	                </div>
	                <div class="checkbox-group">
	                    <input type="checkbox" id="price4" class="price-option">
	                    <label for="price4">$$$$</label>
	                </div>
	            </div>
	    	</div>

	        <div class="sort-buttons">
	    		<button type="button" id="sort-button" class="sort-by-button"><span id="sort-icon" class="fa-sort-amount-desc"></span> Sort By <span id="caret-icon" class="fa-caret-down"></span></button>
	    		
	    		<div id="sort-options" class="options">
	                <div><button id="sort-best-match" type="button" class="sort-option" onclick="setSortBy('best_match')">Best Match</button></div>
	                <div><button id="sort-rating" type="button" class="sort-option" onclick="setSortBy('rating')">Rating</button></div>
	                <div><button id="sort-review-count" type="button" class="sort-option" onclick="setSortBy('review_count')">Review Count</button></div>
	                <div><button id="sort-distance" type="button" class="sort-option" onclick="setSortBy('distance')">Distance</button></div>
	            </div>
	    	</div>
		</div>

		<div id="search-text">
			<?php
				if (isset($_GET["restaurant"])) {
					echo '<h2>Search results for "' . $_GET["restaurant"] . '"</h2>';
			   	}
			   	else {
			   		echo '<h2>Search to find restaurants</h2>';
			   	}
			?>
		</div>

		<div class="restaurant-grid">
			<?php
				if (isset($_GET["restaurant"])) {
					foreach ($data["businesses"] as $business) {
			            echo '<div class="card">';
			            echo '<img src="'. htmlspecialchars($business["image_url"]) .'" alt="'. htmlspecialchars($business["name"]) .'" class="restaurant-photo"/>';
			            echo '<h3 class="restaurant-name">'. htmlspecialchars($business["name"]) .'</h3>';
			            echo '<p class="restaurant-address">'. htmlspecialchars(implode(", ", $business["location"]["display_address"])) .'</p>';
			            echo '<p class="restaurant-phone">'. htmlspecialchars($business["display_phone"]) .'</p>';
			            echo '<p class="restaurant-rating">';
			            for ($i = 1.0; $i <= htmlspecialchars($business["rating"]); $i += 1.0) {
			                echo '<span class="fa-star star"></span>';
			            }
			            if ($rating - floor(htmlspecialchars($business["rating"])) == 0.5) {
			                echo '<span class="fa-star-half"></span>';
			            }
			            echo '</p>';
			            if (isset($_SESSION['username'])) {
				            echo '<button class="favorites-button" data-restaurant-name="' . htmlspecialchars($business["name"], ENT_QUOTES) . '"><span class="fa-star"></span> Add to Favorites</button>';
				        }
			            echo '</div>';
			        }
			   	}
			?>
		</div>

		<script src="js/scripts.js"></script>
	</body>
</html>
