<?php
include 'db.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM matches WHERE MatchID=$id");

header('Location: matches.php');
?>