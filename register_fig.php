<?php
include('includes/print_messages.php');
require ('mysqli_connect.php');
include ('includes/header.php');

if (isset($_COOKIE['username'])) {
  $uid = $_COOKIE['username'];
  $pass = $_COOKIE['pass'];
  $q = "SELECT user_id FROM users WHERE username='$uid' AND pass='$pass'";
  $r = @mysqli_query ($dbc, $q);
  $num = mysqli_num_rows($r);
  if ($num != 1) {
    require ('includes/login_function.php');
    redirect_user('logout.php?hacked=1');
  }
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();
    if (empty($_POST['name'])) {
      $errors[] = 'You forgot to enter the figure name.';
    } else {
      if (strlen($_POST['name']) < 5) {
        $errors[] = 'The name is too short.';
      } else if (strlen($_POST['name']) > 50) {
        $errors[] = 'The name is too long.';
      } else {
        $name = mysqli_real_escape_string($dbc, trim($_POST['name']));
      }
    }
    if (empty($_POST['desc'])) {
      $errors[] = 'You forgot to enter the figure description.';
    } else {
      if (strlen($_POST['desc']) > 1000) {
        $errors[] = 'The description is too long.';
      }else {
        $desc = mysqli_real_escape_string($dbc, trim($_POST['desc']));
      }
    }
    if (empty($_POST['price'])) {
      $errors[] = 'You forgot to enter the figure price.';
    } else {
      if ($_POST['price'] <= 0) {
        $errors[] = 'The price must be more than 0.';
      }else {
        $price = mysqli_real_escape_string($dbc, trim($_POST['price']));
      }
    }
    if (empty($_POST['image'])) {
      $errors[] = 'You forgot to enter the figure image.';
    } else {
      $pattern = "/(https?:\/\/.*\.(?:png|jpg|jpeg|gif))/";
      if (preg_match ($pattern, trim($_POST['image']))) {
        if (strlen(trim($_POST['image'])) <= 250) {
          $image[] = mysqli_real_escape_string($dbc, trim($_POST['image']));
          for ($i=0; !empty($_POST['image'.$i]); $i++) {
            if (preg_match ($pattern, trim($_POST['image'.$i]))) {
              if (strlen(trim($_POST['image'])) <= 250) {
                $image[] = mysqli_real_escape_string($dbc, trim($_POST['image'.$i]));
              }
            }
          }
          $strimg = implode(",", $image);
        } else {
        $errors[] = 'The link is too long.';
        }
      }else {
        $errors[] = 'The link is not an image.';
      }
    }

    if (empty($errors)) {
      $uid = $_COOKIE['username'];
      $q = "SELECT user_id FROM users WHERE username='$uid'";
      $r = @mysqli_query($dbc, $q);
      $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
      $id = $row['user_id'];
      $q = "INSERT INTO figures (user_id, name, description, price, images, status, published) VALUES ('$id', '$name', '$desc', '$price', '$strimg', 0, NOW())";   
      $r = @mysqli_query ($dbc, $q);
      if ($r) {
        echo print_message('success', 'Thank you. We will review the register and post it as soon as posible.');
      } else {
        echo print_message('danger', 'Something went wrong due to our system. Sorry for the inconvenience.');
        echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
      }
      mysqli_close($dbc);
      include ('includes/footer.html'); 
      exit();
    } else {
      foreach ($errors as $msg) {
        echo print_message('danger', $msg);
      }
    }
  }
?>
<div class="row text-center login-title">
  <div class="col-sm-12 text-center">
    <h1 style="color: #8E44AD; font-size: 4em; text-align: center !important;">Register figure</h1>
  </div>
</div>
<script>
  var i=0;
</script>
<div class="row">
  <div class="col-sm-2"></div>
  <div class="col-sm-8">
    <form class="form-horizontal" action="register_fig.php" method="post">
      <div class="form-group row">
        <label class="control-label col-sm-2 text-right" for="name">Name:</label>
        <div class="col-sm-10"> 
          <input type="text" class="form-control" name="name" minlength="5" maxlength="50" required id="name" placeholder="Figure name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
          <small class="form-text text-muted">Required. This will be the title of your figure. It must be between 5 and 50 characters.</small>
        </div>
      </div>
      <div class="form-group row">
        <label class="control-label col-sm-2 text-right" for="desc">Description:</label>
        <div class="col-sm-10"> 
          <textarea rows="5" class="form-control" name="desc" required id="desc" maxlength="1000" placeholder="Description" <?php if(isset($_POST['desc'])) echo $_POST['desc'];?>></textarea>
          <small class="form-text text-muted">Required. This will be the description of your figure. It must be less than 1000 characters.</small>
        </div>
      </div>
      <div class="form-group row">
        <label class="control-label col-sm-2 text-right" for="price">Price:</label>
        <div class="col-sm-10"> 
          <input type="number" step="0.01" class="form-control" name="price" required id="price" min="0.01" placeholder="Price" value="<?php if (isset($_POST['price'])) echo $_POST['price']; ?>">
          <small class="form-text text-muted">Required. This will be the price of your figure. It must be more than 0.</small>
        </div>
      </div>
      <div class="form-group row">
        <label class="control-label col-sm-2 text-right" for="image">Image link:</label>
        <div class="col-sm-10"> 
          <input type="url" class="form-control" name="image" required id="image" maxlength="250" placeholder="Image url" value="<?php if (isset($_POST['image'])) echo $_POST['image']; ?>">
          <small class="form-text text-muted">Required. This will be the image of your figure. It must be a link (url) containing less than 250 characters.</small>
        </div>
      </div>
      <div class="row">
        <label class="control-label col-sm-2 text-right">Add images</label>
        <span class="fa fa-plus-square" onclick="addImages()" style="font-size:1.5em; color: #8E44AD; margin-left: 10px;"></span>
      </div>
      <div id="add-images"></div>
      <div class="form-group row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
  function addImages() {
  document.getElementById("add-images").innerHTML += '<div class="form-group row"><label class="control-label col-sm-2 text-right" for="image'+i+'">Image link:</label><div class="col-sm-10"> <input type="url" class="form-control" name="image'+i+'" id="image'+i+'" maxlength="250" placeholder="Image url"><small class="form-text text-muted">More images for the figure.</small></div></div>';
  i++;
  }
</script>
<?php
} else echo print_message('danger', 'You must be logged in to register a figure.');
include ('includes/footer.html');
?>