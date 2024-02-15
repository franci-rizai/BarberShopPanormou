<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "barbershop";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed";
}

$date = $_POST['date'];
$formattedDate = date('Y-m-d', strtotime($date)); // Format the date

// Check if there is an existing record with the same date and time
$existingRecordQuery = "SELECT time FROM appointments WHERE date = '$formattedDate'";
$result = mysqli_query($conn, $existingRecordQuery);
$timeSlots = array(
    "09:00", "09:30",
    "10:00", "10:30",
    "11:00", "11:30",
    "12:00", "12:30",
    "13:00", "13:30",
    "14:00", "14:30",
    "15:00", "15:30",
    "16:00", "16:30",
    "17:00", "17:30",
    "18:00"
);

// Check if the query was successful
if ($result) {
    // Fetch the result
    $unavailableTimes = array();
    while ($row = mysqli_fetch_assoc($result)) {
        // Store the unavailable times in an array
        $unavailableTimes[] = $row['time'];
    }

    // Find the available times by taking the difference between $timeSlots and $unavailableTimes
    $availableTimes = array_diff($timeSlots, $unavailableTimes);

    // Convert the available times array to JSON format
    $jsonAvailableTimes = json_encode(['availableTimes' => array_values($availableTimes)]);

    // Echo the JSON data
    header('Content-Type: application/json');
    echo $jsonAvailableTimes;
} else {
    echo json_encode(['error' => 'Error retrieving records: ' . mysqli_error($conn)]);
}

// Close connection
mysqli_close($conn);
?>
