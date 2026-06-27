<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config/database.php';

$error = "";

if(isset($_SESSION['user'])){
    if($_SESSION['user']['role'] == 'admin'){
        header("Location: dashboard/admin.php");
    } else {
        header("Location: dashboard/user.php");
    }
    exit;
}

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if($email == "" || $password == ""){
        $error = "Email dan password wajib diisi.";
    } else {

        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows === 1){

            $user = $result->fetch_assoc();

            if(password_verify($password, $user['password'])){

                // LOGIN ADMIN TANPA OTP
                if($user['role'] == 'admin'){

                    session_regenerate_id(true);

                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'role' => $user['role']
                    ];

                    header("Location: dashboard/admin.php");
                    exit;
                }

                // OTP USER
                $otp = rand(100000,999999);

                $expired = date(
                    "Y-m-d H:i:s",
                    strtotime("+60 seconds")
                );

                $update = $conn->prepare("
                    UPDATE users 
                    SET otp=?, otp_expired=? 
                    WHERE id=?
                ");

                $update->bind_param(
                    "ssi",
                    $otp,
                    $expired,
                    $user['id']
                );

                $update->execute();

                $_SESSION['otp_user'] = $user['id'];

                header("Location: otp.php");
                exit;

            } else {
                $error = "Email atau password salah.";
            }

        } else {
            $error = "Email tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login SIPPEGADAIAN</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    background:#f4f6f9;
}

/* CONTAINER */
.container{
    display:flex;
    height:100vh;
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

.overlay h1{
    font-size:34px;
    margin-bottom:10px;
}

.overlay p{
    font-size:14px;
}

/* RIGHT */
.right{
    width:40%;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f4f6f9;
}

/* LOGIN BOX */
.login-box{
    width:400px;

    background:white;

    padding:40px 30px;

    border-radius:16px;

    box-shadow:
    0 20px 60px rgba(0,0,0,0.15);

    animation:fadeUp .5s ease;

    transition:.25s ease;
}

/* HOVER CLEAN */
.login-box:hover{
    transform:translateY(-2px);
}

/* LOGO */
.logo{
    text-align:center;
    margin-bottom:15px;
}

.logo img{
    width:80px;
}

/* TITLE */
.login-box h2{
    text-align:center;
}

.subtitle{
    text-align:center;
    font-size:13px;
    color:#64748b;
    margin-bottom:20px;
}

/* INPUT */
.login-box input{
    width:100%;

    padding:12px;

    margin:10px 0;

    border:1px solid #ddd;

    border-radius:10px;

    transition:.2s ease;
}

/* INPUT FOCUS */
.login-box input:focus{
    border-color:#16a34a;

    box-shadow:
    0 0 0 3px rgba(22,163,74,.08);

    outline:none;
}

/* PASSWORD ICON */
.input-group{
    position:relative;
}

.input-group input{
    padding-right:40px;
}

.toggle-password{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    color:#888;
}

/* REMEMBER */
.remember-me{
    display:flex;
    justify-content:center;
    margin-bottom:12px;
}

.remember-me label{
    display:flex;
    align-items:center;
    gap:6px;
    font-size:13px;
    cursor:pointer;
}

.remember-me input{
    accent-color:#16a34a;
}

/* BUTTON */
.login-box button{
    width:100%;

    padding:12px;

    background:#16a34a;

    border:none;

    color:white;

    border-radius:10px;

    cursor:pointer;

    transition:.2s ease;
}

.login-box button:hover{
    background:#15803d;
}

/* ERROR */
.error{
    color:#dc2626;
    text-align:center;
    margin-bottom:10px;
    font-size:13px;

    animation:shake .3s;
}

/* LINK */
.link{
    text-align:center;
    margin-top:15px;
}

.link a{
    color:#16a34a;
    text-decoration:none;
}

.link a:hover{
    text-decoration:underline;
}

/* ANIMATION */
@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(10px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

@keyframes shake{
    0%{transform:translateX(0);}
    25%{transform:translateX(-4px);}
    50%{transform:translateX(4px);}
    75%{transform:translateX(-4px);}
    100%{transform:translateX(0);}
}

/* RESPONSIVE */
@media(max-width:900px){

    .left{
        display:none;
    }

    .right{
        width:100%;
    }
}
</style>
</head>

<body>

<div class="container">

<!-- LEFT -->
<div class="left">
    <div class="overlay">
        <h1>SIPPEGADAIAN</h1>
        <p>
            Solusi Digital Pengajuan Pinjaman Cepat & Aman
        </p>
    </div>
</div>

<!-- RIGHT -->
<div class="right">

<div class="login-box">

<div class="logo">
    <img src="assets/logo.png">
</div>

<h2>Login</h2>

<p class="subtitle">
Masuk ke Sistem Pengajuan Pinjaman
</p>

<?php if($error){ ?>
<div class="error">
    <?= $error ?>
</div>
<?php } ?>

<form method="POST">

<input
type="email"
name="email"
placeholder="Email"
required>

<div class="input-group">

<input
type="password"
id="password"
name="password"
placeholder="Password"
required>

<span
class="toggle-password"
onclick="togglePassword()">

<i
id="eyeIcon"
class="fa fa-eye">
</i>

</span>

</div>

<div class="remember-me">
<label>

<input type="checkbox">

Remember me

</label>
</div>

<button name="login">
Masuk
</button>

</form>

<div class="link">
Belum punya akun?
<a href="register.php">
<b>Daftar sekarang</b>
</a>
</div>

</div>
</div>

</div>

<script>
function togglePassword(){

    const password =
    document.getElementById("password");

    const icon =
    document.getElementById("eyeIcon");

    if(password.type === "password"){

        password.type = "text";

        icon.classList.replace(
            "fa-eye",
            "fa-eye-slash"
        );

    } else {

        password.type = "password";

        icon.classList.replace(
            "fa-eye-slash",
            "fa-eye"
        );
    }
}
</script>

</body>
</html>