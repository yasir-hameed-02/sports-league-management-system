<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: matches.php');
    exit;
}

$id = (int)$_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM matches WHERE MatchID=$id");
$row = mysqli_fetch_assoc($data);

if (!$row) {
    header('Location: matches.php');
    exit;
}

$teams = mysqli_query($conn, "SELECT TeamID, TeamName FROM teams");
$venues = mysqli_query($conn, "SELECT VenueID, VenueName FROM venues");
$seasons = mysqli_query($conn, "SELECT SeasonID, SeasonYear FROM seasons");

$teams_arr = [];
while ($t = mysqli_fetch_assoc($teams)) $teams_arr[] = $t;

$venues_arr = [];
while ($v = mysqli_fetch_assoc($venues)) $venues_arr[] = $v;

$seasons_arr = [];
while ($s = mysqli_fetch_assoc($seasons)) $seasons_arr[] = $s;

$errors = [];

// Default values from DB
$seasonID   = $row['SeasonID'];
$venueID    = $row['VenueID'];
$homeTeamID = $row['HomeTeamID'];
$awayTeamID = $row['AwayTeamID'];
$matchDate  = $row['MatchDate'];
$homeScore  = $row['HomeScore'];
$awayScore  = $row['AwayScore'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $seasonID   = trim($_POST['SeasonID']   ?? '');
    $venueID    = trim($_POST['VenueID']    ?? '');
    $homeTeamID = trim($_POST['HomeTeamID'] ?? '');
    $awayTeamID = trim($_POST['AwayTeamID'] ?? '');
    $matchDate  = trim($_POST['MatchDate']  ?? '');
    $homeScore  = trim($_POST['HomeScore']  ?? '');
    $awayScore  = trim($_POST['AwayScore']  ?? '');

    // Empty checks
    if ($seasonID   === '') $errors[] = "Season is required.";
    if ($venueID    === '') $errors[] = "Venue is required.";
    if ($homeTeamID === '') $errors[] = "Home team is required.";
    if ($awayTeamID === '') $errors[] = "Away team is required.";
    if ($matchDate  === '') $errors[] = "Match date is required.";
    if ($homeScore  === '') $errors[] = "Home score is required.";
    if ($awayScore  === '') $errors[] = "Away score is required.";

    // Home and Away team cannot be same
    if ($homeTeamID !== '' && $awayTeamID !== '' && $homeTeamID === $awayTeamID) {
        $errors[] = "Home team and away team cannot be the same.";
    }

    // Score: 0 or above (no upper limit)
    if ($homeScore !== '') {
        if (!is_numeric($homeScore)) {
            $errors[] = "Home score must be a number.";
        } elseif ((int)$homeScore < 0) {
            $errors[] = "Home score cannot be negative.";
        }
    }

    if ($awayScore !== '') {
        if (!is_numeric($awayScore)) {
            $errors[] = "Away score must be a number.";
        } elseif ((int)$awayScore < 0) {
            $errors[] = "Away score cannot be negative.";
        }
    }

    if (empty($errors)) {
        $matchDate_safe = mysqli_real_escape_string($conn, $matchDate);
        $sql = "UPDATE matches SET 
                SeasonID=$seasonID, VenueID=$venueID,
                HomeTeamID=$homeTeamID, AwayTeamID=$awayTeamID,
                MatchDate='$matchDate_safe', HomeScore=$homeScore, AwayScore=$awayScore
                WHERE MatchID=$id";
        mysqli_query($conn, $sql);
        header('Location: matches.php');
        exit;
    }
}
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
}
</style>
</head>
<body>

<div class="nav">
    <a href="index.php">🏠 Home</a>
    <a href="matches.php">🎮 Matches</a>
</div>

<div class="container">
<h2>✏️ Edit Match</h2>
<div class="form-box">

    <?php foreach ($errors as $e): ?>
        <div class="error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="POST">
        <label>Season</label>
        <select name="SeasonID">
            <?php foreach ($seasons_arr as $s): ?>
                <option value="<?= $s['SeasonID'] ?>" <?= $s['SeasonID'] == $seasonID ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['SeasonYear']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Venue</label>
        <select name="VenueID">
            <?php foreach ($venues_arr as $v): ?>
                <option value="<?= $v['VenueID'] ?>" <?= $v['VenueID'] == $venueID ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['VenueName']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Home Team</label>
        <select name="HomeTeamID">
            <?php foreach ($teams_arr as $t): ?>
                <option value="<?= $t['TeamID'] ?>" <?= $t['TeamID'] == $homeTeamID ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['TeamName']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Away Team</label>
        <select name="AwayTeamID">
            <?php foreach ($teams_arr as $t): ?>
                <option value="<?= $t['TeamID'] ?>" <?= $t['TeamID'] == $awayTeamID ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['TeamName']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Match Date</label>
        <input type="date" name="MatchDate"
               value="<?= htmlspecialchars($matchDate) ?>">

        <label>Home Score</label>
        <input type="number" name="HomeScore"
               value="<?= htmlspecialchars($homeScore) ?>">

        <label>Away Score</label>
        <input type="number" name="AwayScore"
               value="<?= htmlspecialchars($awayScore) ?>">

        <button type="submit">✅ Update Match</button>
    </form>
</div>
</div>

</body>
</html>
