<?php
include '../middleware/auth.php';
include '../config/database.php';

checkAdmin();

$user = $_SESSION['user'];


/* =======================
   PROSES APPROVE
======================= */

if(isset($_GET['approve'])){

    $id = (int)$_GET['approve'];

    mysqli_query(
        $conn,
        "UPDATE pengajuan
         SET status='approved'
         WHERE id='$id'"
    );

    header("Location: approval.php");
    exit;
}


/* =======================
   PROSES REJECT
======================= */

if(isset($_GET['reject'])){

    $id = (int)$_GET['reject'];

    mysqli_query(
        $conn,
        "UPDATE pengajuan
         SET status='rejected'
         WHERE id='$id'"
    );

    header("Location: approval.php");
    exit;
}

/* =======================
   AMBIL DATA
======================= */

$query = mysqli_query(
$conn,
"SELECT *
FROM pengajuan
ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Approval Pengajuan - SIPPEGADAIAN</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    background:#f1f5f9;
    color:#1e293b;
    overflow-x:hidden;
}

/* CONTAINER */
.container{
    display:flex;
    min-height:100vh;
}

/* SIDEBAR */
.sidebar{
    width:270px;
    background:#0f172a;
    color:white;
    padding:30px 20px;
    position:fixed;
    height:100vh;
    box-shadow:10px 0 30px rgba(0,0,0,0.08);
}

.brand{
    text-align:center;
    margin-bottom:45px;
}

.brand img{
    width:90px;
    margin-bottom:12px;
}

.brand h2{
    font-size:25px;
    font-weight:700;
}

.brand p{
    font-size:13px;
    color:#cbd5e1;
}

/* MENU */
.menu a{
    display:flex;
    align-items:center;
    gap:12px;
    padding:15px;
    margin-bottom:14px;
    border-radius:14px;
    text-decoration:none;
    color:white;
    transition:.3s ease;
    font-weight:500;
}

.menu a:hover,
.menu a.active{
    background:#16a34a;
    transform:translateX(5px);
    box-shadow:0 10px 20px rgba(22,163,74,0.25);
}

/* CONTENT */
.content{
    margin-left:270px;
    width:calc(100% - 270px);
    padding:35px;
    animation:fadeUp .8s ease;
}

/* TOPBAR */
.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:35px;
}

.topbar h1{
    font-size:34px;
    margin-bottom:6px;
}

.topbar p{
    color:#64748b;
    font-size:14px;
}

.user-box{
    background:white;
    padding:14px 20px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,0.06);
    font-size:14px;
    font-weight:600;
}

/* STATS */
.stats{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-bottom:30px;
}

.stat-card{
    background:white;
    border-radius:24px;
    padding:28px;
    box-shadow:0 20px 50px rgba(15,23,42,0.05);
    transition:.3s;
}

.stat-card:hover{
    transform:translateY(-5px);
}

.stat-icon{
    width:60px;
    height:60px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin-bottom:15px;
    font-size:24px;
    color:white;
}

.green{
    background:#16a34a;
}

.orange{
    background:#f59e0b;
}

.red{
    background:#ef4444;
}

.stat-card h2{
    font-size:30px;
    margin-bottom:5px;
}

.stat-card p{
    color:#64748b;
    font-size:14px;
}

/* CARD */
.card{
    background:white;
    border-radius:28px;
    padding:35px;
    box-shadow:0 20px 50px rgba(15,23,42,0.05);
    animation:fadeScale .8s ease;
}

/* TABLE */
.table-wrapper{
    overflow:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#f8fafc;
}

th{
    padding:18px;
    text-align:left;
    font-size:14px;
    color:#334155;
    font-weight:700;
    border-bottom:1px solid #e2e8f0;
}

td{
    padding:18px;
    border-bottom:1px solid #f1f5f9;
    font-size:14px;
}

tr{
    transition:.2s;
}

tr:hover{
    background:#f8fafc;
}

/* STATUS */
.badge{
    padding:8px 14px;
    border-radius:30px;
    font-size:12px;
    font-weight:700;
    display:inline-block;
}

.badge.pending{
    background:#fef3c7;
    color:#92400e;
}

.badge.approved{
    background:#dcfce7;
    color:#166534;
}

.badge.rejected{
    background:#fee2e2;
    color:#991b1b;
}

/* TABLE HEADER */
.table-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    gap:20px;
}

.table-title{
    font-size:26px;
    margin-bottom:5px;
}

.table-subtitle{
    font-size:13px;
    color:#64748b;
}

/* FILTER */
.filter-group{
    display:flex;
    gap:15px;
    align-items:center;
}

.search-box{
    position:relative;
}

.search-box i{
    position:absolute;
    top:50%;
    left:15px;
    transform:translateY(-50%);
    color:#94a3b8;
}

.search-box input{
    width:260px;
    padding:13px 15px 13px 42px;
    border-radius:14px;
    border:1px solid #dbe2ea;
    background:#f8fafc;
    transition:.3s;
}

.search-box input:focus,
.filter-group select:focus{
    outline:none;
    border-color:#16a34a;
    box-shadow:0 0 0 5px rgba(22,163,74,.08);
    background:white;
}

.filter-group select{
    padding:13px 15px;
    border-radius:14px;
    border:1px solid #dbe2ea;
    background:#f8fafc;
    transition:.3s;
}

/* STICKY TABLE */
thead{
    position:sticky;
    top:0;
    z-index:5;
}

/* BETTER BUTTON */
.btn-approve{
    background:#16a34a;
    color:white;
}

.btn-approve:hover{
    background:#15803d;
}

.btn-reject{
    background:#ef4444;
    color:white;
}

.btn-reject:hover{
    background:#dc2626;
}

.btn-view{
    background:#0ea5e9;
    color:white;
}

.btn-view:hover{
    background:#0284c7;
}

/* TABLE IMPROVEMENT */
tbody tr{
    transition:.25s ease;
}

tbody tr:hover{
    background:#f8fafc;
    transform:scale(1.002);
}

/* EMPTY STATE */
.empty{
    text-align:center;
    padding:80px 20px;
}

.empty i{
    font-size:80px;
    color:#cbd5e1;
    margin-bottom:20px;
}

.empty h2{
    margin-bottom:10px;
}

/* RESPONSIVE */
@media(max-width:900px){

    .table-header{
        flex-direction:column;
        align-items:flex-start;
    }

    .filter-group{
        width:100%;
        flex-direction:column;
    }

    .search-box{
        width:100%;
    }

    .search-box input,
    .filter-group select{
        width:100%;
    }

}

/* ACTION */
.action-group{
    display:flex;
    gap:10px;
}

.btn{
    border:none;
    padding:10px 15px;
    border-radius:12px;
    cursor:pointer;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
    transition:.3s;
}

.btn-view{
    background:#e0f2fe;
    color:#0369a1;
}

.btn-approve{
    background:#dcfce7;
    color:#166534;
}

.btn-reject{
    background:#fee2e2;
    color:#991b1b;
}

.btn:hover{
    transform:translateY(-2px);
}

/* EMPTY */
.empty{
    text-align:center;
    padding:60px 20px;
    color:#94a3b8;
}

.empty i{
    font-size:70px;
    margin-bottom:15px;
}

/* ANIMATION */
@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(25px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

@keyframes fadeScale{
    from{
        opacity:0;
        transform:scale(.97) translateY(30px);
    }
    to{
        opacity:1;
        transform:scale(1) translateY(0);
    }
}

/* RESPONSIVE */
@media(max-width:1000px){

    .sidebar{
        display:none;
    }

    .content{
        margin-left:0;
        width:100%;
        padding:20px;
    }

    .stats{
        grid-template-columns:1fr;
    }

    .topbar{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .card{
        padding:20px;
    }

}

</style>
</head>

<body>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="brand">
        <img src="../assets/logo.png">
        <h2>SIPPEGADAIAN</h2>
        <p>Admin Dashboard</p>
    </div>

    <div class="menu">

        <a href="../dashboard/admin.php">
            <i class="fa-solid fa-house"></i>
            Dashboard
        </a>

        <a href="pengajuan.php">
            <i class="fa-solid fa-file-circle-plus"></i>
            Form Pengajuan
        </a>

        <a href="approval.php" class="active">
            <i class="fa-solid fa-circle-check"></i>
            Approval Pengajuan
        </a>

        <a href="riwayat.php">
            <i class="fa-solid fa-clock-rotate-left"></i>
            Riwayat
        </a>

        <a href="#"
            onclick="openLogoutModal(); return false;">
            <i class="fa-solid fa-right-from-bracket"></i>  
            Logout
        </a>

    </div>

</div>

<!-- CONTENT -->
<div class="content">

    <!-- TOPBAR -->
    <div class="topbar">

        <div>
            <h1>Approval Pengajuan</h1>
            <p>
                Kelola approval pengajuan pinjaman user
            </p>
        </div>

        <div class="user-box">
            👤 <?= htmlspecialchars($user['name']) ?>
        </div>

    </div>

    <!-- STATS -->
    <div class="stats">

        <div class="stat-card">

            <div class="stat-icon green">
                <i class="fa-solid fa-file-circle-check"></i>
            </div>

            <h2>
                <?php
                $total = mysqli_num_rows($query);
                echo $total;
                ?>
            </h2>

            <p>Total Pengajuan</p>

        </div>

        <div class="stat-card">

            <div class="stat-icon orange">
                <i class="fa-solid fa-clock"></i>
            </div>

            <h2>
                <?php
                $pending = mysqli_query($conn,"
                SELECT * FROM pengajuan
                WHERE status='pending'
                ");

                echo mysqli_num_rows($pending);
                ?>
            </h2>

            <p>Menunggu Approval</p>

        </div>

        <div class="stat-card">

            <div class="stat-icon red">
                <i class="fa-solid fa-xmark"></i>
            </div>

            <h2>
                <?php
                $reject = mysqli_query($conn,"
                SELECT * FROM pengajuan
                WHERE status='rejected'
                ");

                echo mysqli_num_rows($reject);
                ?>
            </h2>

            <p>Rejected</p>

        </div>

    </div>

    <!-- TABLE -->
    <div class="card">

        <div class="table-header">

    <div>

        <p class="table-subtitle">
            Monitoring approval pengajuan user
        </p>
    </div>

    <div class="filter-group">

        <div class="search-box">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input
            type="text"
            id="searchInput"
            placeholder="Cari nama pengajuan...">

        </div>

        <select id="statusFilter">

            <option value="">
                Semua Status
            </option>

            <option value="pending">
                Pending
            </option>

            <option value="approved">
                Approved
            </option>

            <option value="rejected">
                Rejected
            </option>

        </select>

    </div>

</div>

            <h2 style="font-size:24px;">
                Approval Pengajuan
            </h2>

        </div>

        <div class="table-wrapper">

<?php if(mysqli_num_rows($query) > 0): ?>

<table>

<thead>
<tr>
    <th>Nama</th>
    <th>Cabang</th>
    <th>Jenis</th>

    <th>BI Score</th>
    <th>Risiko</th>

    <th>Status</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($query)): ?>

<tr
data-status="<?= strtolower($row['status'] ?? '') ?>"
data-name="<?= strtolower($row['nama_lengkap'] ?? '') ?>">

<td>
<?= htmlspecialchars($row['nama_lengkap'] ?? '-') ?>
</td>

<td>
<?= htmlspecialchars($row['cabang'] ?? '-') ?>
</td>

<td>
<?= htmlspecialchars($row['jenis_pengajuan'] ?? '-') ?>
</td>

<td>
<?php
$score = $row['bi_score'] ?? 0;

if($score >= 80){
    echo '<span class="score-high">'.$score.'</span>';
}
elseif($score >= 60){
    echo '<span class="score-medium">'.$score.'</span>';
}
else{
    echo '<span class="score-low">'.$score.'</span>';
}
?>
</td>

<td>
<?= htmlspecialchars($row['bi_status'] ?? '-') ?>
</td>

<td>
<span class="badge <?= strtolower($row['status']) ?>">
<?= ucfirst($row['status']) ?>
</span>
</td>

<td>
<?= date('d M Y', strtotime($row['created_at'])) ?>
</td>

<td>
<div class="action-group">

<a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-view">
Detail
</a>

<?php if($row['status']=="pending"): ?>

<a href="?approve=<?= $row['id'] ?>"
class="btn btn-approve">
Approve
</a>

<a href="?reject=<?= $row['id'] ?>"
class="btn btn-reject">
Reject
</a>

<?php endif; ?>

</div>
</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

<?php else: ?>

<div class="empty">

    <i class="fa-solid fa-folder-open"></i>

    <h2>Belum Ada Pengajuan</h2>

    <p style="margin-top:10px;">
        Data pengajuan user akan muncul di sini
    </p>

</div>

<?php endif; ?>

        </div>

    </div>

</div>

</div>

<script>

/* SEARCH */
const searchInput =
document.getElementById('searchInput');

const statusFilter =
document.getElementById('statusFilter');

const rows =
document.querySelectorAll('tbody tr');

function filterTable(){

    const search =
    searchInput.value.toLowerCase();

    const status =
    statusFilter.value.toLowerCase();

    rows.forEach(row => {

        const name =
        row.dataset.name;

        const rowStatus =
        row.dataset.status;

        const matchSearch =
        name.includes(search);

        const matchStatus =
        status === '' ||
        rowStatus === status;

        if(matchSearch && matchStatus){

            row.style.display = '';

        }else{

            row.style.display = 'none';

        }

    });

}

searchInput.addEventListener(
'keyup',
filterTable
);

statusFilter.addEventListener(
'change',
filterTable
);

</script>

<!-- LOGOUT MODAL -->

<div class="logout-modal" id="logoutModal">

    <div class="logout-box">

        <div class="logout-icon">
            !
        </div>

        <h2>Apakah Anda yakin?</h2>

        <p>
            Anda akan keluar dari sistem.
        </p>

        <div class="logout-actions">

            <button
            class="yes-btn"
            onclick="confirmLogout()">

                Ya

            </button>

            <button
            class="cancel-btn"
            onclick="closeLogoutModal()">

                Tidak

            </button>

        </div>

    </div>

</div>

<style>

.logout-modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.45);
    display:flex;
    justify-content:center;
    align-items:center;
    opacity:0;
    visibility:hidden;
    transition:.3s;
    z-index:9999;
}

.logout-modal.active{
    opacity:1;
    visibility:visible;
}

.logout-box{
    width:420px;
    background:white;
    border-radius:20px;
    padding:40px;
    text-align:center;
}

.logout-icon{
    width:90px;
    height:90px;
    border-radius:50%;
    border:5px solid #f8c291;
    margin:auto;
    margin-bottom:20px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:50px;
    color:#f8c291;
    font-weight:bold;
}

.logout-box h2{
    font-size:32px;
    margin-bottom:10px;
}

.logout-box p{
    color:#666;
    margin-bottom:30px;
}

.logout-actions{
    display:flex;
    justify-content:center;
    gap:15px;
}

.logout-actions button{
    border:none;
    padding:12px 28px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
}

.yes-btn{
    background:#3498db;
    color:white;
}

.cancel-btn{
    background:#ef4444;
    color:white;
}

</style>

<script>

function openLogoutModal(){

    document
    .getElementById('logoutModal')
    .classList.add('active');

}

function closeLogoutModal(){

    document
    .getElementById('logoutModal')
    .classList.remove('active');

}

function confirmLogout(){

    window.location.href =
    "../logout.php";

}

</script>

</body>
</html>