<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';
?>

<!DOCTYPE html>
<html>
<head>
<title>PAT GRAM</title>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.validate.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/bootstrap.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/font-awesome.php';
?>

<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/styles/main.css">

<script>
	$(document).ready(function() {
		$("#classCodeForm").validate();
	});
	
	function showClass() {
		$("#classModal").modal("show");
	}
</script>
</head>
<body>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/include/nav.php'; ?>

    <!--test-->
    <?PHP
    #echo "test: ".$_SERVER['DOCUMENT_ROOT'];
    #echo "test: ".$_SERVER['HTTP_HOST'];
    ?>
    <!--end test-->

	<section>
		<div class="container-fluid">
			<div id="teaser" class="row">
				<div id="teaser-video">
					<iframe width="800" height="450" src="http://www.youtube.com/embed/ERzoUY6CT2g" frameborder="0"></iframe>
	        	</div>
	        	<div class="intro-text">
	        		<h1 class="name">Tutorial</h1>
	        		<hr class="star-light">
				</div>
			</div>
		</div>
	</section>
		
	<section>
		<div class="container-fluid">
			<div class="portal row">
				<header class="col-sm-3 portal-header">
					<h2 class="name">For<br/>Students</h2>
				</header>
				<div class="col-sm-9 portal-content">
					<div class="gateway col-sm-3">
						<a href="#" onclick="showClass(); return false;">
                 			<i class="fa fa-university icon"></i><br />
                 			<h3 class="name">Practice in Class</h3>
                 		</a>
             		</div>
					<div class="gateway col-sm-3">
						<a href="practice.php">
                 			<i class="fa fa-coffee icon"></i><br />
                 			<h3 class="name">Practice on Your Own</h3>
                 		</a>
             		</div>
             		<div class="gateway col-sm-3">
						<a href="downloads/words_abbreviations_and_terms_used_in_patterns.pdf">
                 			<i class="fa fa-question-circle icon"></i><br />
                 			<h3 class="name">Help</h3>
                 		</a>
             		</div>
				</div>
			</div>
		</div>
		
		<div class="modal fade" id="classModal" tabindex="-1" role="dialog" 
	  		aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
						</button>
						<h3 class="modal-title" id="myModalLabel"><i class="fa fa-university icon"></i> Practice in Class</h3>
					</div>
					<div class="modal-body">
						<form class="form" role="form" method="GET" action="class.php">
							<div class="form-group">
								<label class="control-label" for="inputClassCode">Please enter the class code:</label>
								<input type="text" class="form-control required" id="inputClassCode" placeholder="Class Code" name="c"></input>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block">Go</button>
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