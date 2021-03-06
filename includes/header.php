<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>Love Second Hand Live!</title>
    <!--<script src="external/bootstrap-3.3.7/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="external/bootstrap-3.3.7/dist/css/bootstrap.min.css">-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="external/font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="icon" href="images/nav.png"/>
    <!--<script src="external/smooth-scroll.js"></script>-->
  </head>
  <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
    <nav class="navbar navbar-expand-lg navbar-default bg-default fixed-top justify-content-between">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="myNavbar">
        <div class="navbar-nav mr-auto">
          <a class="nav-item nav-link" href="index.php">HOME</a>
<?php 
if (isset($_COOKIE['username'])) {
  $id = $_COOKIE['username'];
  $pass = $_COOKIE['pass'];
  $q = "SELECT user_id FROM users WHERE username='$id' AND pass='$pass'";
  $r = @mysqli_query ($dbc, $q);
  $num = mysqli_num_rows($r);
  if ($num != 1) {
    require ('includes/login_function.php');
    redirect_user('logout.php?hacked=1');
  }
  $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
  $id = $row['user_id'];
  echo '<a class="nav-item nav-link" href="profile.php">PROFILE</a><a class="nav-item nav-link" href="logout.php">LOGOUT</a><a class="nav-item nav-link" href="register_fig.php">REGISTER FIGURE</a><a class="nav-item nav-link" href="messages.php">MESSAGES <span class="badge badge-pill badge-primary" style="background-color: white;color: #8E44AD;">';
  $q = "SELECT COUNT(message_id) AS total FROM messages WHERE destiny_id=$id AND viewed=0";
  $r = @mysqli_query ($dbc, $q);
  $num = mysqli_num_rows($r);
  if ($num == 1) {
    $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
    echo $row['total'];
  } else echo '0';
  echo '</span></a>';
} else {
  echo '<a class="nav-item nav-link" href="login.php">LOGIN</a><a class="nav-item nav-link" href="register_user.php">REGISTER USER</a>';
}
?>
        </div>
          <form class="navbar-form form-inline ml-auto" role="search">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search" name="q">
              <div class="input-group-btn">
                  <button class="btn btn-default" type="submit"><span class="fa fa-search" aria-hidden="true"></span></button>
              </div>
            </div>
            <img src="images/nav.png" alt="Logo" class="nav-logo">
          </form>
<!--<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['q'])) {
    $q = mysqli_real_escape_string($dbc, trim($_POST['q']));
    header("edit_user.php?id=$q");
  }
}
?>-->
      </div>
    </nav>
    <div class="content" id="topPage">