<?php
    include 'config/config.php';

    session_start();
    if (!isset($_SESSION['username'])) {
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }

    $username = $_SESSION['username'];
    $restaurantName = urldecode($_GET['name']);

    echo $username;

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_errno) {
        echo $mysqli->connect_error;
        exit();
    }

    $userQuery = "SELECT user_id FROM users WHERE username = ?";
    $userStmt = $mysqli->prepare($userQuery);
    $userStmt->bind_param("s", $username);
    $userStmt->execute();
    $userResult = $userStmt->get_result();

    $restaurantQuery = "SELECT restaurant_id FROM restaurants WHERE name = ?";
    $restaurantStmt = $mysqli->prepare($restaurantQuery);
    $restaurantStmt->bind_param("s", $restaurantName);
    $restaurantStmt->execute();
    $restaurantResult = $restaurantStmt->get_result();

    $userRow = $userResult->fetch_assoc();
    $restaurantRow = $restaurantResult->fetch_assoc();

    if ($userRow && $restaurantRow) {
        $userId = $userRow['user_id'];
        $restaurantId = $restaurantRow['restaurant_id'];

        $insertQuery = "INSERT INTO favorites (user_id, restaurant_id) VALUES (?, ?)";
        $insertStmt = $mysqli->prepare($insertQuery);
        $insertStmt->bind_param("ii", $userId, $restaurantId);
        $insertStmt->execute();
    }
    else {
        echo json_encode(['error' => 'User or restaurant not found']);
    }

    $userStmt->close();
    $restaurantStmt->close();
    $insertStmt->close();
    $mysqli->close();
?>
