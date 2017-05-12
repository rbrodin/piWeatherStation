<!DOCTYPE html>

<head>
<link rel="stylesheet" type="text/css" href="main.css">

</head>

<body>

<?php 

$date = date("m/d/Y");

echo "Today is: $date";

?>

<?php
$servername = "localhost";
$username = "NAME_php";
$password = "*********";
$dbname = 'NAME_weatherData';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * 
FROM  `current_Weather` ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<p class='weatherText'>"."Current Temperature: ". $row["currentTemperature"] ."F<br>"."Current Pressure: " . $row["currenPressure"]."  Millibars<br>"."\nPercent Humid: " . $row["currentHumidity"]."%<br>"."\nChange in Temperature: ". $row["currentDifference"]. " degrees<br>";
    }
} else {
    echo "0 results";
}
$conn->close();

?>






</body>

</html>
