<?php
include 'db.php';

$id = $_GET['id'];

// Pehle matches ka VenueID NULL karo
mysqli_query($conn, "UPDATE matches SET VenueID=NULL WHERE VenueID=$id");

// Phir venue delete karo
mysqli_query($conn, "DELETE FROM venues WHERE VenueID=$id");

header('Location: venues.php');
?>