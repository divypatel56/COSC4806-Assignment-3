<?php require_once 'app/views/templates/header.php' ?>
<div class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <p class = "lead">Welcome, <?= $_SESSION [ "username" ].".<br> Today is: ". 
                    date("Y-m-d")."."?> </p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <p> <a href="/logout">Click here to logout</a></p>
        </div>
    </div>

    <?php require_once 'app/views/templates/footer.php' ?>
