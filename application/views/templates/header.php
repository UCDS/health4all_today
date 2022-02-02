<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
        <title>Health4All Today<?php  if($title){ echo " | ".$title;} ?></title>
    <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" integrity="" crossorigin="anonymous">  
    <link href="<?php echo base_url();?>assets/css/sweetalert.min.css" rel="stylesheet" integrity="" crossorigin="anonymous">  
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css" >
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/selectize.css" >
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/youseelogo.css" media='screen,print'>
  
    <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script>

    <style>
        input[type='checkbox']{
            cursor: pointer;
        }
       .navbar
        {
            background-color: #f8f8f8;
            border:1px solid #e7e7e7;
        }
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      .round-button{
          border-radius:100%;
          border: solid 1px;
      }
      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      input[type=number]::-webkit-inner-spin-button, 
      input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
    }
    </style>
    <!-- Custom styles for this template -->
    <!-- <link href="sticky-footer-navbar.css" rel="stylesheet"> -->
  </head>
  <body class="d-flex flex-column h-100">
    <header>
  <!-- Fixed navbar -->

  <nav class="navbar navbar-expand-md navbar-light   justify-content-between">
    <a class="navbar-brand" href="<?php echo $yousee_website[0]->value; ?>" target="_blank"><span style="position:absolute;font-size:2.7em;left:5%;top:-18px" class="logo logo-yousee"></a>
    
    <a class="navbar-brand" href="<?php echo base_url().'quiz';?>" > 
    <span style="position:absolute;left:20%;top:10px">Health4All.Today</span></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mr-auto">
      <?php 
        $logged_in=$this->session->userdata('logged_in');
      if($logged_in) { ?>
        <!-- <li class="nav-item active">
          <a class="nav-link" href="<?php echo base_url();?>admin" >Home <span class="sr-only">(current)</span></a>
        </li> -->
      </ul>
      <ul class="navbar-nav navbar-right ">  
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo base_url();?>admin" >Operations <span class="sr-only">(current)</span></a>
        </li>
          <li class="nav-item">
             <a class="nav-link" href="#" style="text-decoration:none; color:black;"> <?php echo $logged_in['first_name']." ".$logged_in['last_name']." | " ; ?></a>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" style="text-decoration:none; color:black;" ><i class="fa fa-gear"></i> Settings <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <a class="dropdown-item" href="<?php echo base_url()."admin/change_password";?>"><i class="fa fa-edit"></i> Change Password</a>
              <a class="dropdown-item" href="<?php echo base_url();?>admin/logout"><i class="fa fa-sign-out"></i> Logout</a>
            </ul>
          </li>
        <?php } else {?>
        </ul>
          <ul class="navbar-nav navbar-right">
            <li class="nav-item   <?php if(preg_match("^".base_url()."admin/login^",current_url())){ echo " active";}?>">
              <a class="nav-link" href="<?php echo base_url()."admin/login";?>" style="text-decoration:none; color:black;"><i class="fa fa-sign-in" style="color:black;"></i> Login</a>
            </li>
          </ul>
        <?php }?>
      <form class="form-inline mt-2 mt-md-0">
        <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>
    </div>
  </nav>
</header>

