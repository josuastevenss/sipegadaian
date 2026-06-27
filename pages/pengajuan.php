<?php
include '../config/database.php';
include '../middleware/auth.php';

checkLogin();
checkUser();

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Pengajuan Pinjaman - SIPPEGADAIAN</title>

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pengajuan Pinjaman - SIPPEGADAIAN</title>

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

/* CARD */
.card{
    background:white;
    border-radius:28px;
    padding:40px;
    box-shadow:0 20px 50px rgba(15,23,42,0.06);
    animation:fadeScale .8s ease;
}

/* PROGRESS */
.progress-wrapper{
    display:flex;
    gap:15px;
    margin-bottom:35px;
}

.progress-box{
    flex:1;
    background:#f8fafc;
    border:1px solid #e2e8f0;
    border-radius:18px;
    padding:16px;
    text-align:center;
    font-size:13px;
    font-weight:600;
}

.progress-box.active{
    background:#dcfce7;
    border-color:#16a34a;
    color:#166534;
}

/* SECTION */
.section{
    margin-bottom:40px;
}

.section-title{
    font-size:22px;
    margin-bottom:25px;
    color:#0f172a;
    display:flex;
    align-items:center;
    gap:12px;
    font-weight:700;
}

.section-title i{
    color:#16a34a;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:22px;
}

/* INPUT */
.input-group{
    display:flex;
    flex-direction:column;
}

.input-group label{
    margin-bottom:8px;
    font-size:14px;
    font-weight:600;
    color:#334155;
}

.input-group input,
.input-group textarea,
.input-group select{
    width:100%;
    padding:15px;
    border:1px solid #dbe2ea;
    border-radius:16px;
    font-size:14px;
    background:#f8fafc;
    transition:.3s;
}

.input-group textarea{
    resize:none;
    height:120px;
}

.input-group input:focus,
.input-group textarea:focus,
.input-group select:focus{
    outline:none;
    border-color:#16a34a;
    background:white;
    box-shadow:0 0 0 5px rgba(22,163,74,.10);
    transform:translateY(-1px);
}

.full{
    grid-column:1 / 3;
}

/* VALIDATION */
.error-text{
    color:#dc2626;
    font-size:12px;
    margin-top:6px;
    display:none;
}

.success-text{
    color:#16a34a;
    font-size:12px;
    margin-top:6px;
    display:none;
}

/* UPLOAD */
.upload-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:22px;
}

.upload-box{
    border:2px dashed #d1d9e6;
    border-radius:20px;
    padding:22px;
    background:#f8fafc;
    transition:.3s ease;
}

.upload-box:hover{
    border-color:#16a34a;
    background:#f0fdf4;
    transform:translateY(-2px);
}

.upload-box label{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:12px;
    font-size:14px;
    font-weight:700;
}

.upload-box label i{
    color:#16a34a;
}

.upload-note{
    margin-top:10px;
    font-size:12px;
    color:#64748b;
}

.file-preview{
    margin-top:10px;
    font-size:13px;
    color:#16a34a;
    font-weight:600;
    display:none;
}

/* BUTTON */
.submit-btn{
    width:100%;
    padding:17px;
    border:none;
    border-radius:18px;
    background:#16a34a;
    color:white;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
    transition:.3s ease;
    margin-top:10px;
}

.submit-btn:hover{
    background:#15803d;
    transform:translateY(-3px);
    box-shadow:0 15px 30px rgba(22,163,74,0.25);
}

/* LOADING */
.loading-overlay{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(15,23,42,.7);
    backdrop-filter:blur(5px);
    display:none;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    z-index:99999;
    color:white;
}

.loading-spinner{
    width:70px;
    height:70px;
    border:5px solid rgba(255,255,255,.2);
    border-top:5px solid #22c55e;
    border-radius:50%;
    animation:spin 1s linear infinite;
    margin-bottom:18px;
}

/* SUCCESS */
.success-modal{
    position:fixed;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%) scale(.8);
    background:white;
    width:400px;
    border-radius:24px;
    padding:35px;
    text-align:center;
    box-shadow:0 20px 60px rgba(0,0,0,.2);
    z-index:999999;
    opacity:0;
    visibility:hidden;
    transition:.3s;
}

.success-modal.show{
    opacity:1;
    visibility:visible;
    transform:translate(-50%,-50%) scale(1);
}

.success-modal i{
    font-size:70px;
    color:#16a34a;
    margin-bottom:15px;
}

.success-modal h2{
    margin-bottom:10px;
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

@keyframes spin{
    100%{
        transform:rotate(360deg);
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

    .grid,
    .upload-grid{
        grid-template-columns:1fr;
    }

    .full{
        grid-column:auto;
    }

    .progress-wrapper{
        flex-direction:column;
    }

    .topbar{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .card{
        padding:25px;
    }
}

</style>
</head>

<body>

<!-- LOADING -->
<div class="loading-overlay" id="loadingOverlay">

    <div class="loading-spinner"></div>

    <h3>Mengirim Pengajuan...</h3>

    <p style="margin-top:10px;font-size:14px;">
        Mohon tunggu sebentar
    </p>

</div>

<!-- SUCCESS -->
<div class="success-modal" id="successModal">

    <i class="fa-solid fa-circle-check"></i>

    <h2>Pengajuan Berhasil</h2>

    <p>
        Pengajuan pinjaman berhasil dikirim
        dan sedang diproses admin.
    </p>

</div>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="brand">
        <img src="../assets/logo.png">
        <h2>SIPPEGADAIAN</h2>
        <p>Sistem Pengajuan Pinjaman</p>
    </div>

    <div class="menu">

<?php if($_SESSION['user']['role'] == 'admin'): ?>

    <a href="../dashboard/admin.php">

        <i class="fa-solid fa-house"></i>

        Dashboard

    </a>

    <a class="active"
    href="pengajuan.php">

        <i class="fa-solid fa-file-circle-plus"></i>

        Form Pengajuan

    </a>

    <a href="approval.php">

        <i class="fa-solid fa-circle-check"></i>

        Approval Pengajuan

    </a>

    <a href="riwayat.php">

        <i class="fa-solid fa-clock-rotate-left"></i>

        Riwayat

    </a>

<?php else: ?>

    <a class="active"
    href="pengajuan.php">

        <i class="fa-solid fa-file-circle-plus"></i>

        Pengajuan

    </a>

    <a href="riwayat.php">

        <i class="fa-solid fa-clock-rotate-left"></i>

        Riwayat Pengajuan

    </a>

<?php endif; ?>

   <a href="#"
onclick="openLogoutModal()">

    <i class="fa-solid fa-right-from-bracket"></i>

    Logout

</a>

</div>

</div>

<!-- CONTENT -->
<div class="content">

    <div class="topbar">

        <div>
            <h1>Form Pengajuan</h1>
            <p>
                Silakan lengkapi data pengajuan pinjaman
            </p>
        </div>

        <div class="user-box">
            👤 <?= htmlspecialchars($user['name']) ?>
        </div>

    </div>

    <!-- PROGRESS -->
    <div class="progress-wrapper">

        <div class="progress-box active">
            1. Data Diri
        </div>

        <div class="progress-box active">
            2. Data Pengajuan
        </div>

        <div class="progress-box active">
            3. Upload Dokumen
        </div>

    </div>

    <!-- CARD -->
    <div class="card">

        <form
        action="proses_pengajuan.php"
        method="POST"
        enctype="multipart/form-data"
        id="pengajuanForm">

        <!-- DATA DIRI -->
        <div class="section">

            <div class="section-title">
                <i class="fa-solid fa-user"></i>
                Data Diri
            </div>

            <div class="grid">

                <div class="input-group">
                    <label>Nama Lengkap</label>

                    <input
                    type="text"
                    name="nama_lengkap"
                    value="<?= htmlspecialchars($user['name']) ?>"
                    required>
                </div>

                <div class="input-group">
                    <label>NIK</label>

                    <input
                    type="text"
                    name="nik"
                    id="nik"
                    maxlength="16"
                    required>

                    <div class="error-text" id="nikError">
                        NIK harus 16 digit angka
                    </div>

                    <div class="success-text" id="nikSuccess">
                        NIK valid
                    </div>

                </div>

                <div class="input-group full">
                    <label>Alamat Sesuai KTP</label>

                    <textarea
                    name="alamat_ktp"
                    required></textarea>
                </div>

                <div class="input-group full">
                    <label>Alamat Domisili</label>

                    <textarea
                    name="alamat_domisili"
                    required></textarea>
                </div>

                <div class="input-group">
                    <label>Nomor Telepon</label>

                    <input
                    type="text"
                    name="no_telp"
                    id="phone"
                    required>

                    <div class="error-text" id="phoneError">
                        Nomor HP tidak valid
                    </div>

                    <div class="success-text" id="phoneSuccess">
                        Nomor HP valid
                    </div>

                </div>

                <div class="input-group">
                    <label>Nama Ibu Kandung</label>

                    <input
                    type="text"
                    name="ibu_kandung"
                    required>
                </div>

            </div>

        </div>

        <!-- DATA PENGAJUAN -->
        <div class="section">

            <div class="section-title">
                <i class="fa-solid fa-building-columns"></i>
                Data Pengajuan
            </div>

            <div class="grid">

                <div class="input-group">

                    <label>Cabang Pengajuan</label>

                    <select name="cabang" required>

                        <option value="">
                            -- Pilih Cabang --
                        </option>

                        <option>Pegadaian Cabang Sidoarjo</option>
                        <option>Pegadaian UPC Tulangan</option>
                        <option>Pegadaian UPC Tanggulangin</option>
                        <option>Pegadaian UPC Porong</option>
                        <option>Pegadaian UPC Bhayangkari</option>
                        <option>Pegadaian UPC Kota</option>
                        <option>Pegadaian UPC Suko</option>
                        <option>Pegadaian UPC Buduran</option>

                    </select>

                </div>

                <div class="input-group">

                    <label>Jenis Pengajuan</label>

                    <select name="jenis_pengajuan" required>

                        <option value="">
                            -- Pilih Pengajuan --
                        </option>

                        <option>Kupedes</option>
                        <option>Rtt</option>
                        <option>Kreasi Ultra Mikro</option>
                        <option>Kreasi Multiguna</option>
                        <option>KUR</option>

                    </select>

                </div>

            </div>

        </div>

        <!-- BI CHECKING -->
<div class="section">

    <div class="section-title">
        <i class="fa-solid fa-chart-line"></i>
        Analisa BI Checking / SLIK
    </div>

    <div class="grid">

        <div class="input-group">
            <label>Penghasilan Bulanan</label>

            <input
            type="number"
            name="penghasilan"
            placeholder="Contoh: 5000000"
            required>
        </div>

        <div class="input-group">
            <label>Pengeluaran Bulanan</label>

            <input
            type="number"
            name="pengeluaran"
            placeholder="Contoh: 2500000"
            required>
        </div>

        <div class="input-group">
            <label>Cicilan Aktif Per Bulan</label>

            <input
            type="number"
            name="cicilan_lain"
            placeholder="Contoh: 1000000"
            required>
        </div>

        <div class="input-group">
            <label>Status Rumah</label>

            <select name="status_rumah" required>
                <option value="">
                    -- Pilih Status --
                </option>

                <option>Milik Sendiri</option>
                <option>Kontrak</option>
                <option>Rumah Orang Tua</option>
            </select>
        </div>

        <div class="input-group">
            <label>Memiliki Kredit Lain?</label>

            <select name="kredit_lain" required>

                <option value="">
                    -- Pilih --
                </option>

                <option>Tidak</option>
                <option>Ya</option>

            </select>
        </div>

        <div class="input-group">
            <label>Nama Bank / Leasing</label>

            <input
            type="text"
            name="nama_bank"
            placeholder="Opsional">
        </div>

        <div class="input-group full">
            <label>Lama Usaha / Bekerja</label>

            <input
            type="text"
            name="lama_usaha"
            placeholder="Contoh: 3 Tahun"
            required>
        </div>

    </div>

</div>

        <!-- UPLOAD -->
        <div class="section">

            <div class="section-title">
                <i class="fa-solid fa-file-arrow-up"></i>
                Upload Dokumen
            </div>

            <div class="upload-grid">

<?php
$uploads = [
    ['ktp_file','fa-id-card','KTP Suami / Istri'],
    ['kk_file','fa-users','Kartu Keluarga (KK)'],
    ['surat_nikah_file','fa-ring','Surat Nikah / Cerai'],
    ['domisili_file','fa-house','Surat Domisili'],
    ['sku_file','fa-briefcase','Surat Keterangan Usaha'],
    ['pbb_file','fa-file-invoice','PBB'],
    ['listrik_file','fa-bolt','Rekening Listrik']
];

foreach($uploads as $upload):
?>

<div class="upload-box">

    <label>
        <i class="fa-solid <?= $upload[1] ?>"></i>
        <?= $upload[2] ?>
    </label>

    <input
    type="file"
    name="<?= $upload[0] ?>"
    accept=".jpg,.jpeg,.png,.pdf"
    required
    class="file-input">

    <div class="file-preview"></div>

    <div class="upload-note">
        Format: JPG, PNG, PDF
    </div>

</div>

<?php endforeach; ?>

            </div>

        </div>

        <button
        type="submit"
        class="submit-btn"
        id="submitBtn">

            <i class="fa-solid fa-paper-plane"></i>
            Ajukan Sekarang

        </button>

        </form>

    </div>

</div>

</div>

<script>

/* VALIDASI NIK */
const nikInput =
document.getElementById('nik');

const nikError =
document.getElementById('nikError');

const nikSuccess =
document.getElementById('nikSuccess');

nikInput.addEventListener('input', function(){

    if(/^[0-9]{16}$/.test(this.value)){

        nikError.style.display = 'none';
        nikSuccess.style.display = 'block';

    }else{

        nikError.style.display = 'block';
        nikSuccess.style.display = 'none';

    }

});

/* VALIDASI HP */
const phoneInput =
document.getElementById('phone');

const phoneError =
document.getElementById('phoneError');

const phoneSuccess =
document.getElementById('phoneSuccess');

phoneInput.addEventListener('input', function(){

    if(/^[0-9]{10,15}$/.test(this.value)){

        phoneError.style.display = 'none';
        phoneSuccess.style.display = 'block';

    }else{

        phoneError.style.display = 'block';
        phoneSuccess.style.display = 'none';

    }

});

/* FILE PREVIEW */
document
.querySelectorAll('.file-input')
.forEach(input => {

    input.addEventListener('change', function(){

        const preview =
        this.nextElementSibling;

        if(this.files.length > 0){

            const file =
            this.files[0];

            const size =
            (file.size / 1024 / 1024).toFixed(2);

            preview.style.display = 'block';

            preview.innerHTML =
            '✔ ' +
            file.name +
            ' (' + size + ' MB)';

        }

    });

});

/* SUBMIT */
document
.getElementById('pengajuanForm')
.addEventListener('submit', function(e){

    e.preventDefault();

    document
    .getElementById('loadingOverlay')
    .style.display = 'flex';

    setTimeout(() => {

        document
        .getElementById('loadingOverlay')
        .style.display = 'none';

        document
        .getElementById('successModal')
        .classList.add('show');

        setTimeout(() => {

            this.submit();

        }, 1500);

    }, 2000);

});

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