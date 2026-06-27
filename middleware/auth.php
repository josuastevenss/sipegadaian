<?php

// =========================
// START SESSION
// =========================

if (session_status() === PHP_SESSION_NONE) {

    session_start();

}

// =========================
// CHECK LOGIN
// =========================

function checkLogin(){

    if(

        !isset($_SESSION['user'])

        ||

        empty($_SESSION['user'])

    ){

        header("Location: /sippegadaian/login.php");

        exit;

    }

}

// =========================
// CHECK ADMIN
// =========================

function checkAdmin(){

    if(

        !isset($_SESSION['user'])

        ||

        empty($_SESSION['user'])

    ){

        header("Location: /sippegadaian/login.php");

        exit;

    }

    if(

        !isset($_SESSION['user']['role'])

        ||

        $_SESSION['user']['role'] !== 'admin'

    ){

        header("Location: /sippegadaian/login.php");

        exit;

    }

}

// =========================
// CHECK USER
// =========================

function checkUser(){

    if(

        !isset($_SESSION['user'])

        ||

        empty($_SESSION['user'])

    ){

        header("Location: /sippegadaian/login.php");

        exit;

    }

    if(

        !isset($_SESSION['user']['role'])

        ||

        (

            $_SESSION['user']['role'] != 'user'

            &&

            $_SESSION['user']['role'] != 'admin'

        )

    ){

        header("Location: /sippegadaian/login.php");

        exit;

    }

}
?>