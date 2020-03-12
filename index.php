<?php
include_once 'setting.php';

session_start();
$CONNECT = mysqli_connect(HOST, USER, PASS, DB);

if ($_SESSION['USER_LOGIN_IN' != 1 and $_COOKIE['remember_user']]) {
    $Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `id`,`name`,`regdate`,`email`,`country`,`avatar`
 FROM `users` WHERE `password` = '$_COOKIE[remember_user]'"));
    $_SESSION['USER_ID'] = $Row['id'];
    $_SESSION['USER_NAME'] = $Row['name'];
    $_SESSION['USER_REGDATE'] = $Row['regdate'];
    $_SESSION['USER_EMAIL'] = $Row['email'];
    $_SESSION['USER_COUNTRY'] = UserCountry($Row['country']);
    $_SESSION['USER_AVATAR'] = $Row['avatar'];
    $_SESSION['USER_LOGIN_IN'] = 1;
}

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
    include('page/index.php');
} elseif ($Page == 'login') {
    include('page/login.php');
} elseif ($Page == 'register') {
    include('page/register.php');
} elseif ($Page == 'account') {
    include('form/account.php');
} elseif ($Page == 'profile') {
    include('page/profile.php');
} elseif ($Page == 'restore') {
    include('page/restore.php');
}

function ULogin($p1)
{
    if ($p1 <= 0 and $_SESSION['USER_LOGIN_IN'] != $p1) {
        MessageSend(1, 'Данная страница доступна только для не зарегестрированных пользователей', '/');
    } elseif ($_SESSION['USER_LOGIN_IN'] != $p1) {
        MessageSend(1, 'Данная страница доступна только для зарегестрированных пользователей', '/');
    }
}

function UserCountry($p1)
{
    if ($p1 == 0) {
        return 'Страна не указана';
    } elseif ($p1 == 1) {
        return 'Украина';
    } elseif ($p1 == 2) {
        return 'Россия';
    } elseif ($p1 == 3) {
        return 'США';
    } elseif ($p1 == 4) {
        return 'Канада';
    }
}

function RandomString($p1)
{
    $Char = '0123456789abcdefghijklmnopqrstuvwxyz';
    for ($i = 0; $i < $p1; $i++) {
        $String .= $Char[rand(0, strlen($Char) - 1)];
    }
    return $String;
}

function HideEmail($p1)
{
    $Explode = explode('@', $p1);
    return $Explode[0] . '@******';
}

function MessageSend($p1, $p2, $p3 = '')
{
    if ($p1 == 1) {
        $p1 = 'Ошибка';
    } elseif ($p1 == 2) {
        $p1 = 'Подсказка';
    } elseif ($p1 == 3) {
        $p1 = 'Информация';
    }
    $_SESSION['message'] = '<div class="MessageBlock"><b>' . $p1 . '</b>: ' . $p2 . '</div>';
    if ($p3) {
        $_SERVER['HTTP_REFERER'] = $p3;
    }
    exit(header('Location: ' . $_SERVER['HTTP_REFERER']));
}


function MessageShow()
{
    if ($_SESSION['message']) {
        $Message = $_SESSION['message'];
    }
    echo $Message;
    $_SESSION['message'] = array();
}


function FormChars($p1)
{
    return nl2br(htmlspecialchars(trim($p1), ENT_QUOTES), false);
}


function GenPass($p1, $p2)
{
    return md5('DARKSOUL' . md5('321' . $p1 . '123') . md5('678' . $p2 . '890'));
}


function Head($p1)
{
    echo '<!DOCTYPE html><html><head><meta charset="utf-8" /><title>'
        . $p1 . '</title><meta name="keywords" content="" />
          <meta name="description" content="" />
          <link href="resource/style.css" rel="stylesheet"></head>';
}


function Menu()
{
    if ($_SESSION['USER_LOGIN_IN'] != 1) {
        $Menu = '<a href="/register"><div class="Menu">Регистрация</div></a>
          <a href="/login"><div class="Menu">Вход</div></a><a href="/restore"><div class="Menu">Восстановление пароля</div></a>';
    } else {
        $Menu = '<a href="/profile"><div class="Menu">Профиль</div></a>';
    }
    echo '<div class="MenuHead"><a href="/"><div class="Menu">Главная</div></a>' . $Menu . '</div>';
}

function Footer()
{
    echo '<footer class="footer"><h3 class="Page">Dark Soul Corporation &reg;</h3></footer>';
}

