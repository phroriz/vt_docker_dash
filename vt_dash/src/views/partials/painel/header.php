<?php

use src\handlers\GroupHandler;
use src\handlers\PowerBiHandler;

?>
<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>{{page_name}}</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <!-- [Favicon] icon -->
  <link rel="icon" href="<?= $base ?>/assets/images/favicon.svg" type="image/x-icon"> <!-- [Google Font] Family -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
<!-- [Tabler Icons] https://tablericons.com -->
<link rel="stylesheet" href="<?= $base ?>/assets/fonts/tabler-icons.min.css" >
<!-- [Feather Icons] https://feathericons.com -->
<link rel="stylesheet" href="<?= $base ?>/assets/fonts/feather.css" >
<!-- [Font Awesome Icons] https://fontawesome.com/icons -->
<link rel="stylesheet" href="<?= $base ?>/assets/fonts/fontawesome.css" >
<!-- [Material Icons] https://fonts.google.com/icons -->
<link rel="stylesheet" href="<?= $base ?>/assets/fonts/material.css" >
<!-- [Template CSS Files] -->
<link rel="stylesheet" href="<?= $base ?>/assets/css/style.css" id="main-style-link" >
<link rel="stylesheet" href="<?= $base ?>/assets/css/style-preset.css" >

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
<div class="loader-bg">
  <div class="loader-track">
    <div class="loader-fill"></div>
  </div>
</div>
<!-- [ Pre-loader ] End -->
 <!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="<?= $base ?>/dashboard/index.html" class="b-brand text-primary">
        <!-- ========   Change your logo from here   ============ -->
        <img src="<?= $base ?>/assets/images/logo-dark.svg" class="img-fluid logo-lg" alt="logo">
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <li class="pc-item">
          <a href="<?= $base ?>/painel" class="pc-link">
            <span class="pc-micon"><i class="ti ti-home"></i></span>
            <span class="pc-mtext">Inicio</span>
          </a>
        </li>
        <?php foreach(GroupHandler::getAllForMenu() as $group): ?>
        <li class="pc-item pc-caption">
          <label><?=$group['name'] ?></label>
          <i class="ti ti-dashboard"></i>
        </li>
      <?php foreach(PowerBiHandler::getAllForMenu($group['id']) as $powerbi): ?>
        <li class="pc-item">
          <a href="<?= $base . '/painel/dashboard/powerbi/view/' . $powerbi['hash'] ?>" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
            <span class="pc-mtext"><?= $powerbi['title'] ?></span>
          </a>
        </li>
        <?php endforeach ?>
        <?php endforeach ?>
      </ul>
      <!--
      <div class="card text-center">
        <div class="card-body">
          <img src="<?= $base ?>/assets/images/img-navbar-card.png" alt="images" class="img-fluid mb-2">
          <h5>Upgrade To Pro</h5>
          <p>To get more features and components</p>
          <a href="https://codedthemes.com/item/berry-bootstrap-5-admin-template/" target="_blank"
          class="btn btn-success">Buy Now</a>
        </div>
      </div>
      -->
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->
<header class="pc-header">
  <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
<div class="me-auto pc-mob-drp">
  <ul class="list-unstyled">
    <!-- ======= Menu collapse Icon ===== -->
    <li class="pc-h-item pc-sidebar-collapse">
      <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
        <i class="ti ti-menu-2"></i>
      </a>
    </li>
    <li class="pc-h-item pc-sidebar-popup">
      <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
        <i class="ti ti-menu-2"></i>
      </a>
    </li>
    <li class="dropdown pc-h-item d-inline-flex d-md-none">
      <a
        class="pc-head-link dropdown-toggle arrow-none m-0"
        data-bs-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        aria-expanded="false"
      >
        <i class="ti ti-search"></i>
      </a>
      <div class="dropdown-menu pc-h-dropdown drp-search">
        <form class="px-3">
          <div class="form-group mb-0 d-flex align-items-center">
            <i data-feather="search"></i>
            <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . .">
          </div>
        </form>
      </div>
    </li>
    
  </ul>
</div>
<!-- [Mobile Media Block end] -->
<div class="ms-auto">
  <ul class="list-unstyled">
    
    <li class="dropdown pc-h-item header-user-profile">
      <a
        class="pc-head-link dropdown-toggle arrow-none me-0"
        data-bs-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        data-bs-auto-close="outside"
        aria-expanded="false"
      >
        <img src="<?= $base ?>/assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar">
        <span>{{user_name}}</span>
      </a>
      <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
        <div class="dropdown-header">
          <div class="d-flex mb-1">
            <div class="flex-shrink-0">
              <img src="<?= $base ?>/assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar wid-35">
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-1">{{user_name}}</h6>
              <span>{{user_email}}</span>
            </div>
            <a href="#!" class="pc-head-link bg-transparent"><i class="ti ti-power text-danger"></i></a>
          </div>
        </div>
        
      </div>
    </li>
  </ul>
</div>
 </div>
</header>
<!-- [ Header ] end -->

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">

