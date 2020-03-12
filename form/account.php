<?php

if ($Module == 'logout' and $_SESSION['USER_LOGIN_IN'] == 1) {

    if ($_COOKIE['remember_user']) {
        setcookie('remember_user', '', strtotime('-30 days'), '/');
        unset($_COOKIE['remember_user']);
    }
    session_unset();
    exit(header('Location: /login'));
}

ULogin(0);

if ($Module == 'restore' and !$Param['code'] and substr($_SESSION['RESTORE'], 0, 4) == 'wait') {

    MessageSend(2, 'Вы уже отправили запрос на восстановление пароля. Проверте ваш E-mail адресс <b>' .
        HideEmail(substr($_SESSION['RESTORE'], 5)) . '</b>');
}
if ($Module == 'restore' and $_SESSION['RESTORE'] and substr($_SESSION['RESTORE'], 0, 4) != 'wait') {

    MessageSend(2, 'Пароль был изменнен. Для входа используйте ваш новый пароль <b>' .
        $_SESSION['RESTORE'] . '</b>.', '/login');
}

if ($Module == 'restore' and $Param['code']) {

    $Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `email` FROM `users` WHERE `id` = " . str_replace(
            md5($Row['email']), '', $Param['code'])));
    if (!$Row['email']) {
        MessageSend(1, 'Не возможно восстановить пароль.','/login');
    }
    $Random = RandomString(15);
    $_SESSION['RESTORE'] = $Random;
    mysqli_query($CONNECT, "UPDATE `users`  SET `password` = '" . GenPass($Random, $Row['login']) .
        "' WHERE `login` = '$Row[login]'");

    MessageSend(2, 'Пароль успешно изменнен. Для входа используйте ваш новый пароль <b>' . $Random . '</b>.', '/login');
}

if ($Module == 'restore' and $_POST['enter']) {

    $_POST['login'] = FormChars($_POST['login']);
    $_POST['captcha'] = FormChars($_POST['captcha']);
    if (!$_POST['login'] or !$_POST['captcha']) {
        MessageSend(1, 'Невозможно обработать форму.');
    }
    if ($_SESSION['captcha'] != md5($_POST['captcha'])) {
        MessageSend(1, 'Капча введена не верно.');
    }
    $Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `id`,`email` FROM `users` WHERE `login` = '$_POST[login]'"));
    if (!$Row['email']) {
        MessageSend(1, 'Такой пользователь не найден.');
    }
    mail($Row['email'], 'Dark Soul Corporation, восстановление пароля',
        'Ссылка для восстановления пароля: http://dz.local/account/restore/code/' .
        md5($Row['email']) . $Row['id'], 'From: dz.local');
    $_SESSION['RESTORE'] = 'wait_' . $Row['email'];
    MessageSend(2, 'На ваш E-mail адресс <b>' . HideEmail($Row['email']) . '</b> отправленно сообщение с
 подтверждением смены пароля.');

}

if ($Module == 'register' and $_POST['enter']) {

    $_POST['login'] = FormChars($_POST['login']);
    $_POST['email'] = FormChars($_POST['email']);
    $_POST['password'] = GenPass(FormChars($_POST['password']), $_POST['login']);
    $_POST['name'] = FormChars($_POST['name']);
    $_POST['country'] = FormChars($_POST['country']);
    $_POST['captcha'] = FormChars($_POST['captcha']);
    if (!$_POST['login'] or !$_POST['email'] or !$_POST['password'] or !$_POST['name'] or $_POST['country'] > 4 or
        !$_POST['captcha']) {
        MessageSend(1, 'Невозможно обработать форму.');
    }
    if ($_SESSION['captcha'] != md5($_POST['captcha'])) {
        MessageSend(1, 'Капча введена не верно.');
    }
    $Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `login` FROM `users` WHERE `login` = '$_POST[login]'"));
    if ($Row['login']) {
        exit('Логин <b>' . $_POST['login'] . '</b> уже используеться.');
    }
    $Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `email` FROM `users` WHERE `email` = '$_POST[email]'"));
    if ($Row['email']) {
        exit('E-Mail <b>' . $_POST['email'] . '</b> уже используеться.');
    }
    mysqli_query($CONNECT, "INSERT INTO `users` VALUES (`id`, '$_POST[login]', '$_POST[password]', 
    '$_POST[name]', NOW(), '$_POST[email]', $_POST[country], 0, 0)");

    $Code=str_replace('=','',base64_encode($_POST['email']));

    mail($_POST['email'], 'Регистрация на сайте Dark Soul Corporation',
        'Ссылка для активации: http://dz.local/account/activate/code/' .
        substr($Code, -5) . substr($Code, 0, -5), 'From: dz.local');
    MessageSend(3, 'Регистрация акаунта успешно завершена. 
        На указанный E-mail адрес <b>' . $_POST['email'] . '</b> отправленно письмо с подтверждением регистрации.');

} elseif ($Module == 'activate' and $Param['code']) {

    if (!$_SESSION['USER_ACTIVE_EMAIL']) {
        $Email = base64_decode(substr($Param['code'], 5) . substr($Param['code'], 0, 5));
        if (strpos($Email, '@') !== false) {
            mysqli_query($CONNECT, "UPDATE `users`  SET `active` = 1 WHERE `email` = '$Email'");
            $_SESSION['USER_ACTIVE_EMAIL'] = $Email;
            MessageSend(3, 'E-mail <b>' . $Email . '</b> подтвержден.', '/login');
        } else {
            MessageSend(1, 'E-mail адрес не подтвержден.', '/login');
        }
    } else {
        MessageSend(1, 'E-mail адрес <b>' . $_SESSION['USER_ACTIVE_EMAIL'] . '</b> уже подтвержден.', '/login');
    }

} elseif ($Module == 'login' and $_POST['enter']) {

    $_POST['login'] = FormChars($_POST['login']);
    $_POST['password'] = GenPass(FormChars($_POST['password']), $_POST['login']);
    $_POST['captcha'] = FormChars($_POST['captcha']);
    if (!$_POST['login'] or !$_POST['password'] or !$_POST['captcha']) {
        MessageSend(1, 'Невозможно обработать форму.');
    }
    if ($_SESSION['captcha'] != md5($_POST['captcha'])) {
        MessageSend(1, 'Капча введена не верно.');
    }
    $Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `password`,`active` FROM `users` WHERE `login` =
 '$_POST[login]'"));
    if ($Row['password'] != $_POST['password']) {
        MessageSend(1, 'Не верный логин или пароль.');
    }
    if ($Row['active'] == 0) {
        MessageSend(1, 'Аккаунт ползователя: <b>' . $_POST['login'] . '</b>, не подтвержден.');
    }
    $Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `id`,`name`,`regdate`,`email`,`country`,`avatar`
 FROM `users` WHERE `login` = '$_POST[login]'"));
    $_SESSION['USER_ID'] = $Row['id'];
    $_SESSION['USER_NAME'] = $Row['name'];
    $_SESSION['USER_REGDATE'] = $Row['regdate'];
    $_SESSION['USER_EMAIL'] = $Row['email'];
    $_SESSION['USER_COUNTRY'] = UserCountry($Row['country']);
    $_SESSION['USER_AVATAR'] = $Row['avatar'];
    $_SESSION['USER_LOGIN_IN'] = 1;

    if ($_REQUEST['remember']) {
        setcookie('remember_user', $_POST['password'], strtotime('+30 days'), '/');
    }

    exit(header('Location: /profile'));

}
