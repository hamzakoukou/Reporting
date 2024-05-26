<?php include 'connection.php';?>

<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus"> <![endif]-->
<!--[if gt IE 9]><!--> 
<html class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>Reporting</title>

        <meta name="description" content="Linedata - Admin Dashboard Template & UI Framework created by pixelcave and published on Themeforest">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="assets/img/favicons/favicon.png">

        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-192x192.png" sizes="192x192">

        <link rel="apple-touch-icon" sizes="57x57" href="assets/img/favicons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="assets/img/favicons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="assets/img/favicons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="assets/img/favicons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="assets/img/favicons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="assets/img/favicons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon-180x180.png">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Web fonts -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">

        <!-- Page JS Plugins CSS -->
        <link rel="stylesheet" href="assets/js/plugins/slick/slick.min.css">
        <link rel="stylesheet" href="assets/js/plugins/slick/slick-theme.min.css">
        
        <link rel="stylesheet" href="assets/js/plugins/datatables/jquery.dataTables.min.css">

        <!-- Linedata CSS framework -->
        <link rel="stylesheet" id="css-main" href="assets/css/Linedata.css">

        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->
        
    </head>
    <body>
        <!-- Page Container -->
        <div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed">
           
            <!-- Sidebar -->
            <nav id="sidebar" style="padding-top: 60px;">
                <!-- Sidebar Scroll Container -->
                <div id="sidebar-scroll">
                    <!-- Sidebar Content -->
                    <div class="sidebar-content">
                        <!-- Side Header -->
                        <div class="side-header side-content bg-white-op">
                            <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close">
                                <i class="fa fa-times"></i>
                            </button>
                            <div class="btn-group" style="width: 100%;">
                                <button class="btn btn-default btn-image dropdown-toggle" data-toggle="dropdown" type="button" style="width: 100%; background: transparent; color: #ffffff; text-align: left; border: none;">
                                    <i class="si si-user" style="color: #5c90d2;"></i>
                                    <span class="h5 sidebar-mini-hide" style="margin-left: 10px; color: #ffffff;"><?php echo $_SESSION['username']?></span>
                                    <span class="caret" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="profile.php"><i class="si si-user"></i> Profile</a></li>
                                    <li><a href="history.php"><i class="si si-clock"></i> History</a></li>
                                    <li><a href="logout.php"><i class="si si-logout"></i> Logout</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- END Side Header -->

                        <!-- Side Content -->
                        <div class="side-content">
                            <ul class="nav-main">
                                <!-- Files Menu Item with Subsections -->
                                <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'consulterfiles.php' || basename($_SERVER['PHP_SELF']) == 'files.php') ? 'open' : ''; ?>">
                                    <a href="javascript:void(0)" class="nav-submenu" data-toggle="nav-submenu"><i class="si si-folder"></i><span class="sidebar-mini-hide">Files</span></a>
                                    <ul>
                                        <li>
                                            <a href="consulterfiles.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'consulterfiles.php') ? 'active' : ''; ?>"><i class="si si-eye"></i>Overview</a>
                                        </li>
                                        <li>
                                            <a href="files.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'files.php') ? 'active' : ''; ?>"><i class="si si-cloud-upload"></i>Upload</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="configurations.php"><i class="si si-wrench"></i><span class="sidebar-mini-hide">Configurations</span></a>
                                </li>
                                <!-- Results Menu Item with Subsections -->
                                <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'traitement.php' || basename($_SERVER['PHP_SELF']) == 'overview.php' || basename($_SERVER['PHP_SELF']) == 'downloads.php') ? 'open' : ''; ?>">
                                    <a href="javascript:void(0)" class="nav-submenu" data-toggle="nav-submenu"><i class="si si-bar-chart"></i><span class="sidebar-mini-hide">Results</span></a>
                                    <ul>
                                        <li>
                                            <a href="traitement.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'traitement.php') ? 'active' : ''; ?>"><i class="si si-settings"></i>Traitement</a>
                                        </li>
                                        <li>
                                            <a href="overview.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'overview.php') ? 'active' : ''; ?>"><i class="si si-eye"></i>Overview</a>
                                        </li>
                                        <li>
                                            <a href="downloads.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'downloads.php') ? 'active' : ''; ?>"><i class="si si-cloud-download"></i>Downloads</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- END Side Content -->
                    </div>
                    <!-- Sidebar Content -->
                </div>
                <!-- END Sidebar Scroll Container -->
            </nav>
            <!-- END Sidebar -->

            <!-- Header -->
            <header id="header-navbar" class="content-mini content-mini-full">
                <!-- Header Navigation Right -->
                <ul class="nav-header pull-right">
                    <li class="visible-xs">
                        <!-- Toggle class helper (for .js-header-search below), functionality initialized in App() -> uiToggleClass() -->
                        <button class="btn btn-default" data-toggle="class-toggle" data-target=".js-header-search" data-class="header-search-xs-visible" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </li>
                    <!-- <li class="js-header-search header-search">
                        <form class="form-horizontal" action="base_pages_search.html" method="post">
                            <div class="form-material form-material-primary input-group remove-margin-t remove-margin-b">
                                <input class="form-control" type="text" id="base-material-text" name="base-material-text" placeholder="Search..">
                                <span class="input-group-addon"><i class="si si-magnifier"></i></span>
                            </div>
                        </form>
                    </li> -->
                </ul>
                <!-- END Header Navigation Right -->

                <!-- Header Navigation Left -->
                <ul class="nav-header pull-left">
                    <li>
                        <div class="bg-white-op" style="background: transparent; padding-top: 0px;">
                            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                            <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close">
                                <i class="fa fa-times"></i>
                            </button>
                            <a class="h5 text-white" href="index.php">
                                <img src="assets/img/various/logo.png" alt="" width="85">
                            </a>
                        </div>
                    </li>
                    <li class="hidden-md hidden-lg">
                        <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                        <button class="btn btn-default" data-toggle="layout" data-action="sidebar_toggle" type="button">
                            <i class="fa fa-navicon"></i>
                        </button>
                    </li>
                    <li class="hidden-xs hidden-sm">
                        <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                        <button class="btn btn-default" data-toggle="layout" data-action="sidebar_mini_toggle" type="button" style="background: transparent; color: #b3b3b3; border: 0 none;">
                            <i class="fa fa-bars"></i>
                        </button>
                    </li>
                    
                </ul>
                <!-- END Header Navigation Left -->
            </header>
            <!-- END Header -->


            
            
            
            
            
