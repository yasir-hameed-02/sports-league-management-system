<?php
include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['VenueName']);
    $city     = trim($_POST['City']);
    $capacity = trim($_POST['Capacity']);

    if (empty($name) || empty($city) || empty($capacity)) {
        $error = "All fields are required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $error = "Venue Name must contain letters only.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $city)) {
        $error = "City must contain letters only.";
    } elseif (!is_numeric($capacity)) {
        $error = "Capacity must be a number.";
    } elseif ((int)$capacity < 100 || (int)$capacity > 200000) {
        $error = "Capacity must be between 100 and 200,000.";
    } else {
        $sql = "INSERT INTO venues (VenueName, City, Capacity) 
                VALUES ('$name', '$city', '$capacity')";
        mysqli_query($conn, $sql);
        header('Location: venues.php');
        exit();
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
input{
    width:100%; padding:12px; margin-bottom:15px; border-radius:12px;
    border:1px solid rgba(255,255,255,0.15); background:rgba(255,255,255,0.08);
    color:white; outline:none; box-sizing:border-box;
}
input::placeholder{ color:#cbd5e1; }
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
<h2>➕ Add New Venue</h2>
<div class="form-box">

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="VenueName" placeholder="Venue Name"
               value="<?= htmlspecialchars($name ?? '') ?>">
        <input type="text" name="City" placeholder="City"
               value="<?= htmlspecialchars($city ?? '') ?>">
        <input type="number" name="Capacity" placeholder="Capacity (100–200,000)"
               value="<?= htmlspecialchars($capacity ?? '') ?>">
       <button type="submit">💾 Save Venue</button>
    </form>
</div>
</div>

</body>
</html>