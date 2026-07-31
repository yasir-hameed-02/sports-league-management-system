<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: teams.php');
    exit;
}

$id = (int)$_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM teams WHERE TeamID=$id");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header('Location: teams.php');
    exit;
}

$coaches = mysqli_query($conn, "SELECT CoachID, CoachName FROM coaches");
$coaches_arr = [];
while ($c = mysqli_fetch_assoc($coaches)) $coaches_arr[] = $c;

$errors = [];

$name    = $row['TeamName'];
$city    = $row['City'];
$coachID = $row['CoachID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name    = trim($_POST['TeamName'] ?? '');
    $city    = trim($_POST['City'] ?? '');
    $coachID = trim($_POST['CoachID'] ?? '');

    if ($name === '') $errors[] = "Team name is required.";
    if ($city === '') $errors[] = "City is required.";
    if ($coachID === '') $errors[] = "Coach must be selected.";

    if ($name !== '' && !preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Team name must contain letters only.";
    }

    if ($city !== '' && !preg_match("/^[a-zA-Z\s]+$/", $city)) {
        $errors[] = "City must contain letters only.";
    }

    if (empty($errors)) {

        $safe_name = mysqli_real_escape_string($conn, $name);

        $dup = mysqli_query(
            $conn,
            "SELECT TeamID FROM teams
             WHERE TeamName='$safe_name'
             AND TeamID != $id"
        );

        if (mysqli_num_rows($dup) > 0) {
            $errors[] = "A team with this name already exists.";
        }
    }

    if (empty($errors)) {

        $safe_name = mysqli_real_escape_string($conn, $name);
        $safe_city = mysqli_real_escape_string($conn, $city);
        $coachID_int = (int)$coachID;

        mysqli_query(
            $conn,
            "UPDATE teams SET
             TeamName='$safe_name',
             City='$safe_city',
             CoachID=$coachID_int
             WHERE TeamID=$id"
        );

        header('Location: teams.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Team</title>

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
    linear-gradient(rgba(2,6,23,0.88), rgba(15,23,42,0.92)),
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

/* CONTENT */

.container{
    padding:35px;
}

h2{
    margin-bottom:20px;
    text-shadow:0 0 15px rgba(56,189,248,0.5);
}

/* FORM BOX */

.form-box{

    width:450px;
    max-width:100%;

    padding:30px;

    border-radius:20px;

    background:rgba(255,255,255,0.08);

    backdrop-filter:blur(12px);

    border:1px solid rgba(255,255,255,0.12);

    box-shadow:0 8px 32px rgba(0,0,0,0.35);
}

label{
    display:block;
    margin-bottom:6px;
    color:#cbd5e1;
}

input,
select{

    width:100%;

    padding:12px;

    margin-bottom:15px;

    border-radius:12px;

    border:1px solid rgba(255,255,255,0.15);

    background:rgba(255,255,255,0.08);

    color:white;

    outline:none;
}

input::placeholder{
    color:#cbd5e1;
}

select option{
    color:black;
}

/* BUTTON */

button{

    width:100%;

    padding:12px;

    border:none;

    border-radius:12px;

    background:#38bdf8;

    color:white;

    font-weight:bold;

    cursor:pointer;

    transition:0.3s;
}

button:hover{

   box-shadow:0 0 18px #38bdf8;
    transform:translateY(-2px);
}

/* ERRORS */

.error{

    background:#7f1d1d;

    border:1px solid #ef4444;

    color:white;

    padding:12px;

    border-radius:12px;

    margin-bottom:15px;
}

</style>

</head>

<body>

<div class="nav">
    <a href="index.php">🏠 Home</a>
    <a href="teams.php">👕 Teams</a>
</div>

<div class="container">

<h2>✏️ Edit Team</h2>

<div class="form-box">

    <?php foreach ($errors as $e): ?>
        <div class="error">
            <?= htmlspecialchars($e) ?>
        </div>
    <?php endforeach; ?>

    <form method="POST">

        <label>Team Name</label>

        <input
            type="text"
            name="TeamName"
            value="<?= htmlspecialchars($name) ?>"
            placeholder="Team Name">

        <label>City</label>

        <input
            type="text"
            name="City"
            value="<?= htmlspecialchars($city) ?>"
            placeholder="City">

        <label>Coach</label>

        <select name="CoachID">

            <option value="">-- Select Coach --</option>

            <?php foreach ($coaches_arr as $c): ?>

                <option
                    value="<?= $c['CoachID'] ?>"
                    <?= $c['CoachID'] == $coachID ? 'selected' : '' ?>>

                    <?= htmlspecialchars($c['CoachName']) ?>

                </option>

            <?php endforeach; ?>

        </select>

        <button type="submit">
            ✅ Update Team
        </button>

    </form>

</div>

</div>

</body>
</html>