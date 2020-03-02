<?php

if ($Module == 'register' && $_POST['enter']) {

    $_POST['login'] = FormChars($_POST['login']);
    $_POST['email'] = FormChars($_POST['email']);
    $_POST['password'] = GenPass(FormChars($_POST['password']), $_POST['login']);
    $_POST['name'] = FormChars($_POST['name']);
    $_POST['country'] = FormChars($_POST['country']);
//    $_POST['avatar'] = FormChars($_POST['avatar']);
//    $_POST['avatar'] = 0;
//    $_POST['captcha'] = FormChars($_POST['captcha']);

//    var_dump('sess');
//    var_dump($_SESSION['captcha']);
//    var_dump('post');
//    var_dump(FormChars($_POST['captcha']));
//    die();

    if (!$_POST['login'] || !$_POST['email'] || !$_POST['password'] || !$_POST['name']/* || !$_POST['captcha']*/) {
        MessageSend(1, '<h2 style="text-align: center;"><p class="p">Take Your hands off the keyboard ! ! !</p>
<p class="p"> Our patrol is on its way to You ! ! !</p></h2>');
    }

//    if($_SESSION['captcha'] != md5($_POST['captcha'])){
//        MessageSend(1,'<h2 style="text-align: center;">Wrong input captcha</h2>');
//    }

    $Rov = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `login` FROM `users` WHERE `login` = '$_POST[login]' "));

    if ($Rov['login']) {
        exit('<h1 style="text-align: center;">This Login : <b>' . $_POST['login'] . '</b> , already busy with another user.</h1>');
    }

    $Rov = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `email` FROM `users` WHERE `email` = '$_POST[email]' "));

    if ($Rov['email']) {
        exit('<h1 style="text-align: center;">This E-mail : <b>' . $_POST['email'] . '</b> , already busy with another user.</h1>');
    }

    mysqli_query($CONNECT, "INSERT INTO `users` VALUES (id,'$_POST[login]','$_POST[password]',
'$_POST[name]', NOW(),'$_POST[email]',$_POST[country],0,0)");

    $Code = substr(base64_decode($_POST['email']),0,-1);

    mail($_POST['email'], 'Welcome in Dark Soul Corporation &reg; . Thank you for registering on our site !!!',
        'Linc for activation: http://dz.local/account/activate/code/' . substr($Code, -5) . substr($Code, 0, -5),
        'From: Dark Soul Corporation &reg;');

    MessageSend(3, 'Congratulations !  An activation code has been sent to your address .');

}