<?php Head('Registration'); ?>
<body>
<div class="wrapper">
    <div class="header"></div>
    <div class="content">
        <?php Menu();
        MessageShow();

        ?>
        <div class="Page">
            <form method="post" action="/account/register">
                <br/><input type="text" name="login" placeholder="Login" required>
                <br/><input type="email" name="email" placeholder="E-mail" required>
                <br/><input type="password" name="password" placeholder="Password" autocomplete="on" required>
                <br/><input type="text" name="name" placeholder="Name" required>
                <br/><select size="1" name="country">
                    <option value="0">Your country</option>
                    <option value="1">Ukraine</option>
                    <option value="2">Russia</option>
                    <option value="3">Canada</option>
                    <option value="4">USA</option>
                    <option value="5">Italy</option>
                </select>
                <!--                <br/><input type="file" name="avatar">-->
<!--                <div class="capdiv">-->
<!--                    <input type="text" class="capinp" name="captcha" placeholder="Captcha">-->
<!--                    <img src="/resource/captcha.php" class="capimg" alt="Captcha">-->
<!--                </div>-->
                <br/><br/><input type="submit" name="enter" value="Registration">
                <input type="reset" value="Clear">
            </form>
        </div>
    </div>
    <?php Footer(); ?>
</div>
</body>
</html>
