<?php
include "db.php";

$id = $_GET['id'];

// 1. standings delete
mysqli_query($conn, "DELETE FROM Standings WHERE TeamID=$id");

// 2. players delete
mysqli_query($conn, "DELETE FROM Players WHERE TeamID=$id");

// 3. matches delete
mysqli_query($conn, "DELETE FROM Matches WHERE HomeTeamID=$id OR AwayTeamID=$id");

// 4. team delete
mysqli_query($conn, "DELETE FROM teams WHERE TeamID=$id");

header("Location: teams.php");
exit();
?>