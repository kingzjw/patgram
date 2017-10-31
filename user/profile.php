<?php 

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/include/requirelogin.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';

$userId = $_SESSION["userId"];
$updateSucceed = false;

$conn = createConnection();

if($_SERVER['REQUEST_METHOD']=='POST') {
	$firstName = $_POST["firstName"];
	$lastName = $_POST["lastName"];
	
	mysqli_query($conn, "UPDATE user
			SET firstName='$firstName', lastName='$lastName'
			WHERE userId='$userId'");
	
	$updateSucceed = true;
}

$result = mysqli_query($conn, "SELECT email, firstName, lastName
		FROM user
		WHERE userId='$userId'");

if($row = mysqli_fetch_array($result)) {
	$email = $row["email"];
	$firstName = $row["firstName"];
	$lastName = $row["lastName"];
	$_SESSION["firstName"] = $firstName;
}

closeConnection($conn);

?>


<!DOCTYPE html>
<html>
<head>
<title>Profile</title>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.validate.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/bootstrap.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/font-awesome.php';
?>

<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/styles/main.css">
<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/styles/user.css">

<script>
$(document).ready(function() {
	$("#profileForm").validate({
		rules: {
            firstName : {
                required : true
            },
            lastName : {
                required : true
            }
        }
	});
});
</script>

</head>
<body>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/include/nav.php'; ?>
	
	<section>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-3">
					<?php include 'sidebar.php'; ?>
				</div>
				
				<section class="page-wrapper">
				<div class="col-sm-9">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-user"></i>
							<span>Profile</span>
						</div>
						<div class="panel-body">
						<?php if($updateSucceed) { ?>
							<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok"></span> Update profile successfully.</div>
						<?php } ?>
							<form id="profileForm" class="form" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
								<div class="form-group">
									<label for="inputEmail" class="control-label">Email</label>
									<input type="email" class="form-control" id="inputEmail" placeholder="Email" name="email" value="<?php echo $email; ?>" readonly></input>
								</div>
								<div class="form-group">
									<label for="inputFirstName" class="control-label">First Name</label>
									<input type="text" class="form-control" id="inputFirstName" placeholder="First Name" name="firstName" value="<?php echo $firstName; ?>"></input>
								</div>
								<div class="form-group">
									<label for="inputLastName" class="control-label">Last Name</label>
									<input type="text" class="form-control" id="inputLastName" placeholder="Last Name" name="lastName" value="<?php echo $lastName; ?>"></input>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary">Update</button>
									<button type="reset" class="btn btn-primary">Reset</button>
				  				</div>
							</form>
						</div>
					</div>
				</div>
				</section>
			</div>
		</div>
	</section>
	
	<?php include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
</body>
</html>