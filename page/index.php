<?php Head('Главная страница') ?>
    <body>
        <div class="wrapper">
            <div class="header"></div>
            <div class="content">
                <?php
                Menu();
                MessageShow()
                ?>
                <div class="Page">
                    <h2>Главная страница</h2>
                </div>
            </div>
            <?php Footer() ?>
        </div>
    </body>
</html>