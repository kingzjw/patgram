<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/requirelogin.php';

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>My Class</title>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.validate.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/bootstrap.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/handlebars.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/font-awesome.php';
?>

<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/styles/main.css">
<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/styles/user.css">

<script id="class-template" type="text/x-handlebars-template">
{{#each this}}
<tr>
	<td>
		<a href="editclass.php?c={{code}}">{{name}}</a>
	</td>
	<td>
		<a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/class.php?c={{code}}">{{code}}</a>
	</td>
	<td>{{createDate}}</td>
	<td>
		<button type="button" class="btn btn-default" onclick="deleteClass('{{code}}');">
			<i class="fa fa-times"></i>
		</button>
	</td>
</tr>
{{else}}
<tr>
	<td colspan="4">No class yet.</td>
</tr>
{{/each}}
</script>

<script>

$(document).ready(function() {
	showClasses();

	$("#classForm").validate({
		submitHandler : addClass
	});
});

function showAddClassModal() {
	$("#modal1").modal("show");
}

function showClasses() {
	$.get("_getmyclass.php", {}, showClassesSucceed, "json");
}

function showClassesSucceed(data) {
	var tbody = $("#classList tbody");
	tbody.empty();

	var source = $("#class-template").html();
	var template = Handlebars.compile(source);
	var html = template(data);
	tbody.append(html);
}

function addClass() {
	var name = $("#inputName").val();
	var description = $("#inputDescription").val();
	$.post("_addclass.php", { "name" : name, "description" : description}, function (data) {
		if(data) {
			$("#modal1").modal("hide");
			showClasses();
		}
	}, "json");
}

function deleteClass(classCode) {
	if(confirm("Confirm to delete class ?")) {
		$.post("_deleteclass.php", { c : classCode }, function (data) {
			if(data) {
				showClasses();
			}
		}, "json");
	}
}

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
								<i class="fa fa-book"></i>
								<span>My Class</span>
							</div>
							<div class="panel-body">
							<div>
								<div>
									<button type="button" class="btn btn-primary" onclick="showAddClassModal();">
										<i class="fa fa-plus"></i> Add Class
									</button>
								</div>
				
								<div class="modal fade" id="modal1" tabindex="-1" role="dialog"
									aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">
													<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
												</button>
												<h3 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add Class</h3>
											</div>
											<div class="modal-body">
												<form id="classForm" class="form" role="form">
													<div class="form-group">
														<label for="inputName" class="control-label">Name</label>
														<input type="text" class="form-control required" id="inputName"
																placeholder="Name"></input>
													</div>
													<div class="form-group">
														<label for="inputDescription" class="control-label">Description</label>
															<textarea class="form-control" id="inputDescription"
																placeholder="Description" rows="3"></textarea>
													</div>
													<div class="form-group">
														<button type="submit" class="btn btn-primary">Add</button>
														<button type="button" class="btn btn-primary"
															data-dismiss="modal">Cancel</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div>
								<table id="classList" class="table table-hover">
									<thead>
										<tr>
											<th>Name</th>
											<th>Code</th>
											<th>Create Date</th>
											<th></th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
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