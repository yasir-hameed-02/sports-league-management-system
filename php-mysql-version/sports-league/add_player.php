<?php
include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $seasonID   = trim($_POST['SeasonID']);
    $venueID    = trim($_POST['VenueID']);
    $homeTeamID = trim($_POST['HomeTeamID']);
    $awayTeamID = trim($_POST['AwayTeamID']);
    $matchDate  = trim($_POST['MatchDate']);
    $homeScore  = trim($_POST['HomeScore']);
    $awayScore  = trim($_POST['AwayScore']);

    if (empty($seasonID) || empty($venueID) || empty($homeTeamID) || empty($awayTeamID) || empty($matchDate)) {
        $error = "All fields are required.";
    } elseif (empty($homeScore) && $homeScore !== '0') {
        $error = "Home Score is required.";
    } elseif (empty($awayScore) && $awayScore !== '0') {
        $error = "Away Score is required.";
    } elseif ($homeTeamID == $awayTeamID) {
        $error = "Home Team and Away Team cannot be the same.";
    } elseif (!is_numeric($homeScore) || $homeScore < 0) {
        $error = "Home Score must be 0 or greater.";
    } elseif (!is_numeric($awayScore) || $awayScore < 0) {
    $error = "Away Score must be 0 or greater.";
    } else {
        $sql = "INSERT INTO matches (SeasonID, VenueID, HomeTeamID, AwayTeamID, MatchDate, HomeScore, AwayScore) 
                VALUES ('$seasonID', '$venueID', '$homeTeamID', '$awayTeamID', '$matchDate', '$homeScore', '$awayScore')";
        mysqli_query($conn, $sql);
        header('Location: matches.php');
        exit;
    }
}

$teams   = mysqli_query($conn, "SELECT TeamID, TeamName FROM teams");
$venues  = mysqli_query($conn, "SELECT VenueID, VenueName FROM venues");
$seasons = mysqli_query($conn, "SELECT SeasonID, SeasonYear FROM seasons");

$teams_arr = [];
while($t = mysqli_fetch_assoc($teams)) $teams_arr[] = $t;

$venues_arr = [];
while($v = mysqli_fetch_assoc($venues)) $venues_arr[] = $v;

$seasons_arr = [];
while($s = mysqli_fetch_assoc($seasons)) $seasons_arr[] = $s;
?>

<!DOCTYPE html>
<html>
<head>
<style>
*{ margin:0; padding:0; box-sizing:border-box; }
body{
    font-family:Arial; min-height:100vh; color:white;
    background:
    linear-gradient(rgba(2,6,23,0.88), rgba(15,23,42,0.92)),
    url('https://images.unsplash.com/photo-1508098682722-e99c643e7485?q=80&w=1920');
    background-size:cover; background-position:center; background-attachment:fixed;
}
::-webkit-scrollbar{ width:8px; }
::-webkit-scrollbar-track{ background:rgba(255,255,255,0.05); }
::-webkit-scrollbar-thumb{ background:rgba(56,189,248,0.4); border-radius:10px; }
::-webkit-scrollbar-thumb:hover{ background:#38bdf8; }
.nav{
    display:flex; gap:15px; flex-wrap:wrap; padding:18px 25px;
    background:rgba(255,255,255,0.08); backdrop-filter:blur(12px);
    border-bottom:1px solid rgba(255,255,255,0.1);
    box-shadow:0 8px 32px rgba(0,0,0,0.3);
}
.nav a{
    color:white; text-decoration:none; padding:10px 16px; border-radius:12px;
    background:rgba(56,189,248,0.12); border:1px solid rgba(56,189,248,0.3); transition:0.3s;
}
.nav a:hover{ background:#38bdf8; color:#020617; box-shadow:0 0 15px #38bdf8; }
.container{ padding:35px; }
h2{ margin-bottom:20px; text-shadow:0 0 15px rgba(56,189,248,0.5); }
.form-box{
    width:450px; max-width:100%; padding:30px; border-radius:20px;
    background:rgba(255,255,255,0.08); backdrop-filter:blur(12px);
    border:1px solid rgba(255,255,255,0.12); box-shadow:0 8px 32px rgba(0,0,0,0.35);
}
input, select{
    width:100%; padding:12px; margin-bottom:15px; border-radius:12px;
    border:1px solid rgba(255,255,255,0.15); background:rgba(255,255,255,0.08);
    color:white; outline:none; box-sizing:border-box;
}
input::placeholder{ color:#cbd5e1; }
select option{ color:black; }
label{ font-size:13px; color:#94a3b8; margin-bottom:4px; display:block; }
button{
    width:100%; padding:12px; border:none; border-radius:12px;
    background:#38bdf8; color:#020617; font-weight:bold; cursor:pointer; transition:0.3s;
}
button:hover{ box-shadow:0 0 18px #38bdf8; transform:translateY(-2px); }
.error{
    background:#7f1d1d; border:1px solid #ef4444; color:white;
    padding:12px; border-radius:12px; margin-bottom:15px;
}</style>
</head>
<body>

<div class="nav">
    <a href="index.php">🏠 Home</a>
    <a href="matches.php">🏟️ Matches</a>
</div>

<div class="container">
<h2>➕ Add New Match</h2>
<div class="form-box">

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Season</label>
        <select name="SeasonID">
            <option value="">-- Select Season --</option>
            <?php foreach($seasons_arr as $s): ?>
                <option value="<?= $s['SeasonID'] ?>"
                    <?= (isset($seasonID) && $seasonID == $s['SeasonID']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['SeasonYear']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Venue</label>
        <select name="VenueID">
            <option value="">-- Select Venue --</option>
            <?php foreach($venues_arr as $v): ?>
                <option value="<?= $v['VenueID'] ?>"
                    <?= (isset($venueID) && $venueID == $v['VenueID']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['VenueName']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Home Team</label>
        <select name="HomeTeamID">
            <option value="">-- Select Home Team --</option>
            <?php foreach($teams_arr as $t): ?>
                <option value="<?= $t['TeamID'] ?>"
                    <?= (isset($homeTeamID) && $homeTeamID == $t['TeamID']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['TeamName']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Away Team</label>
        <select name="AwayTeamID">
            <option value="">-- Select Away Team --</option>
            <?php foreach($teams_arr as $t): ?>
                <option value="<?= $t['TeamID'] ?>"
                    <?= (isset($awayTeamID) && $awayTeamID == $t['TeamID']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['TeamName']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Match Date</label>
        <input type="date" name="MatchDate"
               value="<?= htmlspecialchars($matchDate ?? '') ?>">

        <label>Home Score</label>
        <input type="number" name="HomeScore" placeholder="0"
               value="<?= htmlspecialchars($homeScore ?? '') ?>">

        <label>Away Score</label>
        <input type="number" name="AwayScore" placeholder="0"
               value="<?= htmlspecialchars($awayScore ?? '') ?>">

        <button type="submit">💾 Save Match</button>
    </form>
</div>
</div>

</body>
</html>
