<?php

include '../config/database.php';
include '../middleware/auth.php';

checkLogin();

$user = $_SESSION['user'];
$user_id = $user['id'];
$role = $user['role'];

/* ADMIN LIHAT SEMUA DATA */
/* USER HANYA LIHAT DATA SENDIRI */

if($role == "admin"){

    $query = mysqli_query($conn,"
    SELECT *
    FROM pengajuan
    ORDER BY id DESC
    ");

    $total = mysqli_num_rows(
    mysqli_query($conn,"
    SELECT * FROM pengajuan
    "));

    $pending = mysqli_num_rows(
    mysqli_query($conn,"
    SELECT * FROM pengajuan
    WHERE status='pending'
    "));

    $approved = mysqli_num_rows(
    mysqli_query($conn,"
    SELECT * FROM pengajuan
    WHERE status='approved'
    "));

    $rejected = mysqli_num_rows(
    mysqli_query($conn,"
    SELECT * FROM pengajuan
    WHERE status='rejected'
    "));

}else{

    $query = mysqli_query($conn,"
    SELECT *
    FROM pengajuan
    WHERE user_id='$user_id'
    ORDER BY id DESC
    ");

    $total = mysqli_num_rows($query);

    $pending = mysqli_num_rows(
    mysqli_query($conn,"
    SELECT *
    FROM pengajuan
    WHERE user_id='$user_id'
    AND status='pending'
    "));

    $approved = mysqli_num_rows(
    mysqli_query($conn,"
    SELECT *
    FROM pengajuan
    WHERE user_id='$user_id'
    AND status='approved'
    "));

    $rejected = mysqli_num_rows(
    mysqli_query($conn,"
    SELECT *
    FROM pengajuan
    WHERE user_id='$user_id'
    AND status='rejected'
    "));
}

$total = mysqli_num_rows($query);

$pending = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM pengajuan
WHERE user_id='$user_id'
AND status='pending'"
));

$approved = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM pengajuan
WHERE user_id='$user_id'
AND status='approved'"
));

$rejected = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM pengajuan
WHERE user_id='$user_id'
AND status='rejected'"
));

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
Riwayat Pengajuan - SIPPEGADAIAN
</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f1f5f9;
    display:flex;
    min-height:100vh;
    overflow-x:hidden;
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
    animation:fadeUp .7s ease;
}

.logo{
    text-align:center;
    margin-bottom:45px;
}

.logo img{
    width:90px;
    margin-bottom:12px;
}

.logo h2{
    font-size:25px;
    font-weight:700;
}

.logo p{
    font-size:13px;
    color:#cbd5e1;
}

/* NAV */

.nav{
    margin-top:30px;
}

.nav a{
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
    position:relative;
    overflow:hidden;
}

.nav a::before{
    content:'';
    position:absolute;
    inset:0;
    background:rgba(255,255,255,.06);
    opacity:0;
    transition:.3s;
}

.nav a:hover::before{
    opacity:1;
}

.nav a:hover,
.nav a.active{
    background:#16a34a;
    transform:translateX(5px);
    box-shadow:0 10px 20px rgba(22,163,74,0.25);
}

/* MAIN */

.main{
    margin-left:270px;
    width:calc(100% - 270px);
    padding:35px;
}

/* TOPBAR */

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    animation:fadeUp .7s ease;
}

.page-title h1{
    font-size:42px;
    color:#0f172a;
    margin-bottom:8px;
    font-weight:800;
}

.page-title p{
    color:#64748b;
    font-size:15px;
}

.profile{
    background:white;
    padding:12px 18px;
    border-radius:18px;
    font-weight:600;
    box-shadow:0 10px 30px rgba(0,0,0,.05);
    display:flex;
    align-items:center;
    gap:10px;
}

/* STATS */

.stats{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:24px;
    margin-bottom:30px;
}

.card{
    background:white;
    border-radius:24px;
    padding:26px;
    box-shadow:0 10px 30px rgba(0,0,0,.05);
    transition:.35s;
    animation:fadeScale .7s ease;
}

.card i{
    width:58px;
    height:58px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    margin-bottom:20px;
    color:white;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:0 20px 40px rgba(0,0,0,.08);
}

.green{
    background:#16a34a;
}

.yellow{
    background:#f59e0b;
}

.blue{
    background:#2563eb;
}

.red{
    background:#ef4444;
}

.card h2{
    font-size:42px;
    margin-bottom:6px;
    color:#0f172a;
    font-weight:800;
}

.card p{
    color:#64748b;
}

/* TABLE */

.table-card{
    background:white;
    border-radius:24px;
    padding:30px;
    box-shadow:0 10px 30px rgba(0,0,0,.05);
    animation:fadeUp .8s ease;
}

.table-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    flex-wrap:wrap;
    gap:20px;
    margin-bottom:25px;
}

.table-actions{
    display:flex;
    align-items:center;
    gap:14px;
    flex-wrap:wrap;
}

.table-title{
    font-size:24px;
    color:#0f172a;
    margin-bottom:6px;
    font-weight:800;
}

.table-subtitle{
    color:#64748b;
    font-size:14px;
}

.search-box{
    position:relative;
}

.search-box input{
    width:240px;
    padding:14px 18px 14px 45px;
    border:1px solid #cbd5e1;
    border-radius:14px;
    outline:none;
    background:white;
}

.search-box input{
    width:220px;
}

.table-wrapper{
    width:100%;
    overflow-x:auto;
    border-radius:18px;
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#f8fafc;
}

th{
    text-align:left;
    padding:18px;
    color:#475569;
    border-bottom:1px solid #e2e8f0;
    font-size:14px;
    font-weight:700;
}

td{
    padding:18px;
    border-bottom:1px solid #f1f5f9;
    color:#0f172a;
    font-size:14px;
}

tbody tr{
    transition:.2s;
}

tbody tr:hover{
    background:#f8fafc;
    transform:scale(1.002);
}

/* BADGE */

.badge{
    padding:10px 16px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
    display:inline-block;
}

.pending{
    background:#fef3c7;
    color:#92400e;
}

.approved{
    background:#dcfce7;
    color:#166534;
}

.rejected{
    background:#fee2e2;
    color:#991b1b;
}

.score-high{
    background:#dcfce7;
    color:#166534;
}

.score-medium{
    background:#fef3c7;
    color:#92400e;
}

.score-low{
    background:#fee2e2;
    color:#991b1b;
}

/* BUTTON */

.detail-btn{
    text-decoration:none;
    padding:10px 16px;
    border-radius:12px;
    background:#dbeafe;
    color:#1d4ed8;
    font-weight:600;
    display:inline-block;
}

/* EMPTY */

.empty{
    text-align:center;
    padding:60px 20px;
    color:#94a3b8;
}

/* RESPONSIVE */

@media(max-width:900px){

    body{
        flex-direction:column;
    }

    /* GLOBAL ANIMATION */

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
        transform:scale(.96);
    }

    to{
        opacity:1;
        transform:scale(1);
    }

}

    .sidebar{
        width:100%;
        position:relative;
        min-height:auto;
    }

    .main{
        margin-left:0;
        width:100%;
        padding:20px;
    }

    .topbar{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .table-header{
        flex-direction:column;
        align-items:flex-start;
    }

    .search-box{
        width:100%;
    }

    .search-box input:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,.08);
}

    /* ANIMATION */

@keyframes fadeUp{

    from{
        opacity:0;
        transform:translateY(20px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }

}

.card{
    animation:fadeUp .5s ease;
    transition:.3s;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:0 20px 40px rgba(0,0,0,.08);
}

.table-card{
    animation:fadeUp .7s ease;
}

tbody tr{
    transition:.25s;
}

tbody tr:hover{
    background:#f8fafc;
    transform:scale(1.002);
}

.nav a{
    position:relative;
    overflow:hidden;
}

.nav a::before{
    content:'';
    position:absolute;
    inset:0;
    background:rgba(255,255,255,.05);
    opacity:0;
    transition:.3s;
}

.nav a:hover::before{
    opacity:1;
}

}

/* FILTER */

.filter-select{
    padding:14px 18px;
    border-radius:14px;
    border:1px solid #cbd5e1;
    background:white;
    outline:none;
    font-weight:600;
    color:#0f172a;
    cursor:pointer;
}

.filter-select:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,.1);
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

    <div class="logo">

        <img src="../assets/logo.png">

        <h2>SIPPEGADAIAN</h2>

        <p>Sistem Pengajuan Pinjaman</p>

    </div>

  <div class="nav">

<?php if($_SESSION['user']['role'] == 'admin'): ?>

        <a href="../dashboard/admin.php">

            <i class="fa-solid fa-house"></i>

            Dashboard

        </a>

        <a href="pengajuan.php">

            <i class="fa-solid fa-file-circle-plus"></i>

            Form Pengajuan

        </a>

        <a href="approval.php">

            <i class="fa-solid fa-circle-check"></i>

            Approval Pengajuan

        </a>

        <a class="active"
        href="riwayat.php">

            <i class="fa-solid fa-clock-rotate-left"></i>

            Riwayat

        </a>

<?php else: ?>

        <a href="pengajuan.php">

            <i class="fa-solid fa-file-circle-plus"></i>

            Pengajuan

        </a>

        <a class="active"
        href="riwayat.php">

            <i class="fa-solid fa-clock-rotate-left"></i>

            Riwayat Pengajuan

        </a>

<?php endif; ?>

        <a href="#"
        onclick="openLogoutModal(); return false;">

            <i class="fa-solid fa-right-from-bracket"></i>

            Logout

        </a>

    </div>

</div>

<!-- MAIN -->

<div class="main">

    <div class="topbar">

        <div class="page-title">

            <h1>Riwayat Pengajuan</h1>

            <p>
                Pantau status pengajuan pinjaman anda
            </p>

        </div>

        <div class="profile">

            <i class="fa-solid fa-user"></i>

            <?= htmlspecialchars($user['name']) ?>

        </div>

    </div>

    <!-- STATS -->

    <div class="stats">

        <div class="card">

            <i class="fa-solid fa-file-lines green"></i>

            <h2><?= $total ?></h2>

            <p>Total Pengajuan</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-clock yellow"></i>

            <h2><?= $pending ?></h2>

            <p>Pending</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-circle-check blue"></i>

            <h2><?= $approved ?></h2>

            <p>Approved</p>

        </div>

        <div class="card">

            <i class="fa-solid fa-circle-xmark red"></i>

            <h2><?= $rejected ?></h2>

            <p>Rejected</p>

        </div>

    </div>

    <!-- TABLE -->

    <div class="table-card">

        <div class="table-header">

            <div>

                <h2 class="table-title">
                    History Data Pengajuan
                </h2>

            </div>

            <div class="table-actions">

    <div class="search-box">

        <i class="fa-solid fa-magnifying-glass"></i>

        <input
        type="text"
        id="searchInput"
        placeholder="Cari pengajuan...">

    </div>

    <select id="statusFilter" class="filter-select">

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
        <div class="table-wrapper">

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
                    <th>Detail</th>

                </tr>

            </thead>

            <tbody>

<?php if(mysqli_num_rows($query) > 0): ?>

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

echo '<span class="badge score-high">'.$score.'</span>';

}
elseif($score >= 60){

echo '<span class="badge score-medium">'.$score.'</span>';

}
else{

echo '<span class="badge score-low">'.$score.'</span>';

}

?>

</td>

<td>
<?= htmlspecialchars($row['bi_status'] ?? '-') ?>
</td>

<td>

<?php

$status = $row['status'] ?? 'pending';

if($status == 'approved'){

echo '<span class="badge approved">Approved</span>';

}
elseif($status == 'rejected'){

echo '<span class="badge rejected">Rejected</span>';

}
else{

echo '<span class="badge pending">Pending</span>';

}

?>

</td>

<td>
<?= !empty($row['created_at']) 
? date('d M Y', strtotime($row['created_at'])) 
: '-' ?>
</td>

<td>

<a
class="detail-btn"
href="detail_pengajuan.php?id=<?= $row['id'] ?>">

Detail

</a>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="8">

<div class="empty">

Belum ada pengajuan

</div>

</td>

</tr>

<?php endif; ?>

</tbody>

<script>

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
        row.dataset.name || '';

        const rowStatus =
        row.dataset.status || '';

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