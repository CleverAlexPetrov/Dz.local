<?php
ULogin(1);
Head('Профиль пользователя'); ?>
<body>
<div class="wrapper">
    <div class="header"></div>
    <div class="content">
        <?php
        Menu();
        MessageShow();
        ?>
        <div class="Page">
            <?php
            if ($_SESSION['USER_AVATAR'] == 0) {
                $Avatar = 0;
            } else {
                $Avatar = $_SESSION['USER_AVATAR'] . '/' . $_SESSION['USER_ID'];
            }
            echo '
                <img src="resource/avatar/' . $Avatar . '.jpg" width="120px" height="120px" alt="Аватар" align="left">
                
                <div class="Block">
                    ID: ' . $_SESSION['USER_ID'] . '
                    <br>Имя: ' . $_SESSION['USER_NAME'] . '
                    <br>E-mail: ' . $_SESSION['USER_EMAIL'] . '
                    <br>Страна: ' . $_SESSION['USER_COUNTRY'] . '
                    <br>Дата регистрации: ' . $_SESSION['USER_REGDATE'] . '                
                </div>
                <a href="/account/logout" class="button ProfileB">Выйти</a>
                <div class="ProfileEdit">
                    <form method="POST" action="/account/edit" enctype="multipart/form-data">
                        <br><input type="password" name="oldpassword" placeholder="Старый пароль" maxlength="15"
                            pattern="[A-Za-z-0-9]{5,15}" title="Не менее 5 и не более 15 латинских символов или цифр.">
                        <br><input type="password" name="newpassword" placeholder="Новый пароль" maxlength="15"
                            pattern="[A-Za-z-0-9]{5,15}" title="Не менее 5 и не более 15 латинских символов или цифр.">
                        <br><input type="text" name="name" placeholder="Имя" maxlength="10" pattern="[A-Za-z-0-9]{4,10}"
                            title="Не менее 4 и не более 10 латинских символов или цифр." 
                            value="' . $_SESSION['USER_NAME'] . '" required>
                        <br><select size="1" name="country">
                            ' . str_replace('>' . $_SESSION['USER_COUNTRY'], 'selected>' . $_SESSION['USER_COUNTRY'],
                    '<option value="0">Страна...</option>
                             <option value="1">Украина</option>
                             <option value="2">Россия</option>
                             <option value="3">США</option>
                             <option value="4">Канада</option>
                             ') . '
                             </select>
                        <br><input type="file" name="avatar">
                        <br><br><input type="submit" name="enter" value="Сохранить"> <input type="reset" value="Очистить">
                    </form>
                </div>'; ?>
        </div>
    </div>
    <?php Footer(); ?>
</div>
</body>
</html>