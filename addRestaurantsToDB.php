<?php
	include 'config/config.php';

	$data = json_decode(file_get_contents('php://input'), true);
	$businesses = $data['businesses'];

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	foreach ($businesses as $business) {
        $name = $business["name"];
        $address = implode(", ", $business["location"]["display_address"]);
        $phone = isset($business["display_phone"]) ? $business["display_phone"] : "";
        $cuisine = count($business["categories"]) > 0 ? $business["categories"][0]["title"] : "";
        $rating = isset($business["rating"]) ? $business["rating"] : 0;
        $price = isset($business["price"]) ? $business["price"] : "";
        $url = explode("?", $business["url"])[0];
        $image_url = $business["image_url"];

        $checkStmt = $mysqli->prepare("SELECT COUNT(*) FROM restaurants WHERE name = ?");
        $checkStmt->bind_param("s", $name);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_array();
        if ($row[0] == 0) {
            $insertStmt = $mysqli->prepare("INSERT INTO restaurants (name, address, phone_number, cuisine, rating, price, url, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $insertStmt->bind_param("ssssdsss", $name, $address, $phone, $cuisine, $rating, $price, $url, $image_url);
            $insertStmt->execute();
        }

        $checkStmt->close();
    }

    $mysqli->close();
?>
