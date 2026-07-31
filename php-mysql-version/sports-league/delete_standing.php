<?php
include 'db.php';

$id = $_GET['id'];

// Pehle dependent tables clear karo
mysqli_query($conn, "UPDATE matches SET SeasonID=NULL WHERE SeasonID=$id");
mysqli_query($conn, "DELETE FROM standings WHERE SeasonID=$id");

// Phir season delete karo
mysqli_query($conn, "DELETE FROM seasons WHERE SeasonID=$id");

header('Location: seasons.php');
?>
