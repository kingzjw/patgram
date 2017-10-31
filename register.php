<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

if($_SERVER['REQUEST_METHOD']=='POST') {
	$userName = $_POST["email"];

	include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';

	$conn = createConnection();

	$result = mysqli_query($conn, "SELECT userId
			FROM user
			WHERE userName='$userName'");

	if(mysqli_num_rows($result) == 0) {
		$password = md5($_POST["password"]);
		$email = $_POST["email"];
		$firstName = $_POST["firstName"];
		$lastName = $_POST["lastName"];
		$createDate = date("Y-m-d H:i:s", time());
	
		mysqli_query($conn, "INSERT INTO user(userName, password, email, firstName, lastName, createDate)
			VALUES('$userName', '$password', '$email', '$firstName', '$lastName', '$createDate')");
	
		header("location: http://".$_SERVER["HTTP_HOST"]."/register_success.php");
	
		closeConnection($conn);
		exit();
	}

	closeConnection($conn);
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.validate.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/bootstrap.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/font-awesome.php';
?>

<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/styles/main.css">

<script>
$(document).ready(function() {
	$("#registerForm").validate({
		rules: {
			email : {
				required : true,
				email : true,
				remote : {
					url : "http://<?php echo $_SERVER['HTTP_HOST']; ?>/user/_checkusername.php",
					type : "post",
					datatype : "json",
					data : {
						name : function () {
							return $("#inputEmail").val();
						}
					}
				}
			},
			password : {
                required : true,
                minlength : 6
            },
            confirmPassword : {
                required : true,
                minlength : 6,
                equalTo : "#inputPassword"
            },
            firstName : {
                required : true
            },
            lastName : {
                required : true
            }
        },
        messages : {
            email : {
                remote : "Please use another email."
            }
        }
	});
});
</script>

</head>
<body>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/include/nav.php'; ?>
		
	<section>
		<div class="container">
			<div class="row">
				<div class="panel panel-default col-sm-offset-4 col-sm-4">
					<div class="panel-body">
						<h3>Register</h3>
						<form id="registerForm" class="form" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
							<div class="form-group">
								<label for="inputEmail" class="control-label">Email</label>
								<input type="email" class="form-control" id="inputEmail" placeholder="Email" name="email"></input>
							</div>
							<div class="form-group">
								<label for="inputPassword" class="control-label">Password</label>
								<input type="password" class="form-control" id="inputPassword" placeholder="Password" name="password"></input>
							</div>
							<div class="form-group">
								<label for="inputConfirmPassword" class="control-label">Confirm Password</label>
								<input type="password" class="form-control" id="inputConfirmPassword" placeholder="Confirm Password" name="confirmPassword"></input>
							</div>
							<div class="form-group">
								<label for="inputFirstName" class="control-label">First Name</label>
								<input type="text" class="form-control" id="inputFirstName" placeholder="First Name" name="firstName"></input>
							</div>
							<div class="form-group">
								<label for="inputLastName" class="control-label">Last Name</label>
								<input type="text" class="form-control" id="inputLastName" placeholder="Last Name" name="lastName"></input>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary">Register</button>
								<button type="reset" class="btn btn-primary">Reset</button>
			  				</div>
						</form>
					</div>
				</div>
		</div>
		</div>
	</section>
		
	<?php include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
</body>
</html>