<?php
include '../middleware/auth.php';
include '../config/database.php';

checkAdmin();

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin - SIPPEGADAIAN</title>

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
    background:#f4f7fb;
}

/* LAYOUT */

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

.menu{
    margin-top:30px;
}

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

/* MAIN */

.main{
    margin-left:260px;
    width:100%;
    padding:40px;
}

/* TOPBAR */

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.topbar h1{
    color:#071739;
    font-size:34px;
}

.user-box{
    background:white;
    padding:12px 18px;
    border-radius:14px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
    font-weight:600;
    color:#071739;
}

/* CARD */

.card-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:20px;
}

.card{
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card i{
    font-size:32px;
    color:#18a94b;
    margin-bottom:15px;
}

.card h3{
    margin-bottom:10px;
    color:#071739;
}

.card p{
    color:#64748b;
    font-size:14px;
}

.quick-menu{
    margin-top:40px;
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
}

.quick-menu h2{
    margin-bottom:20px;
    color:#071739;
}

.quick-links{
    display:flex;
    flex-wrap:wrap;
    gap:15px;
}

.quick-links a{
    background:#18a94b;
    color:white;
    text-decoration:none;
    padding:14px 22px;
    border-radius:12px;
    transition:0.3s;
    font-weight:600;
}

.quick-links a:hover{
    transform:translateY(-3px);
    background:#12913d;
}

</style>
</head>
<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="logo">
            <img src="../assets/logo.png">
            <h2>SIPPEGADAIAN</h2>
            <p>Admin Dashboard</p>
        </div>

        <div class="menu">

            <a href="admin.php" class="active">
                <i class="fas fa-home"></i>
                Dashboard
            </a>

            <a href="../pages/pengajuan.php">
                <i class="fa-solid fa-file-circle-plus"></i>
                Form Pengajuan
            </a>

            <a href="../pages/approval.php">
                <i class="fas fa-check-circle"></i>
                Approval Pengajuan
            </a>

            <a href="../pages/riwayat.php">
                <i class="fas fa-clock-rotate-left"></i>
                Riwayat
            </a>

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

            <div>
                <h1>Dashboard Admin</h1>
                <p style="color:#64748b;margin-top:8px;">
                    Kelola pengajuan pinjaman SIPPEGADAIAN
                </p>
            </div>

            <div class="user-box">
                <i class="fas fa-user-shield"></i>
                <?= htmlspecialchars($user['name']); ?>
            </div>

        </div>

        <!-- CARD -->
        <div class="card-grid">

            <div class="card">
                <i class="fas fa-file-circle-plus"></i>
                <h3>Pengajuan Baru</h3>
                <p>Kelola dan buat pengajuan pinjaman baru.</p>
            </div>

            <div class="card">
                <i class="fas fa-check-double"></i>
                <h3>Approval</h3>
                <p>Verifikasi dan approval pengajuan user.</p>
            </div>

            <div class="card">
                <i class="fas fa-chart-line"></i>
                <h3>Monitoring</h3>
                <p>Pantau seluruh aktivitas pengajuan.</p>
            </div>

        </div>

        <!-- QUICK MENU -->
        <div class="quick-menu">

            <h2>Quick Access</h2>

            <div class="quick-links">

                <a href="../pages/pengajuan.php">
                    Buat Pengajuan
                </a>

                <a href="../pages/approval.php">
                    Kelola Approval
                </a>

                <a href="../pages/riwayat.php">
                    Riwayat Pengajuan
                </a>

            </div>

        </div>

    </div>

</div>

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