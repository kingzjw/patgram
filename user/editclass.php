<?php 

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/include/requirelogin.php';

if(empty($_GET["c"])) {
	exit("Error");
}

include_once $_SERVER['DOCUMENT_ROOT'].'/include/dbconnection.php';

$classCode = $_GET["c"];

$conn = createConnection();

$result = mysqli_query($conn, "SELECT name
		FROM class
		WHERE code='$classCode'");

if($row = mysqli_fetch_array($result)) {
	$name = $row["name"];

	closeConnection($conn);
}
else {
	closeConnection($conn);
	exit("Error");
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Class</title>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/bootstrap.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/handlebars.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/typeahead.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/font-awesome.php';
?>

<script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/scripts/load.js"></script>

<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/styles/main.css">
<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/styles/user.css">

<script id="highlightable-pattern-template" type="text/x-handlebars-template">
{{#each this}}
<div class="panel {{#if hl}}panel-primary{{else}}panel-info{{/if}}">
	<div class="panel-heading">
		<h4 class="panel-title">
			<button type="button" class="btn btn-default btn-highlight">
				<span class="glyphicon {{#if hl}}glyphicon-star{{else}}glyphicon-star-empty{{/if}}"></span>
			</button>
			<a data-toggle="collapse" data-parent="#accordion" href="#collapse{{@index}}">
				<span class="verb-pattern">{{vp}}</span>
				<span class="meaning-group">{{mg}}</span>
				<input type="hidden" class="meaning-group-id" value="{{mgid}}"></input>
			</a>
		</h4>
	</div>
	<div id="collapse{{@index}}" class="panel-collapse collapse">
		<div class="panel-body"></div>
	</div>
</div>
{{/each}}
</script>

<script id="highlightable-meaning-group-template" type="text/x-handlebars-template">
<div class="panel panel-default">
	<div class="panel-heading meaning">Meaning</div>
	<div class="panel-body">{{m4mg}}</div>
</div>
<div class="panel panel-default example-verbs">
	<div class="panel-heading">Example Verbs</div>
	<div class="panel-body">
		<table class="table table-condensed">
		{{#tablebody ev 6}}
			<input class="example-verb-id" type="hidden" value="{{evid}}"></input>
			<span class="label label-highlight {{#if hl}}label-primary{{else}}label-default{{/if}}">{{ev}}</span>
		{{/tablebody}}
		</table>
	</div>
</div>
<div class="panel panel-default example-sentences">
	<div class="panel-heading">Example Sentences</div>
	<div class="panel-body">
		<ul>
		{{#hightlightVerbInSentence es ev}}
			<u><b>{{this}}</b></u>
		{{/hightlightVerbInSentence}}
		</ul>
	</div>
</div>
</script>

<script id="show-collapse-button-template" type="text/x-handlebars-template">
<button type="button" class="btn btn-link show-all-example-verbs" onclick="showAllExampleVerbs($(this).parent())">Show All</button>
<button class="btn btn-link collapse-example-verbs" onclick="collapseExampleVerbs($(this).parent())">Collapse</button>
</script>

<script>
	$(document).ready(function() {
		$("#loading").hide();

		$("#addVerbForm").submit(addVerb);
		
		$("body").on("click", ".btn-highlight", function() {
			var mgid = $(this).siblings("a").children(".meaning-group-id").val();
			var span = $(this).children("span:first");
			if(span.hasClass("glyphicon-star-empty")){
				highlightMeaningGroup(mgid);
				span.removeClass("glyphicon-star-empty").addClass("glyphicon-star");
				$(this).parents(".panel:first").removeClass("panel-info").addClass("panel-primary");
			}
			else{
				unhighlightMeaningGroup(mgid);
				span.removeClass("glyphicon-star").addClass("glyphicon-star-empty");
				$(this).parents(".panel:first").removeClass("panel-primary").addClass("panel-info");
			}
		});
		
		$("body").on("click", ".label-highlight", function() {
			var evid = $(this).siblings(".example-verb-id:first").val();
			if($(this).hasClass("label-default")){
				$.post("_highlightexampleverb.php", {
					c : "<?php echo  $_GET["c"]; ?>",
					ev : evid
				});
				$(this).removeClass("label-default").addClass("label-primary");
			}
			else{
				$.post("_unhighlightexampleverb.php", {
					c : "<?php echo  $_GET["c"]; ?>",
					ev : evid
				});
				$(this).removeClass("label-primary").addClass("label-default");
			}
		});

		Handlebars.registerHelper("tablebody", templateTablebody);
		Handlebars.registerHelper("hightlightVerbInSentence", templateHightlightVerbInSentence);

		$(".typeahead").typeahead({
			hint: true,
			highlight: true,
			minLength: 3
		}, {
			source: function (query, callback) {
				$.getJSON("http://<?php echo $_SERVER['HTTP_HOST']; ?>/verb/_findverb.php", {
					p : query
				}, function (data) {
					callback(data);
				})
			}
		});
		
		showVerbs();
	});

	function showVerbs() {
		$.get("_getverbbyclass.php", { c : "<?php echo  $_GET["c"] ?>"}, function(data) {
			var select = $("#addedVerbs");
			select.empty();
			$.each(data, function(index, value) {
				select.append('<option>' + value + '</option>');
			});
		}, "json");
	}

	function addVerb(event) {
		event.preventDefault();

		var verb = $("#inputVerb").val().toLowerCase();
		if($("#addedVerbs").children("option:contains('" + verb + "')").length==0){
			$.post("_addverbtoclass.php", {
				c : "<?php echo  $_GET["c"]; ?>",
				v : verb
			}, function (data) {
				if(data){
					showVerbs();
				}
				$("#inputVerb").select();
			}, "json");
		}
	}
	
	function editVerb() {
		var selected = $("#addedVerbs").children(":selected");
		if(selected.length!=0) {
			$("#modal1").modal("show");
			
			var verb = selected.text().toLowerCase();
			if ($(".modal-title").text() != verb) {
				$(".modal-title").text(verb);
				
				var mbody = $(".modal-body");
				$("#loading").show();
				$("#accordion").empty();
				
				console.log(verb);
				$.get("http://<?php echo $_SERVER['HTTP_HOST']; ?>/verb/_patterns.php", {
					c : "<?php echo $_GET["c"]; ?>",
					v : verb
				}, findPatternsSucceed, "json");
			}
		}
	}
	
	function deleteVerb() {
		var selected = $("#addedVerbs").children(":selected");
		if(selected.length!=0) {
			var verb = selected.text().toLowerCase();
			if(confirm("Confirm to delete " + verb + "?")) {
				$.post("_deleteverbfromclass.php", {
					c : "<?php echo $_GET["c"]; ?>",
					v : verb
				}, function (data) {
					console.log(data);
					if(data) {
						showVerbs();
					}
				}, "json");
			}
		}
	}

	function checkCollapseExampleVerbs(obj) {
		var shown_rows = 4;
		var tbody = obj.children("table:first").children("tbody");
		var row = tbody.children("tr").length;
		if (row >= shown_rows) {
			var source = $("#show-collapse-button-template").html();
			var template = Handlebars.compile(source);
			var html = template();
			obj.append(html);

			collapseExampleVerbs(obj);
		}
	}

	function showAllExampleVerbs(obj) {
		var shownRows = 4;
		var tbody = obj.children("table:first").children("tbody");
		var row = tbody.children("tr").length;
		if (row >= shownRows) {
			for (i = shownRows; i <= row; i++) {
				tbody.children("tr:nth-child(" + i + ")").show();
			}

			obj.children(".show-all-example-verbs:first").hide();
			obj.children(".collapse-example-verbs:first").show();
		}
	}

	function collapseExampleVerbs(obj) {
		var shownRows = 4;
		var tbody = obj.children("table:first").children("tbody");
		var row = tbody.children("tr").length;
		if (row >= shownRows) {
			for (i = shownRows; i <= row; i++) {
				tbody.children("tr:nth-child(" + i + ")").hide();
			}

			obj.children(".collapse-example-verbs:first").hide();
			obj.children(".show-all-example-verbs:first").show();
		}
	}
	
	function findPatternsSucceed(data) {
		$("#loading").hide();

		var source = $("#highlightable-pattern-template").html();
		var template = Handlebars.compile(source);
		var html = template(data);
		$("#accordion").append(html);

		var length = data.length;
		for(var i=0; i<length; i++) {
			$("#collapse" + i).on('show.bs.collapse', showMeaningGroup);
		}
	}

	function showMeaningGroup() {
		var mgid = $(this).siblings(".panel-heading:first").find(".meaning-group-id:first").val();
		console.log(mgid);
		var pbody = $(this).children(".panel-body:first");
		if (pbody.children().length == 0) {
			pbody.append("<div>Loading...</div>");
			$.get("http://<?php echo $_SERVER['HTTP_HOST']; ?>/verb/_mgroup.php", {
				g : mgid
				}, function(data) {
					console.log(data);
					pbody.empty();
					
					var source = $("#highlightable-meaning-group-template").html();
					var template = Handlebars.compile(source);
					var html = template(data);
					pbody.append(html);
					
					checkCollapseExampleVerbs(pbody.children(".example-verbs:first").children(".panel-body"));
				}, "json");
		}
	}

	function highlightMeaningGroup(mgid) {
		$.post("_highlightmeaninggroup.php", { c : "<?php echo $_GET["c"]; ?>", g : mgid });
	}

	function unhighlightMeaningGroup(mgid) {
		$.post("_unhighlightmeaninggroup.php", { c : "<?php echo $_GET["c"]; ?>", g : mgid });
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
		<div class="col-sm-9">
		<div class="panel panel-default">
			<div class="panel-body">
			<h3><?php echo $name; ?></h3>
			<div>
				<form id="addVerbForm" class="form-inline" role="form">
					<div class="form-group">
						<label class="sr-only" for="inputVerb">Search Verb</label>
						<input id="inputVerb" type="text" class="form-control typeahead" placeholder="Verb"></input>
					</div>
					<button type="submit" class="btn btn-default">Add</button>
				</form>
			</div>
			<div>
				<select id="addedVerbs" class="form-control" size="10">
				</select>
				<button type="button" class="btn btn-primary" onclick="editVerb();">Edit</button>
				<button type="button" class="btn btn-primary" onclick="deleteVerb();">Delete</button>
			</div>
			<div class="modal fade" id="modal1" tabindex="-1" role="dialog"
				aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">
								<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
							</button>
							<h3 class="modal-title" id="myModalLabel"></h3>
						</div>
						<div class="modal-body">
							<div id="loading">Loading...</div>
							<div class="panel-group" id="accordion"></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
		</div>
		</div>
	</section>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
</body>
</html>