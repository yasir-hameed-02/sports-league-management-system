
<?php
$conn = mysqli_connect("localhost", "root", "", "sports_league_system");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
