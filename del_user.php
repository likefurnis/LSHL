<?php
include ('includes/print_messages.php');
require ('mysqli_connect.php');
include ('includes/header.php');
require ('includes/login_function.php');

if (isset($_COOKIE['username'])) {
  $uid = $_COOKIE['username'];
  $pass = $_COOKIE['pass'];
  $q = "SELECT user_id FROM users WHERE username='$uid' AND pass='$pass'";
  $r = @mysqli_query ($dbc, $q);
  if (mysqli_num_rows($r) != 1) {
    redirect_user('logout.php?hacked=1');
  }
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$id = $row['user_id'];
	if  ($_SERVER['REQUEST_METHOD'] == 'POST') {
  	$q = "DELETE FROM stars WHERE seller_id=$id";
	  $r = @mysqli_query ($dbc, $q);
  	$q = "DELETE FROM figures WHERE user_id=$id";
	  $r = @mysqli_query ($dbc, $q);
  	$q = "DELETE FROM users WHERE user_id=$id LIMIT 1";
  	$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) redirect_user('logout.php?deleted=1');
		else {
			echo print_message('danger', 'The user could not be deleted due to a system error');
			echo '<p>'.mysqli_error($dbc).'<br />Query: '.$q.'</p>';
		}
	}
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin-top: 300px;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete your account?</h5>
      </div>
      <div class="modal-body">
        All your figures, votes you recieved and messages will also be deleted.
      </div>
      <div class="modal-footer">
        <a href="profile.php"><button type="button" class="btn btn-secondary">No</button></a>
        <form action="del_user.php" method="POST"> 
        	<button type="submit" class="btn btn-primary">Yes</button>
      	</form>
      </div>
    </div>
  </div>
</div>
<script>$('#myModal').modal('show');</script>
<?php
} else echo print_message('danger', 'You must be logged in to delete a figure.');
?>