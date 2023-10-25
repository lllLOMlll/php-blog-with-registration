<?php
session_start();
include "dbConnection.php";


// Query to get all users
$query = $db->query("SELECT * FROM users ORDER BY id ASC");
$users = $query->fetchAll(PDO::FETCH_ASSOC);

// Quere to get all posts
$query = $db->query("SELECT * FROM posts ORDER BY id DESC");
$posts = $query->fetchAll(PDO::FETCH_ASSOC);	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lab 4</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>
	<div class="containter">
	    
		<!-- ********************************* TOP IMAGE *********************************** -->
		<div class="text-center">
			<img class="image-logo" src="images/dragon.jpg" alt="dragon">
		</div>
		

		<!-- ********************************* LOGOUT*********************************** -->
		
		<!-- Show only if the user is logged -->
		<?php 
		if (isset($_SESSION['logged'])){
			?>
			
		<!--Logout Button-->
		<form method="post" action="logout.php">
		<div class="text-center">
			<input class="btn btn-md btn-danger my-1" type="submit" name="logout" value="Logout">
		</div>
	</form>	
	
	    <!--Username and Avatar-->
	<p class="text-center">
    <strong>Welcome <span style="color:green; font-size: 18px;"><?php echo $_SESSION['usernameLogin']?></span></strong>
    <br>
    <?php if (isset($_SESSION['avatar'])): ?>
        <img src="<?php echo $_SESSION['avatar']; ?>" alt="User Avatar" style="width:50px;height:50px;">
    <?php endif; ?>
</p>

	
			<?php
		}
		?>

		<!--  ****************** SUCCESS  OR ERROR MESSAGE (Login, Post, not Register) ******************* -->
		<div class="text-center">
			<!-- Success -->
			<?php
			if (isset($_SESSION['successMessage'])) {
				?>
				<div id="successMessage" class="alert alert-success fade in alert-dismissible show" style="margin-top:18px;"> 
					<strong>Yahoo!</strong><br><?php echo $_SESSION['successMessage'] ?>
				</div>
				<?php
				unset($_SESSION['successMessage']);
			}
			?>

			<!-- Error -->
			<?php
			if (isset($_SESSION['errorMessage'])) {
				?>
				<div class="alert alert-danger fade in alert-dismissible show" style="margin-top:18px;"> 
					<strong>Oups!</strong><br><?php echo $_SESSION['errorMessage'] ?>
				</div>
				<?php
   					 // Unset the error message from the session to prevent it from displaying on refresh
				unset($_SESSION['errorMessage']);
			}
			?>
		</div>



		<!-- ***************************** SIGN IN OR REGISTER ***************************** -->
		<!-- Dont show if the user is logged -->
		<?php
		if (!isset($_SESSION['logged'])){
			?>
			<div class="text-center">
				<form role="form" method="post" action="login.php">  
					<p class="text-uppercase mt-3"><b>Login using your account</b></p>

					<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
						<!-- Username -->
					<input type="text" class="form-control mb-3" id="usernameLogin" name="usernameLogin" placeholder="Username" value="<?php echo (isset($_SESSION['usernameLogin']) ? $_SESSION['usernameLogin'] : ''); ?>">

						<!-- Password -->
					<input type="password" class="form-control mb-3" id="passwordLogin" name="passwordLogin" placeholder="Password" value="<?php echo isset($_SESSION['passwordLogin']) ? $_SESSION['passwordLogin'] : ''; ?>">

					</div>

					<!-- Login and Register buttons -->
					<div class="mb-3">
						<input type="submit" class="btn btn-md btn-success me-2" value="Login" name="login">  
						<!-- Button trigger modal -->
						<button type="button" class="btn btn-md btn-warning" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
					</div>
				</form>
			</div>
			<?php
		}
		?>

		<!-- ***************************** REGISTRATION (MODAL) ******************************************   -->
		<!-- Creating and initializing a variable to use it in my custom.js -->
		<!-- I need this to show the error in the modal -->
		<script>
			var showErrorModal = <?php echo isset($_SESSION['errorMessageRegister']) ? 'true' : 'false'; ?>;
		</script>


		<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- ERROR MESSAGE -->
					<?php
					if (isset($_SESSION['errorMessageRegister'])) {
						?>
						<div class="alert alert-danger fade in alert-dismissible show">
							<strong>Oups!</strong><br><?php echo $_SESSION['errorMessageRegister'] ?>
						</div>	
						<?php
   					 // Unset the error message from the session to prevent it from displaying on refresh
						unset($_SESSION['errorMessageRegister']);
					}
					?>

					<!-- MODAL -->
					<div class="modal-header">
						<h5 class="modal-title" id="registerModalLabel">Registration Form</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form method="post" action="register.php" enctype="multipart/form-data">
							<div class="mb-3">
							    <!--Username-->
								<label for="registerUsername" class="form-label">Username</label>
								<input type="text" class="form-control <?php echo isset($_SESSION['fieldInputFeedbackUsername']); ?>" id="usernameRegister" name="usernameRegister" value="<?php echo isset($_SESSION['registrationInProgress']) ? ($_SESSION['usernameRegister']) : ''; ?>">

							</div>
							<div class="mb-3">
							    <!--Email-->
								<label for="registerEmail" class="form-label">Email</label>
								<input type="text" class="form-control <?php echo isset($_SESSION['fieldInputFeedbackEmail']); ?>" id="emailRegister" name="emailRegister" value="<?php echo isset($_SESSION['registrationInProgress']) ? ($_SESSION['emailRegister']) : ''; ?>">
							</div>
							<div class="mb-3">
							    <!--Password-->
								<label for="registerPassword" class="form-label">Password</label>
								<input type="password" class="form-control <?php echo isset($_SESSION['fieldInputFeedbackPassword']); ?>" id="passwordRegister" name="passwordRegister" value="<?php echo isset($_SESSION['registrationInProgress']) ? ($_SESSION['passwordRegister']) : '';  ?>">
							</div>
							<div class="mb-3">
							    <!--Confirm password-->
								<label for="confirmPassword" class="form-label">Confirm Password</label>
								<input type="password" class="form-control <?php echo isset($_SESSION['fieldInputFeedbackConfirmPassword']); ?>" id="passwordConfirmRegister" name="passwordConfirmRegister" value="<?php echo isset($_SESSION['registrationInProgress']) ? ($_SESSION['passwordConfirmRegister']) : ''; ?>">
							</div>
							<div class="mb-3">
							    <!--Avatar-->
								<label for="avatarUpload" class="form-label">Avatar</label>
								<?php if (isset($_SESSION['avatarUploaded']) && $_SESSION['avatarUploaded'] && isset($_SESSION['registrationInProgress'])): ?>
								<p>Please re-upload your avatar.</p>
							<?php endif; ?>
							<input class="form-control <?php echo isset($_SESSION['fieldInputFeedbackAvatar']); ?>" type="file" id="avatarUploadRegister" name="avatarUploadRegister">

						</div>
						<button type="button" class="btn btn-secondary" id="buttonResetRegister">Reset</button>
						<input type="submit" class="btn btn-primary" value="Register" name="register"></input>
					</form>
				</div>
				<div class="modal-footer">

				</div>
			</div>
		</div>
	</div>



	<!-- ************************************************************************************ -->
	<!-- ***************************** SENDING MESSAGE AND BLOG ***************************** -->
	<!-- ************************************************************************************ -->
	<div class="row mt-5">
		<!-- ****************************** SENDING MESSAGE  ************************************* -->

		<!-- Display this only if the user is logged -->
		<?php
		if (isset($_SESSION['logged'])) {	
			?>

			<div class="col-md-4 offset-md-2 text-center">
				<h3 class="text-center">Send a Message</h3>
				<form method="post" action="post.php">
					<textarea class="form-control" name="blogPost"></textarea>
					<input class="btn btn-md btn-success mt-3" type="submit" name="post" value="Post">
				</form>
			</div>
			<!-- Closing tag of showing the "Send a Message" -->
			<?php
		}
		?>


		<!-- ***********************************  BLOG  ******************************************* -->

		
			<div class="<?php echo isset($_SESSION['logged']) ? 'col-md-4' : 'col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4'; ?>">
				    <!-- The title will be displayed only if there are posts -->
    <?php if (!empty($posts)) : ?>
        <h3 class="text-center">Blog Posts</h3>
    <?php endif; ?>
				<div class="feed">
					<?php
        		// Loop to display the posts
					foreach ($posts as $post) {
						?>
						<div class="post">
							<div class="avatar-container">
								<img src="<?php echo $post['avatar']; ?>" alt="User avatar" class="avatar">
							</div>
							<div class="content">
								<h2 class="username"><?php echo $post['username']; ?></h2>
								<p class="user-post" style="font-weight: normal;"><?php echo $post['comment']; ?></p>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>


		<!-- Closing tag of the <div class="row">. I need that to have the Sending message and blog displayed correctly -->
		</div>



		<!-- Closing tag for the <div class="containter"> -->
		</div>
		<script src="js/bootstrap.bundle.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/jquery.js"></script>
		<script src="js/custom.js"></script>
	</body>
	</html>