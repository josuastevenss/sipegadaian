<?php

include '../config/database.php';
include '../middleware/auth.php';

checkLogin();
checkUser();

$user_id = $_SESSION['user']['id'];

/* DATA DIRI */

$nama_lengkap = $_POST['nama_lengkap'];
$nik = $_POST['nik'];
$alamat_ktp = $_POST['alamat_ktp'];
$alamat_domisili = $_POST['alamat_domisili'];
$no_telp = $_POST['no_telp'];
$ibu_kandung = $_POST['ibu_kandung'];

/* DATA PENGAJUAN */

$cabang = $_POST['cabang'];
$jenis_pengajuan = $_POST['jenis_pengajuan'];

/* BI CHECKING */

$penghasilan = $_POST['penghasilan'];
$pengeluaran = $_POST['pengeluaran'];
$cicilan_lain = $_POST['cicilan_lain'];
$status_rumah = $_POST['status_rumah'];
$kredit_lain = $_POST['kredit_lain'];
$nama_bank = $_POST['nama_bank'];
$lama_usaha = $_POST['lama_usaha'];

/* HITUNG BI SCORE */

$bi_score = 100;

if($pengeluaran > ($penghasilan*0.7)){

    $bi_score -= 20;

}

if($cicilan_lain > ($penghasilan*0.3)){

    $bi_score -= 30;

}

if($kredit_lain == "Ya"){

    $bi_score -= 15;

}

if($status_rumah != "Milik Sendiri"){

    $bi_score -= 10;

}

/* STATUS BI */

if($bi_score >= 80){

    $bi_status = "Risiko Rendah";

}
elseif($bi_score >= 60){

    $bi_status = "Risiko Sedang";

}
else{

    $bi_status = "Risiko Tinggi";

}

/* INSERT */

$sql = "

INSERT INTO pengajuan(

user_id,
nama_lengkap,
nik,
alamat_ktp,
alamat_domisili,
no_telp,
ibu_kandung,
cabang,
jenis_pengajuan,
penghasilan,
pengeluaran,
cicilan_lain,
status_rumah,
kredit_lain,
nama_bank,
lama_usaha,
bi_score,
bi_status

)

VALUES(

'$user_id',
'$nama_lengkap',
'$nik',
'$alamat_ktp',
'$alamat_domisili',
'$no_telp',
'$ibu_kandung',
'$cabang',
'$jenis_pengajuan',
'$penghasilan',
'$pengeluaran',
'$cicilan_lain',
'$status_rumah',
'$kredit_lain',
'$nama_bank',
'$lama_usaha',
'$bi_score',
'$bi_status'

)

";

mysqli_query($conn,$sql);

header("Location: riwayat.php");

exit;

?>