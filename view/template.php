<!DOCTYPE html>
<html <?=LANG?>
<?php 

$peticionAjax = false;

require_once "./controller/viewcontroller.php";

$ins_views = new viewController();

$view = $ins_views->obtener_vistas_controlador();

$viewStyles = [
    "index" => "view/inc/index/css.php",
    "login" => "view/inc/login/css.php",
];

$viewScripts = [
    "index" => "view/inc/index/js.php",
    "login" => "view/inc/login/js.php",
];
?>

<head>
    <?php include_once "view/inc/head.php"; ?>
    <?php include_once $viewStyles[$view]; ?>
</head>

<body id="page-top" class="index-page bg-dark-subtle <?= $view ?>" data-bs-theme="dark">
<?php

    if ($view == "login" || $view == "404" || $view == "index") : 

        require_once "./view/content/$view-view.php"; 

    else: 
        include_once "view/inc/topbar.php";
        include_once "view/inc/sidebar.php";
        ?>

        <main id="main" class="main"> <?php include $view; ?> </main>
        
        <!-- End of Main Content -->
        <?php include_once "view/inc/footer.php"; ?>

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top"> <i class="fas fa-angle-up"></i> </a>

    <?php 
    endif;
        // <!-- Script -->
        include_once "view/inc/script.php";
        include_once $viewScripts[$view];
    ?>

</body>

</html>