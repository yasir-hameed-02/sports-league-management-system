<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: players_view.php');
    exit;
}

$id = (int)$_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM players WHERE PlayerID=$id");
$row = mysqli_fetch_assoc($data);

if (!$row) {
    header('Location: players_view.php');
    exit;
}

$teams = mysqli_query($conn, "SELECT TeamID, TeamName FROM teams");
$teams_arr = [];
while ($t = mysqli_fetch_assoc($teams)) $teams_arr[] = $t;

$errors = [];

// Default values from DB
$name     = $row['PlayerName'];
$age      = $row['Age'];
$position = $row['Position'];
$teamID   = $row['TeamID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['PlayerName'] ?? '');
    $age      = trim($_POST['Age']        ?? '');
    $position = trim($_POST['Position']   ?? '');
    $teamID   = trim($_POST['TeamID']     ?? '');

    // Empty checks
    if ($name     === '') $errors[] = "Player name is required.";
    if ($age      === '') $errors[] = "Age is required.";
    if ($position === '') $errors[] = "Position is required.";
    if ($teamID   === '') $errors[] = "Team must be selected.";

    // Letters only
    if ($name !== '' && !preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Player name must contain letters only.";
    }
    if ($position !== '' && !preg_match("/^[a-zA-Z\s]+$/", $position)) {
        $errors[] = "Position must contain letters only.";
    }

    // Age range 15-50
    if ($age !== '') {
        if (!is_numeric($age)) {
            $errors[] = "Age must be a number.";
        } elseif ((int)$age < 15 || (int)$age > 50) {
            $errors[] = "Age must be between 15 and 50.";
        }
    }

    if (empty($errors)) {
        $safe_name     = mysqli_real_escape_string($conn, $name);
        $safe_position = mysqli_real_escape_string($conn, $position);
        $age_int       = (int)$age;
        $teamID_int    = (int)$teamID;

        mysqli_query($conn, "UPDATE players SET 
            PlayerName='$safe_name', Age=$age_int,
            Position='$safe_position', TeamID=$teamID_int
            WHERE PlayerID=$id");
        header('Location: players_view.php');
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
    <a href="players_view.php">⚽ Players</a>
</div>

<div class="container">
<h2>✏️ Edit Player</h2>
<div class="form-box">

    <?php foreach ($errors as $e): ?>
        <div class="error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="POST">
        <label>Player Name</label>
        <input type="text" name="PlayerName"
               value="<?= htmlspecialchars($name) ?>"
               placeholder="Player Name">

        <label>Age</label>
        <input type="number" name="Age"
               value="<?= htmlspecialchars($age) ?>"
               placeholder="Age">

        <label>Position</label>
        <input type="text" name="Position"
               value="<?= htmlspecialchars($position) ?>"
               placeholder="Position">

        <label>Team</label>
        <select name="TeamID">
            <?php foreach ($teams_arr as $t): ?>
                <option value="<?= $t['TeamID'] ?>"
                    <?= $t['TeamID'] == $teamID ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['TeamName']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">✅ Update Player</button>
    </form>
</div>
</div>

</body>
</html>
