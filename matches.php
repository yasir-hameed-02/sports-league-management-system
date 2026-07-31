<?php
include "db.php";

$teams_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM teams"))['total'];
$players_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM players"))['total'];
$coaches_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM coaches"))['total'];
$venues_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM venues"))['total'];
$seasons_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM seasons"))['total'];
$matches_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM matches"))['total'];
$standings_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM standings"))['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Sports League</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial;
    min-height:100vh;
    color:white;

    background:
    linear-gradient(rgba(2,6,23,0.85), rgba(15,23,42,0.92)),
    url('https://images.unsplash.com/photo-1508098682722-e99c643e7485?q=80&w=1920');

    background-size:cover;
    background-position:center;
    background-attachment:fixed;
}

/* NAVBAR */

.nav{
    display:flex;
    gap:15px;
    flex-wrap:wrap;

    padding:18px 25px;

    background:rgba(255,255,255,0.08);

    backdrop-filter:blur(12px);

    border-bottom:1px solid rgba(255,255,255,0.1);

    box-shadow:0 8px 32px rgba(0,0,0,0.3);
}

.nav a{
    color:white;
    text-decoration:none;

    padding:10px 16px;

    border-radius:12px;

    background:rgba(56,189,248,0.12);

    border:1px solid rgba(56,189,248,0.3);

    transition:0.3s;
}

.nav a:hover{

    background:#38bdf8;

    color:#020617;

    box-shadow:0 0 15px #38bdf8;
}

/* MAIN */

.container{
    padding:40px;
}

h1{
    font-size:42px;
    margin-bottom:10px;

    text-shadow:0 0 20px rgba(56,189,248,0.5);
}

/* CARDS */

.cards{
    display:flex;
    flex-wrap:wrap;
    gap:25px;
    margin-top:35px;
}

.card{

    width:180px;

    padding:28px 20px;

    border-radius:20px;

    text-align:center;

    text-decoration:none;

    color:white;

    background:rgba(255,255,255,0.08);

    backdrop-filter:blur(14px);

    border:1px solid rgba(255,255,255,0.12);

    box-shadow:0 8px 32px rgba(0,0,0,0.35);

    transition:0.3s;
}

.card:hover{

    transform:translateY(-8px) scale(1.03);

    border:1px solid rgba(56,189,248,0.6);

    box-shadow:0 0 25px rgba(56,189,248,0.5);
}

.card .number{
    font-size:42px;
    font-weight:bold;

    margin-top:12px;

    color:#38bdf8;
}

.card .label{
    font-size:15px;
    letter-spacing:1px;

    color:#cbd5e1;
}

</style>

</head>
<body>

<div class="nav">
    <a href="index.php">🏠 Home</a>
    <a href="teams.php">👕 Teams</a>
    <a href="players_view.php">⚽ Players</a>
    <a href="coaches.php">🧑‍💼 Coaches</a>
    <a href="venues.php">🏟️ Venues</a>
    <a href="seasons.php">📅 Seasons</a>
    <a href="matches.php">🎮 Matches</a>
    <a href="standings.php">🏆 Standings</a>
</div>

<div class="container">

<h1>🏆 Sports League Dashboard</h1>

<div class="cards">

    <a class="card" href="teams.php">
        <div class="label">Teams</div>
        <div class="number"><?= $teams_count ?></div>
    </a>

    <a class="card" href="players_view.php">
        <div class="label">Players</div>
        <div class="number"><?= $players_count ?></div>
    </a>

    <a class="card" href="coaches.php">
        <div class="label">Coaches</div>
        <div class="number"><?= $coaches_count ?></div>
    </a>

    <a class="card" href="venues.php">
        <div class="label">Venues</div>
        <div class="number"><?= $venues_count ?></div>
    </a>

    <a class="card" href="seasons.php">
        <div class="label">Seasons</div>
        <div class="number"><?= $seasons_count ?></div>
    </a>

    <a class="card" href="matches.php">
        <div class="label">Matches</div>
        <div class="number"><?= $matches_count ?></div>
    </a>

    <a class="card" href="standings.php">
        <div class="label">Standings</div>
        <div class="number"><?= $standings_count ?></div>
    </a>

</div>

</div>

</body>
</html>