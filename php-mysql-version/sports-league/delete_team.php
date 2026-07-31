<?php
include 'db.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM standings WHERE StandingID=$id");

header('Location: standings.php');
?>
