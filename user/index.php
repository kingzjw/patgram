<?php 

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/include/requirelogin.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';

$userId = $_SESSION["userId"];

$conn = createConnection();

$result = mysqli_query($conn, "SELECT COUNT(*) AS numClass
		FROM class
		WHERE userId='$userId'");

if($row = mysqli_fetch_array($result)) {
	$numClass = $row["numClass"];
}

closeConnection($conn);

?>

<!DOCTYPE html>
<html>
<head>
<title>User Center</title>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.validate.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/bootstrap.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/font-awesome.php';
?>

<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/styles/main.css">
<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/styles/user.css">

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
					<div>
						<h3>Hello, <?php echo $_SESSION["firstName"]; ?></h3>
					</div>
				
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-book"></i>
							<span>My Class</span>
						</div>
						<div class="panel-body">
							<p>You have <?php echo $numClass; ?> classes.</p>
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