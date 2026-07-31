<?php
include "db.php";
$result = mysqli_query($conn, "
SELECT 
    standings.StandingID,
    standings.MatchesPlayed,
    standings.Wins,
    standings.Losses,
    standings.Points,
    teams.TeamName,
    seasons.SeasonYear
FROM standings
LEFT JOIN teams ON standings.TeamID = teams.TeamID
LEFT JOIN seasons ON standings.SeasonID = seasons.SeasonID
ORDER BY standings.Points DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<style>

*{ margin:0; padding:0; box-sizing:border-box; }

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

::-webkit-scrollbar{ width:8px; }
::-webkit-scrollbar-track{ background:rgba(255,255,255,0.05); }
::-webkit-scrollbar-thumb{ background:rgba(56,189,248,0.4); border-radius:10px; }
::-webkit-scrollbar-thumb:hover{ background:#38bdf8; }

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

.container{ padding:35px; }

h2{
    margin-bottom:20px;
    text-shadow:0 0 15px rgba(56,189,248,0.5);
}

.btn{
    padding:10px 16px;
    border-radius:12px;
    text-decoration:none;
    color:white;
    display:inline-block;
    transition:0.3s;
}

.add{
    background:rgba(56,189,248,0.2);
    border:1px solid rgba(56,189,248,0.4);
}
.add:hover{
    background:#38bdf8;
    color:#020617;
    box-shadow:0 0 15px #38bdf8;
}
.edit{
    background:rgba(56,189,248,0.2);
    border:1px solid rgba(56,189,248,0.4);
}
.edit:hover{
    background:#38bdf8;
    color:#020617;
    box-shadow:0 0 15px #38bdf8;
}
.delete{
    background:rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.2);
}
.delete:hover{
    background:rgba(239,68,68,0.7);
    box-shadow:0 0 15px rgba(239,68,68,0.5);
}

/* MODAL */
.modal-overlay{
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,0.6); backdrop-filter:blur(6px);
    z-index:1000; justify-content:center; align-items:center;
}
.modal-overlay.active{ display:flex; }
.modal-box{
    background:rgba(15,23,42,0.95);
    border:1px solid rgba(255,255,255,0.15);
    border-radius:20px; padding:35px 30px;
    width:380px; max-width:90%;
    box-shadow:0 0 40px rgba(239,68,68,0.3), 0 8px 32px rgba(0,0,0,0.5);
    text-align:center;
}
.modal-icon{ font-size:48px; margin-bottom:15px; }
.modal-title{
    font-size:22px; font-weight:bold; margin-bottom:10px;
    text-shadow:0 0 15px rgba(239,68,68,0.6);
}
.modal-msg{ color:#94a3b8; margin-bottom:25px; font-size:15px; }
.modal-btns{ display:flex; gap:12px; justify-content:center; }
.modal-yes{
    padding:11px 28px; border-radius:12px; border:none;
    background:rgba(239,68,68,0.8); color:white;
    font-weight:bold; cursor:pointer; transition:0.3s; font-size:15px;
}
.modal-yes:hover{
    background:#ef4444;
    box-shadow:0 0 18px rgba(239,68,68,0.7);
    transform:translateY(-2px);
}
.modal-no{
    padding:11px 28px; border-radius:12px; border:none;
    background:rgba(56,189,248,0.2);
    border:1px solid rgba(56,189,248,0.4);
    color:white; font-weight:bold; cursor:pointer; transition:0.3s; font-size:15px;
}
.modal-no:hover{
    background:#38bdf8; color:#020617;
    box-shadow:0 0 18px #38bdf8;
    transform:translateY(-2px);
}

table{
    width:100%;
    border-collapse:collapse;
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(12px);
    border:1px solid rgba(255,255,255,0.12);
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 8px 32px rgba(0,0,0,0.35);
}

th{
    background:rgba(56,189,248,0.18);
    padding:14px;
    text-align:left;
    border-bottom:1px solid rgba(255,255,255,0.1);
}

td{
    padding:14px;
    border-bottom:1px solid rgba(255,255,255,0.08);
    text-align:center;
}

tr:hover{
    background:rgba(255,255,255,0.05);
}

</style>
</head>
<body>

<div class="nav">
    <a href="index.php">🏠 Home</a>
    <a href="teams.php">👕 Teams</a>
    <a href="players_view.php">⚽ Players</a>
    <a href="coaches.php">🧑‍💼 Coaches</a>
    <a href="venues.php">🏟️ Venues</a>
    <a href="seasons.php">📅 Seasons</a>
    <a href="matches.php">🎮 Matches</a>
    <a href="standings.php">🏆 Standings</a>
</div>

<div class="container">
<h2>🏆 Standings</h2>
<a class="btn add" href="add_standing.php">➕ Add Standing</a>
<br><br>

<table>
<tr>
    <th>ID</th>
    <th>Team</th>
    <th>Season</th>
    <th>Played</th>
    <th>Wins</th>
    <th>Losses</th>
    <th>Points</th>
    <th>Actions</th>
</tr>
<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['StandingID'] ?></td>
    <td><?= $row['TeamName'] ?></td>
    <td><?= $row['SeasonYear'] ?></td>
    <td><?= $row['MatchesPlayed'] ?></td>
    <td><?= $row['Wins'] ?></td>
    <td><?= $row['Losses'] ?></td>
    <td><?= $row['Points'] ?></td>
    <td>
        <a class="btn edit" href="edit_standing.php?id=<?= $row['StandingID'] ?>">Edit</a>
       <a class="btn delete" href="#" onclick="showModal('delete_standing.php?id=<?= $row["StandingID"] ?>'); return false;">🗑️ Delete</a>
</tr>
<?php } ?>
</table>
</div>

<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">🗑️</div>
        <div class="modal-title">Delete Record?</div>
        <div class="modal-msg">This action cannot be undone.<br>Are you sure you want to delete this record?</div>
        <div class="modal-btns">
            <button class="modal-yes" id="confirmYes">Yes, Delete</button>
            <button class="modal-no" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
let deleteURL = '';
function showModal(url) {
    deleteURL = url;
    document.getElementById('deleteModal').classList.add('active');
}
function closeModal() {
    document.getElementById('deleteModal').classList.remove('active');
    deleteURL = '';
}
document.getElementById('confirmYes').addEventListener('click', function() {
    if (deleteURL) window.location.href = deleteURL;
});
</script>

</body>
</html>
