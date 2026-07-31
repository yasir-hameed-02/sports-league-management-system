<?php
include 'db.php';

$id = $_GET['id'];

// Pehle teams ka CoachID NULL karo
mysqli_query($conn, "UPDATE teams SET CoachID=NULL WHERE CoachID=$id");

// Phir coach delete karo
mysqli_query($conn, "DELETE FROM coaches WHERE CoachID=$id");

header('Location: coaches.php');
?>
