<?php
include_once 'config.php';

session_start();
$CONNECT = mysqli_connect(HOST, USER, PASS, DB);

if ($_SERVER['REQUEST_URI'] == '/') {
    $Page = 'index';
    $Module = 'index';
} else {
    $URL_Path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $URL_Parts = explode('/', trim($URL_Path, ' /'));
    $Page = array_shift($URL_Parts);
    $Module = array_shift($URL_Parts);

    if (!empty($Module)) {
        $Param = array();

        for ($i = 0; $i < count($URL_Parts); $i++) {
            $Param[$URL_Parts[$i]] = $URL_Parts[++$i];
        }
    }
}

if ($Page == 'index') {
    include_once 'page/index.php';
} elseif ($Page == 'login') {
    include_once 'page/login.php';
} elseif ($Page == 'register') {
    include_once 'page/register.php';
} elseif ($Page == 'account') {
    include_once 'forms/account.php';
}

function MessageSend($p1, $p2)
{
    if ($p1 == 1) {
        $p1 = 'Error';
    } elseif ($p1 == 2) {
        $p1 = 'Help';
    } elseif ($p1 == 3) {
        $p1 = 'Information';
    }

    $_SESSION['messedg'] = '<div class="MessageBlock"><b>' . $p1 . '</b>: ' . $p2 . '</div>';

    exit(header("Location: {$_SERVER['HTTP_REFERER']}"));
}

function MessageShow()
{
    if ($_SESSION['messedg']) {
        $Messedg = $_SESSION['messedg'];

        echo $Messedg;

        $_SESSION['messedg'] = array();
    }
}


function GenPass($p1, $p2)
{
    return md5('YourNameAndLogin' . md5('123' . $p1 . '951') . md5('357' . $p2 . '987'));
}

function FormChars($p1)
{
    return nl2br(htmlspecialchars(trim($p1), ENT_QUOTES), false);
}

function Head($p1)
{
    echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>' . $p1 . '</title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <link href="/resource/style.css" rel="stylesheet">
</head>';
}

function Menu()
{
    echo '<div class="MenuHead">
            <a href="/">
                <div class="menu">Home page</div>
            </a>
            <a href="/register">
                <div class="menu">Page registration</div>
            </a>
            <a href="/login">
                <div class="menu">Entry</div>
            </a>
        </div>';
}

function Footer()
{
    echo '<footer class="footer"><h3 class="Page">Dark Soul Corporation &reg;</h3></footer>';
}