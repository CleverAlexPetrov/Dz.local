<?php
if ($_POST['enter']) {
    echo 'Inquiry on login ...';
    exit();
}
Head('Entry');
?>
<body>
<div class="wrapper">
    <div class="header"></div>
    <div class="content">
        <?php Menu(); ?>
        <div class="Page">
            <form method="post" action="/login">
                <br/><input type="text" name="login" placeholder="Login" required>
                <br/><input type="password" name="password" placeholder="Password" required>
                <br/><br/><input type="submit" name="enter" value="Login">
                <input type="reset" value="Clear">
            </form>
        </div>
    </div>
    <?php Footer(); ?>
</div>
</body>
</html>