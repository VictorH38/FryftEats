<?php
    include 'config/config.php';

    session_start();
    if (!isset($_SESSION['username'])) {
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }

    $username = $_SESSION['username'];
    $restaurantName = urldecode($_GET['name']);

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_errno) {
        echo $mysqli->connect_error;
        exit();
    }

    $isInFavorites = false;

    $userQuery = "SELECT user_id FROM users WHERE username = ?";
    if ($userStmt = $mysqli->prepare($userQuery)) {
        $userStmt->bind_param("s", $username);
        $userStmt->execute();
        $userResult = $userStmt->get_result();

        if ($userRow = $userResult->fetch_assoc()) {
            $userId = $userRow['user_id'];

            $restaurantQuery = "SELECT restaurant_id FROM restaurants WHERE name = ?";
            if ($restaurantStmt = $mysqli->prepare($restaurantQuery)) {
                $restaurantStmt->bind_param("s", $restaurantName);
                $restaurantStmt->execute();
                $restaurantResult = $restaurantStmt->get_result();

                if ($restaurantRow = $restaurantResult->fetch_assoc()) {
                    $restaurantId = $restaurantRow['restaurant_id'];

                    $checkQuery = "SELECT COUNT(*) FROM favorites WHERE user_id = ? AND restaurant_id = ?";
                    if ($checkStmt = $mysqli->prepare($checkQuery)) {
                        $checkStmt->bind_param("ii", $userId, $restaurantId);
                        $checkStmt->execute();
                        $checkResult = $checkStmt->get_result();

                        if ($checkRow = $checkResult->fetch_array()) {
                            $isInFavorites = $checkRow[0] > 0;
                        }
                    }
                }
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode(['isInFavorites' => $isInFavorites]);

    $mysqli->close();
?>
