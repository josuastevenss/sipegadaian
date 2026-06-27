<?php 
include 'config/database.php';

$error = "";
$success = "";

if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $nik = trim($_POST['nik']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $alamat = trim($_POST['alamat']);
    $pekerjaan = trim($_POST['pekerjaan']);

    if(empty($name) || empty($nik) || empty($email) || empty($phone) || empty($password)){
        $error = "Semua field wajib diisi.";
    }
    elseif(!ctype_digit($nik) || strlen($nik) != 16){
        $error = "NIK harus 16 digit angka.";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Format email tidak valid.";
    }
    elseif(!ctype_digit($phone)){
        $error = "No HP hanya boleh angka.";
    }
    else {

        if(substr($phone, 0, 1) == "0"){
            $phone = "62" . substr($phone, 1);
        }

        $check = $conn->prepare("SELECT id FROM users WHERE email=? OR nik=?");
        $check->bind_param("ss", $email, $nik);
        $check->execute();
        $result = $check->get_result();

        if($result->num_rows > 0){
            $error = "Email atau NIK sudah terdaftar.";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (name, nik, email, phone, password, alamat, pekerjaan, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'user', NOW())");
            $stmt->bind_param("sssssss", $name, $nik, $email, $phone, $hash, $alamat, $pekerjaan);

            if($stmt->execute()){
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Gagal registrasi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register SIPPEGADAIAN</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{
    font-family:'Segoe UI',sans-serif;
    overflow:hidden;
}

/* CONTAINER */
.container{
    display:flex;
    height:100vh;
    animation:fadeIn 1s ease;
}

/* LEFT */
.left{
    width:60%;
    background-image:url('assets/background.jpg');
    background-size:cover;
    background-position:center;
    position:relative;
}

/* OVERLAY */
.overlay{
    position:absolute;
    width:100%;
    height:100%;
    background:linear-gradient(135deg, rgba(0,100,0,0.35), rgba(0,60,0,0.6));
    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    color:white;
    text-align:center;
    animation:fadeInUp 1.2s ease;
}

.overlay h1{font-size:38px;margin-bottom:10px;}
.overlay p{font-size:15px;}

/* RIGHT */
.right{
    width:40%;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f4f6f9;
}

/* BOX */
.login-box{
    width:400px;
    background:white;
    padding:30px 30px;
    border-radius:16px;

    box-shadow:
    0 20px 60px rgba(0,0,0,0.15);

    animation:fadeUp .8s cubic-bezier(.22,1,.36,1);

    transition:
    transform .25s ease,
    box-shadow .25s ease;
}

/* HOVER CLEAN CORPORATE */
.login-box:hover{

    transform:translateY(-3px);

    box-shadow:
    0 28px 70px rgba(0,0,0,0.18);
}

/* LOGO */
.logo{
    text-align:center;
    margin-bottom:18px;
}
.logo img{
    width:95px;
    transition:0.3s;
}
.logo img:hover{
    transform:scale(1.1) rotate(2deg);
}

/* TEXT */
.login-box h2{
    text-align:center;
    font-size:22px;
    margin-bottom:6px;
}
.subtitle{
    text-align:center;
    font-size:13px;
    color:#64748b;
    margin-bottom:22px;
    line-height:1.5;
}

/* INPUT */
.login-box input, textarea{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:1px solid #ddd;
    border-radius:10px;
    transition:0.3s;
}

.login-box input:focus, textarea:focus{
    border:1px solid #28a745;
    box-shadow:0 0 8px rgba(40,167,69,0.3);
    transform:scale(1.02);
    outline:none;
}

/* PASSWORD ICON */
.input-group{position:relative;}
.input-group input{padding-right:40px;}

.toggle-password{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
}

/* BUTTON */
.login-box button{
    width:100%;
    padding:12px;
    background:#28a745;
    border:none;
    color:white;
    border-radius:10px;
    cursor:pointer;
    transition:0.3s;
}

.login-box button:hover{
    background:#218838;
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(0,0,0,0.15);
}

.login-box button:disabled{
    background:#aaa;
}

/* MESSAGE */
.error{
    color:red;
    text-align:center;
    margin-bottom:10px;
    animation:shake 0.3s;
}

.success{
    color:green;
    text-align:center;
    margin-bottom:10px;
    animation:fadeIn 0.5s;
}

.link{text-align:center;margin-top:15px;}
.link a{color:#28a745;text-decoration:none;}
.link a:hover{text-decoration:underline;}

small{font-size:12px;margin-top:-5px;margin-bottom:5px;display:block;}
.valid{color:green;}
.invalid{color:red;}

/* ANIMATION */
@keyframes fadeIn{
    from{opacity:0}
    to{opacity:1}
}

@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(28px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }
}

@keyframes zoomBg{
    from{transform:scale(1);}
    to{transform:scale(1.1);}
}

@keyframes shake{
    0%{transform:translateX(0);}
    25%{transform:translateX(-5px);}
    50%{transform:translateX(5px);}
    75%{transform:translateX(-5px);}
    100%{transform:translateX(0);}
}

@media(max-width:900px){
    .left{display:none;}
    .right{width:100%;}
}
</style>
</head>

<body>

<div class="container">

<div class="left">
    <div class="overlay">
        <h1>SIPPEGADAIAN</h1>
        <p>Solusi Digital Pengajuan Pinjaman Cepat & Aman</p>
    </div>
</div>

<div class="right">
<div class="login-box">

<div class="logo">
    <img src="assets/logo.png">
</div>

<h2>Register</h2>
<p class="subtitle">Buat akun baru</p>

<?php if($error){ ?><div class="error"><?= $error ?></div><?php } ?>
<?php if($success){ ?><div class="success"><?= $success ?></div><?php } ?>

<form method="POST">

<input type="text" name="name" placeholder="Nama lengkap" required>

<input type="text" id="nik" name="nik" placeholder="NIK (16 digit)">
<small id="nikMsg"></small>

<input type="email" id="email" name="email" placeholder="Email">
<small id="emailMsg"></small>

<input type="text" id="phone" name="phone" placeholder="No HP">
<small id="phoneMsg"></small>

<div class="input-group">
<input type="password" id="password" name="password" placeholder="Password">
<span class="toggle-password" onclick="togglePassword()">
<i id="eyeIcon" class="fa fa-eye"></i>
</span>
</div>
<small id="passMsg"></small>

<textarea name="alamat" placeholder="Alamat"></textarea>

<input type="text" name="pekerjaan" placeholder="Pekerjaan">

<button id="submitBtn" name="register" disabled>Daftar</button>

</form>

<div class="link">
Sudah punya akun? 
<a href="login.php"><b>Login</b></a>
</div>

</div>
</div>

</div>

<script>
// VALIDASI REALTIME (tetap sama)
let valid = {nik:false,email:false,phone:false,pass:false};

function check(){
    document.getElementById("submitBtn").disabled =
    !(valid.nik && valid.email && valid.phone && valid.pass);
}

// NIK
document.getElementById("nik").addEventListener("input", function(){
    let v = this.value;
    let msg = document.getElementById("nikMsg");

    if(/^[0-9]{16}$/.test(v)){
        msg.innerHTML="✔ NIK valid";
        msg.className="valid";
        valid.nik=true;
    } else {
        msg.innerHTML="NIK harus 16 digit";
        msg.className="invalid";
        valid.nik=false;
    }
    check();
});

// EMAIL
document.getElementById("email").addEventListener("input", function(){
    let v = this.value;
    let msg = document.getElementById("emailMsg");

    if(/^\S+@\S+\.\S+$/.test(v)){
        msg.innerHTML="✔ Email valid";
        msg.className="valid";
        valid.email=true;
    } else {
        msg.innerHTML="Email tidak valid";
        msg.className="invalid";
        valid.email=false;
    }
    check();
});

// PHONE
document.getElementById("phone").addEventListener("input", function(){
    let v = this.value.replace(/[^0-9]/g,'');
    if(v.startsWith("0")) v="62"+v.slice(1);
    this.value=v;

    let msg = document.getElementById("phoneMsg");

    if(v.length>=10){
        msg.innerHTML="✔ Nomor valid";
        msg.className="valid";
        valid.phone=true;
    } else {
        msg.innerHTML="Nomor tidak valid";
        msg.className="invalid";
        valid.phone=false;
    }
    check();
});

// PASSWORD
document.getElementById("password").addEventListener("input", function(){
    let v = this.value;
    let msg = document.getElementById("passMsg");

    if(v.length >= 6){
        msg.innerHTML="✔ Password aman";
        msg.className="valid";
        valid.pass=true;
    } else {
        msg.innerHTML="Minimal 6 karakter";
        msg.className="invalid";
        valid.pass=false;
    }
    check();
});

// TOGGLE PASSWORD
function togglePassword(){
    let pass = document.getElementById("password");
    let icon = document.getElementById("eyeIcon");

    if(pass.type==="password"){
        pass.type="text";
        icon.classList.replace("fa-eye","fa-eye-slash");
    } else {
        pass.type="password";
        icon.classList.replace("fa-eye-slash","fa-eye");
    }
}
</script>

</body>
</html>