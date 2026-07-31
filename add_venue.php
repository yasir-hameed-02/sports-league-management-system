<?php
include 'db.php';

$error = "";
$name = "";
$city = "";
$coachID = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = trim($_POST['TeamName']);
    $city    = trim($_POST['City']);
    $coachID = trim($_POST['CoachID']);

    if(empty($name) || empty($city)) {
        $error = "Team Name and City are required.";
    } elseif(!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $error = "Team Name must contain letters only.";
    } elseif(!preg_match("/^[a-zA-Z\s]+$/", $city)) {
        $error = "City must contain letters only.";
    } elseif(empty($coachID)) {
        $error = "Please select a Coach.";
    } else {
        $check = mysqli_query($conn, "SELECT * FROM teams WHERE TeamName='$name'");
        if(mysqli_num_rows($check) > 0) {
            $error = "A team with this name already exists.";
        } else {
            mysqli_query($conn, "INSERT INTO teams (TeamName, City, CoachID) VALUES ('$name', '$city', '$coachID')");
            header('Location: teams.php');
            exit;
        }
    }
}

$coaches = mysqli_query($conn, "SELECT CoachID, CoachName FROM coaches");
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
select::-webkit-scrollbar{ width:6px; }
select::-webkit-scrollbar-track{ background:#0d1b2a; }
select::-webkit-scrollbar-thumb{ background:rgba(56,189,248,0.4); border-radius:10px; }
select::-webkit-scrollbar-thumb:hover{ background:#38bdf8; }
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
}
</style>
</head>
<body>

<div class="nav">
    <a href="index.php">🏠 Home</a>
    <a href="teams.php">👕 Teams</a>
</div>

<div class="container">
<h2>➕ Add New Team</h2>
<div class="form-box">

    <?php if($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="TeamName" placeholder="Team Name"
               value="<?= htmlspecialchars($name) ?>" required>
        <input type="text" name="City" placeholder="City"
               value="<?= htmlspecialchars($city) ?>" required>
        <select name="CoachID">
            <option value="">-- Select Coach (Required) --</option>
            <?php while($c = mysqli_fetch_assoc($coaches)): ?>
                <option value="<?= $c['CoachID'] ?>"
                    <?= $coachID == $c['CoachID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['CoachName']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">💾 Save Team</button>
    </form>

</div>
</div>

</body>
</html>