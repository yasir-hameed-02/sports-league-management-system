<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: coaches.php');
    exit;
}

$id = (int)$_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM coaches WHERE CoachID=$id");
$row = mysqli_fetch_assoc($data);

if (!$row) {
    header('Location: coaches.php');
    exit;
}

$errors = [];
$name = $row['CoachName'];
$exp  = $row['ExperienceYears'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['CoachName'] ?? '');
    $exp  = trim($_POST['ExperienceYears'] ?? '');

    // Empty checks
    if ($name === '') $errors[] = "Coach name is required.";
    if ($exp  === '') $errors[] = "Experience years is required.";

    // Letters only on name
    if ($name !== '' && !preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Coach name must contain letters only.";
    }

    // Experience range
    if ($exp !== '') {
        if (!is_numeric($exp)) {
            $errors[] = "Experience years must be a number.";
        } elseif ((int)$exp < 0 || (int)$exp > 50) {
            $errors[] = "Experience years must be between 0 and 50.";
        }
    }

    // Duplicate name check (exclude current record)
    if (empty($errors)) {
        $safe_name = mysqli_real_escape_string($conn, $name);
        $dup = mysqli_query($conn, "SELECT CoachID FROM coaches WHERE CoachName='$safe_name' AND CoachID != $id");
        if (mysqli_num_rows($dup) > 0) {
            $errors[] = "A coach with this name already exists.";
        }
    }

    if (empty($errors)) {
        $safe_name = mysqli_real_escape_string($conn, $name);
        $exp_int   = (int)$exp;
        mysqli_query($conn, "UPDATE coaches SET CoachName='$safe_name', ExperienceYears=$exp_int WHERE CoachID=$id");
        header('Location: coaches.php');
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

<<div class="nav">
    <a href="index.php">🏠 Home</a>
    <a href="coaches.php">🧑‍💼 Coaches</a>
</div>

<div class="container">
<h2>✏️ Edit Coach</h2>
<div class="form-box">

    <?php foreach ($errors as $e): ?>
        <div class="error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="POST">
    <label>Coach Name</label>
    <input type="text" name="CoachName"
           value="<?= htmlspecialchars($name) ?>"
           placeholder="Coach Name">

    <label>Experience Years</label>
    <input type="number" name="ExperienceYears"
           value="<?= htmlspecialchars($exp) ?>"
           placeholder="Experience Years">

    <button type="submit">✅ Update Coach</button>
</form>
</div>
</div>

</body>
</html>
