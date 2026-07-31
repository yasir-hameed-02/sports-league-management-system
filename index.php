<?php
include 'db.php';

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM venues WHERE VenueID=$id");
$row = mysqli_fetch_assoc($data);

$errors = [];
$venueName = $row['VenueName'];
$city = $row['City'];
$capacity = $row['Capacity'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $venueName = trim($_POST['VenueName']);
    $city = trim($_POST['City']);
    $capacity = trim($_POST['Capacity']);

    // Empty checks
    if ($venueName === '') $errors[] = "Venue name is required.";
    if ($city === '') $errors[] = "City is required.";
    if ($capacity === '') $errors[] = "Capacity is required.";

    // Letters only
    if ($venueName !== '' && !preg_match("/^[a-zA-Z\s]+$/", $venueName))
        $errors[] = "Venue name must contain letters only.";
    if ($city !== '' && !preg_match("/^[a-zA-Z\s]+$/", $city))
        $errors[] = "City must contain letters only.";

    // Capacity numeric check
    if ($capacity !== '' && !is_numeric($capacity))
        $errors[] = "Capacity must be a number.";

    // Capacity range check
    if (is_numeric($capacity) && ($capacity < 100 || $capacity > 200000))
        $errors[] = "Capacity must be between 100 and 200,000.";

    if (empty($errors)) {
        $sql = "UPDATE venues SET VenueName='$venueName', City='$city', Capacity='$capacity' 
                WHERE VenueID=$id";
        mysqli_query($conn, $sql);
        header('Location: venues.php');
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
    <a href="venues.php">🏟️ Venues</a>
</div>

<div class="container">
<h2>✏️ Edit Venue</h2>
<div class="form-box">

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $e): ?>
                <p style="margin:5px 0"><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
    <label>Venue Name</label>
    <input type="text" name="VenueName" value="<?= htmlspecialchars($venueName ?? '') ?>" placeholder="Venue Name">

    <label>City</label>
    <input type="text" name="City" value="<?= htmlspecialchars($city ?? '') ?>" placeholder="City">

    <label>Capacity</label>
    <input type="text" name="Capacity" value="<?= htmlspecialchars($capacity ?? '') ?>" placeholder="Capacity">

    <button type="submit">✅ Update Venue</button>
</form>
</div>
</div>

</body>
</html>