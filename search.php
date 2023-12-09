<?php
	include 'config/config.php';

	session_start();

	if (isset($_GET["restaurant"])) {
		$encodedRestaurant = urlencode($_GET["restaurant"]);
		$apiUrl = 'https://api.yelp.com/v3/businesses/search?latitude=34.0259&longitude=-118.2853&term=' . $encodedRestaurant . '&radius=1500';
		if ($_GET["price"] != "0") {
		    foreach (str_split($_GET["price"]) as $price) {
		        $apiUrl = $apiUrl . '&price=' . $price;
		    }
		}
		$apiUrl = $apiUrl . '&sort_by=' . $_GET["sort_by"] . '&limit=48';

		$ch = curl_init($apiUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'accept: application/json',
			'Authorization: Bearer cZCVq1Zbup36-n8lUeq60arieukA2DznuNsxR14tW2OSh3Uo5A8Ovn4yTf2KX1FrqDJkrJT06W8wcHUZO0wnxL75e4X080QWJt8HHi-qWjokL33wEswAWe_N56k6ZXYx',
		));
		
		$response = curl_exec($ch);
		curl_close($ch);
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
			
		</div>

		<script src="js/scripts.js"></script>

		<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function() {
			    let params = new URLSearchParams(window.location.search);
			    let restaurant = params.get("restaurant");
			    let price = params.get("price");
			    let sortBy = params.get("sort_by");

			    if (restaurant) {
			        fetchRestaurants(restaurant, price, sortBy);
			    }
			});

			function fetchRestaurants(restaurant, price, sortBy) {
			    let data = <?php echo $response ?>;
			    updateRestaurantGrid(data.businesses);
			    updateDatabase(data.businesses);
			}

			function updateRestaurantGrid(businesses) {
				const grid = document.querySelector('.restaurant-grid');
			    grid.innerHTML = '';

			    businesses.forEach(business => {
			        const card = document.createElement('div');
			        card.className = 'card';

			        const img = document.createElement('img');
			        img.src = business.image_url;
			        img.alt = business.name;
			        img.className = 'restaurant-photo';
			        card.appendChild(img);

			        const name = document.createElement('h3');
			        name.className = 'restaurant-name';
			        name.textContent = business.name;
			        card.appendChild(name);

			        const address = document.createElement('p');
			        address.className = 'restaurant-address';
			        address.textContent = business.location.display_address.join(', ');
			        card.appendChild(address);

			        const phone = document.createElement('p');
			        phone.className = 'restaurant-phone';
			        phone.textContent = business.display_phone;
			        card.appendChild(phone);

			        const rating = document.createElement('p');
			        rating.className = 'restaurant-rating';
			        for (let i = 1; i <= Math.floor(business.rating); i++) {
			            const star = document.createElement('span');
			            star.className = 'fa-star star';
			            rating.appendChild(star);
			        }
			        if (business.rating - Math.floor(business.rating) === 0.5) {
			            const halfStar = document.createElement('span');
			            halfStar.className = 'fa-star-half';
			            rating.appendChild(halfStar);
			        }
			        card.appendChild(rating);

			        var userIsLoggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;
			        if (userIsLoggedIn) {
			        	const favoritesButton = document.createElement('button');
			       		favoritesButton.className = 'favorites-button';
			        	favoritesButton.setAttribute('data-restaurant-name', business.name);
			        	favoritesButton.innerHTML = '<span class="fa-star"></span> Add to Favorites';
			        	checkInFavorites(business.name, favoritesButton);
			        	card.appendChild(favoritesButton);
			        }

			        grid.appendChild(card);
			    });
			}

			function updateDatabase(businesses) {
			    var updateUrl = new URL(window.location.href);
			    updateUrl.pathname = '/FryftEats/addRestaurantsToDB.php';

			    fetch(updateUrl, {
			        method: 'POST',
			        headers: {
			            'Content-Type': 'application/json'
			        },
			        body: JSON.stringify({businesses: businesses})
			    })
			    .catch(error => console.error('Error:', error));
			}

			function checkInFavorites(restaurantName, button) {
			    var checkUrl = new URL(window.location.href);
			    checkUrl.pathname = '/FryftEats/checkInFavorites.php';
			    checkUrl.search = 'name=' + encodeURIComponent(restaurantName);
			    
			    fetch(checkUrl, { method: 'GET' })
			        .then(response => response.json())
			        .then(data => {
			            if (data.isInFavorites) {
			                button.innerHTML = '<span class="fa-star"></span> Remove from Favorites';
			                button.onclick = () => removeFromFavorites(restaurantName, button);
			            } else {
			                button.innerHTML = '<span class="fa-star"></span> Add to Favorites';
			                button.onclick = () => addToFavorites(restaurantName, button);
			            }
			        })
			        .catch(err => console.error('Error checking favorites:', err));
			}

			function addToFavorites(restaurantName, button) {
			    var favUrl = new URL(window.location.href);
			    favUrl.pathname = '/FryftEats/addToFavorites.php';
			    favUrl.search = 'name=' + encodeURIComponent(restaurantName);

			    fetch(favUrl, { method: 'GET' })
			        .then(response => {
			            if (response.ok) {
			                checkInFavorites(restaurantName, button);
			            }
			        })
			        .catch(err => console.error('Error adding to favorites:', err));
			}

			function removeFromFavorites(restaurantName, button) {
			    var favUrl = new URL(window.location.href);
			    favUrl.pathname = '/FryftEats/removeFromFavorites.php';
			    favUrl.search = 'name=' + encodeURIComponent(restaurantName);

			    fetch(favUrl, { method: 'GET' })
			        .then(response => {
			            if (response.ok) {
			                checkInFavorites(restaurantName, button);
			            }
			        })
			        .catch(err => console.error('Error removing from favorites:', err));
			}
		</script>
	</body>
</html>
