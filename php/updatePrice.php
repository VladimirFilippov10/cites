<?php
include 'dbconnect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_id = intval($_POST['car_id']);
    if (isset($_POST['edit_price']))

    {
        $query = "SELECT car_price FROM car WHERE car_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $car = $result->fetch_assoc();
            $new_price = floatval($_POST['new_price']); // Get the new price from the form

    
            // Update the car price
            $updateQuery = "UPDATE car SET car_price = ? WHERE car_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("di", $new_price, $car_id);

            $updateStmt->execute();
        } // Closing brace for the first if statement
    } 
    if (isset($_POST['toggle_price']))


    {
        $query = "SELECT car_in_price FROM car WHERE car_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $car = $result->fetch_assoc();
            $new_price_status = !$car['car_in_price']; // Toggle the boolean value
    
            // Обновление значения cars_in_price
            $updateQuery = "UPDATE car SET car_in_price = ? WHERE car_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ii", $new_price_status, $car_id);
            $updateStmt->execute();
        } // Closing brace for the else statement
    } // Closing brace for the else block
    // Redirect back to the viewAllCars page
    header("Location: ../viewAllCars.php");
    exit();
}

    // Получение текущего значения cars_in_price


?>
