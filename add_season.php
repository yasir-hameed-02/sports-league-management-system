<?php
include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['PlayerName']);
    $age      = trim($_POST['Age']);
    $position = trim($_POST['Position']);
    $teamID   = trim($_POST['TeamID']);

    if (empty($name) || empty($age) || empty($position)) {
        $error = "All fields are required.";
    } elseif (empty($teamID)) {
        $error = "Please select a Team.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $error = "Player Name must contain letters only.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $position)) {
        $error = "Position must contain letters only.";
    } elseif ($age < 15 || $age > 50) {
        $error = "Age must be between 15 and 50.";
    } else {
        $sql = "INSERT INTO players (PlayerName, Age, Position, TeamID) 
                VALUES ('$name', '$age', '$position', '$teamID')";
        mysqli_query($conn, $sql);
        header('Location: players_view.php');
        exit;
    }
}

$teams = mysqli_query($conn, "SELECT TeamID, TeamName FROM teams");
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
    <a href="players_view.php">⚽ Players</a>
</div>

<div class="container">
<h2>➕ Add New Player</h2>
<div class="form-box">

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="PlayerName" placeholder="Player Name"
               value="<?= htmlspecialchars($name ?? '') ?>">
        <input type="number" name="Age" placeholder="Age (15-50)"
               value="<?= htmlspecialchars($age ?? '') ?>">
        <input type="text" name="Position" placeholder="Position"
               value="<?= htmlspecialchars($position ?? '') ?>">
        <select name="TeamID">
            <option value="">-- Select Team (Required) --</option>
            <?php while($t = mysqli_fetch_assoc($teams)): ?>
                <option value="<?= $t['TeamID'] ?>"
                    <?= (isset($teamID) && $teamID == $t['TeamID']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['TeamName']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">💾 Save Player</button>
    </form>
</div>
</div>

</body>
</html>