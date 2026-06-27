<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config/database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$error = "";
$success = "";

/* =========================
   FUNCTION SEND EMAIL OTP
========================= */

function sendEmailOTP($email, $name, $otp){

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';

        $mail->SMTPAuth = true;

        /* GMAIL PENGIRIM OTP */
        $mail->Username = 'sippegadaian@gmail.com';

        /* APP PASSWORD GOOGLE */
        $mail->Password = 'ahvdqfryjrkkhkrp';

        $mail->SMTPSecure =
        PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;

        $mail->setFrom(
            'sippegadaian@gmail.com',
            'SIPPEGADAIAN'
        );

        $mail->addAddress($email, $name);

        $mail->isHTML(true);

        $mail->Subject =
        'Kode OTP Login SIPPEGADAIAN';

        $mail->Body = '

        <div style="
            font-family:Segoe UI,sans-serif;
            padding:20px;
        ">

            <h2 style="color:#16a34a;">
                Verifikasi OTP SIPPEGADAIAN
            </h2>

            <p>Halo '.$name.',</p>

            <p>
                Gunakan kode OTP berikut:
            </p>

            <div style="
                font-size:32px;
                font-weight:bold;
                letter-spacing:5px;
                margin:25px 0;
                color:#0f172a;
            ">
                '.$otp.'
            </div>

            <p>
                OTP berlaku selama 1 menit.
            </p>

            <p>
                Jangan berikan kode ini kepada siapa pun.
            </p>

        </div>
        ';

        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;

    }

}

/* =========================
   SESSION CHECK
========================= */

if(!isset($_SESSION['otp_user'])){

    header("Location: login.php");
    exit;

}

$user_id = $_SESSION['otp_user'];

$stmt = $conn->prepare("
    SELECT * FROM users
    WHERE id=?
");

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if(!$user){

    session_destroy();

    header("Location: login.php");

    exit;

}

/* =========================
   TIMER OTP
========================= */

$expired_time =
strtotime($user['otp_expired']);

$current_time = time();

$remaining_time =
$expired_time - $current_time;

if($remaining_time < 0){

    $remaining_time = 0;

}

/* =========================
   VERIFY OTP
========================= */

if(isset($_POST['verify'])){

    $otp_input = trim($_POST['otp']);

    if($otp_input == ""){

        $error = "OTP wajib diisi.";

    }
    elseif(
        !ctype_digit($otp_input)
        ||
        strlen($otp_input) != 6
    ){

        $error = "Format OTP tidak valid.";

    }
    else {

        if($user['otp'] == $otp_input){

            if(
                strtotime($user['otp_expired'])
                >
                time()
            ){

                session_regenerate_id(true);

                $_SESSION['user'] = [

                    'id' => $user['id'],

                    'name' => $user['name'],

                    'email' => $user['email'],

                    'role' => $user['role']

                ];

                    $_SESSION['login'] = true;

                    $_SESSION['user_id'] = $user['id'];

                    $_SESSION['role'] = $user['role'];

                    $_SESSION['name'] = $user['name'];

                    $_SESSION['email'] = $user['email'];

                /* HAPUS OTP */

                $clear = $conn->prepare("
                    UPDATE users
                    SET
                    otp=NULL,
                    otp_expired=NULL
                    WHERE id=?
                ");

                $clear->bind_param(
                    "i",
                    $user_id
                );

                $clear->execute();

                unset($_SESSION['otp_user']);

                if($user['role'] == 'admin'){

                    header(
                "Location: /sippegadaian/dashboard/admin.php"
                );

                } else {

                  header("Location: /sippegadaian/pages/pengajuan.php");

                }

                exit;

            } else {

                $error =
                "OTP sudah kadaluarsa.";

            }

        } else {

            $error = "OTP salah.";

        }

    }

}

/* =========================
   RESEND OTP
========================= */

if(isset($_POST['resend'])){

    $otp =
    random_int(100000, 999999);

    $expired = date(
        "Y-m-d H:i:s",
        strtotime("+60 seconds")
    );

    $update = $conn->prepare("
        UPDATE users
        SET
        otp=?,
        otp_expired=?
        WHERE id=?
    ");

    $update->bind_param(
        "ssi",
        $otp,
        $expired,
        $user_id
    );

    $update->execute();

    /* KIRIM EMAIL */

    $sendEmail = sendEmailOTP(
        $user['email'],
        $user['name'],
        $otp
    );

    if($sendEmail){

        $success =
        "OTP baru berhasil dikirim ke email.";

    } else {

        $error =
        "Gagal mengirim OTP.";

    }

    header("Refresh:1");

}

/* =========================
   CANCEL OTP
========================= */

if(isset($_POST['cancel'])){

    unset($_SESSION['otp_user']);

    session_regenerate_id(true);

    header("Location: login.php");

    exit;

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Verifikasi OTP - SIPPEGADAIAN</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f4f6f9;
}

.box{
    width:350px;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 20px 50px rgba(0,0,0,0.15);
    text-align:center;
}

.otp-container{
    display:flex;
    justify-content:space-between;
    margin-bottom:15px;
}

.otp-input{
    width:45px;
    height:50px;
    text-align:center;
    font-size:20px;
    border:1px solid #ddd;
    border-radius:10px;
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    margin-bottom:10px;
    transition:.3s;
}

.verify{
    background:#28a745;
    color:white;
}

.verify:hover{
    background:#218838;
}

.resend{
    background:#6c757d;
    color:white;
}

.cancel{
    background:#dc3545;
    color:white;
}

.error{
    color:red;
    margin-bottom:10px;
}

.success{
    color:green;
    margin-bottom:10px;
}

#timer{
    font-size:13px;
    color:#555;
    margin-top:10px;
}

.loading-overlay{
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.65);
    backdrop-filter:blur(5px);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:99999;
}

.loading-box{
    width:320px;
    background:white;
    border-radius:24px;
    padding:35px;
    text-align:center;
    animation:popup .35s ease;
    box-shadow:0 25px 60px rgba(0,0,0,.18);
}

.spinner{
    width:65px;
    height:65px;
    margin:auto;
    border:5px solid #e2e8f0;
    border-top:5px solid #16a34a;
    border-radius:50%;
    animation:spin 1s linear infinite;
    margin-bottom:20px;
}

.loading-box h3{
    font-size:22px;
    margin-bottom:8px;
    color:#0f172a;
}

.loading-box p{
    color:#64748b;
    font-size:14px;
}

@keyframes spin{

    100%{
        transform:rotate(360deg);
    }

}

@keyframes popup{

    from{
        transform:scale(.85);
        opacity:0;
    }

    to{
        transform:scale(1);
        opacity:1;
    }

}

</style>

</head>

<body>

<div class="box">

<h2>Verifikasi OTP</h2>

<br>

<?php if($error){ ?>
<div class="error"><?= $error ?></div>
<?php } ?>

<?php if($success){ ?>
<div class="success"><?= $success ?></div>
<?php } ?>

<form method="POST" id="otpForm">

<div class="otp-container">

    <input
    type="text"
    maxlength="1"
    class="otp-input"
    autofocus>

    <input
    type="text"
    maxlength="1"
    class="otp-input">

    <input
    type="text"
    maxlength="1"
    class="otp-input">

    <input
    type="text"
    maxlength="1"
    class="otp-input">

    <input
    type="text"
    maxlength="1"
    class="otp-input">

    <input
    type="text"
    maxlength="1"
    class="otp-input">

</div>

<input
type="hidden"
name="otp"
id="otp-value">

<button
type="submit"
class="verify"
name="verify"
id="verifyBtn">

    Verifikasi

</button>

</form>

<form method="POST">

<button
class="resend"
id="resendBtn"
name="resend">

    Kirim Ulang OTP

</button>

</form>

<p id="timer"></p>

<br>

<form method="POST">

<button
class="cancel"
name="cancel">

    Batal / Kembali

</button>

</form>

</div>

<!-- LOADING -->

<div
class="loading-overlay"
id="loadingOverlay">

    <div class="loading-box">

        <div class="spinner"></div>

        <h3>Memproses OTP...</h3>

        <p>Mohon tunggu sebentar</p>

    </div>

</div>

<script>

/* OTP INPUT */

const inputs =
document.querySelectorAll(".otp-input");

const otpValue =
document.getElementById("otp-value");

inputs.forEach((input, index) => {

    input.addEventListener(
        "input",
        (e) => {

        let value = e.target.value;

        if(!/^[0-9]$/.test(value)){

            input.value = "";
            return;

        }

        if(index < inputs.length - 1){

            inputs[index + 1].focus();

        }

        updateOTP();

    });

    input.addEventListener(
        "keydown",
        (e) => {

        if(
            e.key === "Backspace"
            &&
            input.value === ""
            &&
            index > 0
        ){

            inputs[index - 1].focus();

        }

    });

});

function updateOTP(){

    let otp = "";

    inputs.forEach(input => {

        otp += input.value;

    });

    otpValue.value = otp;

}

/* TIMER */

let timeLeft =
<?= $remaining_time ?>;

const timer =
document.getElementById("timer");

const resendBtn =
document.getElementById("resendBtn");

function updateTimer() {

    if (timeLeft > 0) {

        let m =
        Math.floor(timeLeft / 60);

        let s =
        timeLeft % 60;

        timer.innerHTML =
        "Kode OTP berlaku selama "
        + m + ":"
        + (s < 10 ? "0" : "") + s;

        resendBtn.disabled = true;

        resendBtn.style.opacity = "0.6";

        resendBtn.innerText = "Tunggu...";

        timeLeft--;

    } else {

        timer.innerHTML =
        "Anda bisa kirim ulang OTP sekarang";

        resendBtn.disabled = false;

        resendBtn.style.opacity = "1";

        resendBtn.innerText =
        "Kirim Ulang OTP";

    }

}

setInterval(updateTimer, 1000);

updateTimer();

/* FORM SUBMIT */

const verifyForm =
document.getElementById("otpForm");

const verifyBtn =
document.getElementById("verifyBtn");

const loadingOverlay =
document.getElementById("loadingOverlay");

verifyForm.addEventListener(
"submit",
function(e){

    updateOTP();

    if(
        otpValue.value.length !== 6
    ){

        e.preventDefault();

        alert("OTP harus 6 digit");

        return;

    }

    loadingOverlay.style.display =
"flex";

verifyBtn.innerText =
"Memproses...";

});

</script>

<?php ob_end_flush(); ?>

</body>
</html>