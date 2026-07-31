<?php
include 'db.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM players WHERE PlayerID=$id");

header('Location: players_view.php');
?>