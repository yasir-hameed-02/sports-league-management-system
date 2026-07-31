<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: seasons.php');
    exit;
}

$id = (int)$_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM seasons WHERE SeasonID=$id");
$row = mysqli_fetch_assoc($data);

if (!$row) {
    header('Location: seasons.php');
    exit;
}

$errors = [];

// Default values from DB
$year  = $row['SeasonYear'];
$start = $row['StartDate'];
$end   = $row['EndDate'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $year  = trim($_POST['SeasonYear'] ?? '');
    $start = trim($_POST['StartDate']  ?? '');
    $end   = trim($_POST['EndDate']    ?? '');

    // Empty checks
    if ($year  === '') $errors[] = "Season year is required.";
    if ($start === '') $errors[] = "Start date is required.";
    if ($end   === '') $errors[] = "End date is required.";

    // Year must be exactly 4 digits
    if ($year !== '' && !preg_match("/^\d{4}$/", $year)) {
        $errors[] = "Season year must be exactly 4 digits.";
    }

    // Year range 2000-2100
    if ($year !== '' && preg_match("/^\d{4}$/", $year)) {
        $year_int = (int)$year;
        if ($year_int < 2000 || $year_int > 2100) {
            $errors[] = "Season year must be between 2000 and 2100.";
        }
    }

    // EndDate must be after StartDate
    if ($start !== '' && $end !== '') {
        if (strtotime($end) <= strtotime($start)) {
            $errors[] = "End date must be after start date.";
        }
    }

    if (empty($errors)) {
        $safe_year  = mysqli_real_escape_string($conn, $year);
        $safe_start = mysqli_real_escape_string($conn, $start);
        $safe_end   = mysqli_real_escape_string($conn, $end);

        mysqli_query($conn, "UPDATE seasons SET 
            SeasonYear='$safe_year', StartDate='$safe_start', EndDate='$safe_end'
            WHERE SeasonID=$id");
        header('Location: seasons.php');
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
input{
    width:100%; padding:12px; margin-bottom:15px; border-radius:12px;
    border:1px solid rgba(255,255,255,0.15); background:rgba(255,255,255,0.08);
    color:white; outline:none; box-sizing:border-box;
}
input::placeholder{ color:#cbd5e1; }
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
    <a href="seasons.php">📅 Seasons</a>
</div>

<div class="container">
<h2>✏️ Edit Season</h2>
<div class="form-box">

    <?php foreach ($errors as $e): ?>
        <div class="error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="POST">
        <label>Season Year</label>
        <input type="text" name="SeasonYear"
               value="<?= htmlspecialchars($year) ?>"
               placeholder="e.g. 2025">

        <label>Start Date</label>
        <input type="date" name="StartDate"
               value="<?= htmlspecialchars($start) ?>">

        <label>End Date</label>
        <input type="date" name="EndDate"
               value="<?= htmlspecialchars($end) ?>">

        <button type="submit">✅ Update Season</button>
</div>
</div>

</body>
</html>