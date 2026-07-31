<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: standings.php');
    exit;
}

$id = (int)$_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM standings WHERE StandingID=$id");
$row = mysqli_fetch_assoc($data);

if (!$row) {
    header('Location: standings.php');
    exit;
}

$teams = mysqli_query($conn, "SELECT TeamID, TeamName FROM teams");
$seasons = mysqli_query($conn, "SELECT SeasonID, SeasonYear FROM seasons");

$teams_arr = [];
while ($t = mysqli_fetch_assoc($teams)) $teams_arr[] = $t;

$seasons_arr = [];
while ($s = mysqli_fetch_assoc($seasons)) $seasons_arr[] = $s;

$errors = [];

// Default values from DB
$teamID   = $row['TeamID'];
$seasonID = $row['SeasonID'];
$played   = $row['MatchesPlayed'];
$wins     = $row['Wins'];
$losses   = $row['Losses'];
$points   = $row['Points'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teamID   = trim($_POST['TeamID']       ?? '');
    $seasonID = trim($_POST['SeasonID']     ?? '');
    $played   = trim($_POST['MatchesPlayed']?? '');
    $wins     = trim($_POST['Wins']         ?? '');
    $losses   = trim($_POST['Losses']       ?? '');
    $points   = trim($_POST['Points']       ?? '');

    // Empty checks
    if ($teamID   === '') $errors[] = "Team must be selected.";
    if ($seasonID === '') $errors[] = "Season must be selected.";
    if ($played   === '') $errors[] = "Matches played is required.";
    if ($wins     === '') $errors[] = "Wins is required.";
    if ($losses   === '') $errors[] = "Losses is required.";
    if ($points   === '') $errors[] = "Points is required.";

    // Numeric checks + ranges
    if ($played !== '') {
        if (!is_numeric($played)) {
            $errors[] = "Matches played must be a number.";
        } elseif ((int)$played < 0 || (int)$played > 100) {
            $errors[] = "Matches played must be between 0 and 100.";
        }
    }

    if ($wins !== '') {
        if (!is_numeric($wins)) {
            $errors[] = "Wins must be a number.";
        } elseif ((int)$wins < 0 || (int)$wins > 100) {
            $errors[] = "Wins must be between 0 and 100.";
        }
    }

    if ($losses !== '') {
        if (!is_numeric($losses)) {
            $errors[] = "Losses must be a number.";
        } elseif ((int)$losses < 0 || (int)$losses > 100) {
            $errors[] = "Losses must be between 0 and 100.";
        }
    }

    if ($points !== '') {
        if (!is_numeric($points)) {
            $errors[] = "Points must be a number.";
        } elseif ((int)$points < 0 || (int)$points > 300) {
            $errors[] = "Points must be between 0 and 300.";
        }
    }

    // Wins + Losses cannot exceed MatchesPlayed
    if (empty($errors)) {
        if ((int)$wins + (int)$losses > (int)$played) {
            $errors[] = "Wins and losses combined cannot exceed matches played.";
        }
    }

    if (empty($errors)) {
        $played_int  = (int)$played;
        $wins_int    = (int)$wins;
        $losses_int  = (int)$losses;
        $points_int  = (int)$points;
        $teamID_int  = (int)$teamID;
        $seasonID_int = (int)$seasonID;

        mysqli_query($conn, "UPDATE standings SET
            TeamID=$teamID_int, SeasonID=$seasonID_int,
            MatchesPlayed=$played_int, Wins=$wins_int,
            Losses=$losses_int, Points=$points_int
            WHERE StandingID=$id");
        header('Location: standings.php');
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
    <a href="standings.php">🏆 Standings</a>
</div>

<div class="container">
<h2>✏️ Edit Standing</h2>
<div class="form-box">

    <?php foreach ($errors as $e): ?>
        <div class="error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="POST">
        <label>Team</label>
        <select name="TeamID">
            <?php foreach ($teams_arr as $t): ?>
                <option value="<?= $t['TeamID'] ?>"
                    <?= $t['TeamID'] == $teamID ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['TeamName']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Season</label>
        <select name="SeasonID">
            <?php foreach ($seasons_arr as $s): ?>
                <option value="<?= $s['SeasonID'] ?>"
                    <?= $s['SeasonID'] == $seasonID ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['SeasonYear']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Matches Played</label>
        <input type="number" name="MatchesPlayed"
               value="<?= htmlspecialchars($played) ?>">

        <label>Wins</label>
        <input type="number" name="Wins"
               value="<?= htmlspecialchars($wins) ?>">

        <label>Losses</label>
        <input type="number" name="Losses"
               value="<?= htmlspecialchars($losses) ?>">

        <label>Points</label>
        <input type="number" name="Points"
               value="<?= htmlspecialchars($points) ?>">

        <button type="submit">✅ Update Standing</button>
    </form>
</div>
</div>

</body>
</html>
