<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

?>

<!DOCTYPE html>
<html>
<head>
<title>Practice</title>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/jquery.validate.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/bootstrap.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/handlebars.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/font-awesome.php';
?>

<script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/scripts/load.js"></script>

<?php 
include $_SERVER['DOCUMENT_ROOT'].'/include/codemirror.php';
?>

<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/styles/main.css">

<script id="pattern-template" type="text/x-handlebars-template">
{{#each this}}
<div class="panel panel-info">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapse{{@index}}">
				<i class="fa fa-comment-o"></i>
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

<script id="meaning-group-template" type="text/x-handlebars-template">
<div class="panel panel-default">
	<div class="panel-heading meaning">Meaning</div>
	<div class="panel-body">{{m4mg}}</div>
</div>
<div class="panel panel-default example-verbs">
	<div class="panel-heading">Example Verbs</div>
	<div class="panel-body">
		<table class="table table-condensed">
		{{#tablebody ev 6}}
			<span class="label label-default">{{ev}}</span>
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
		$("#classCodeForm").validate();
		
		var cm = CodeMirror.fromTextArea(document.getElementById("editor1"), {
			mode : "verb",
			theme : "eclipse",
			lineWrapping : true,
			autofocus : true
		});

		$("body").on("click", ".cm-s-eclipse span.cm-keyword", showVerb);
		$("body").on("click", ".class-verb", showVerb);

		$("#modal1").on("hidden.bs.modal", function() {
			cm.focus();
		});

		Handlebars.registerHelper("tablebody", templateTablebody);
		Handlebars.registerHelper("hightlightVerbInSentence", templateHightlightVerbInSentence);
	});

	function loadKeywordsFrom() {
		var lines;
		$.ajax({
			type : "GET",
			url : window.location.protocol +"//"+ window.location.host +"/glossary/verb.txt",
			async : false,
			success : function(data) {
				lines = data;
			}
		});
		return lines.split(" ");
	}

	function showVerb() {
		$("#modal1").modal("show");

		var verb = VERB.getNormalForm($(this).text().toLowerCase());
		console.log(verb);
		if ($(".modal-title").text() != verb) {
			$(".modal-title").text(verb);

			var mbody = $(".modal-body");
			$("#loading").show();
			$("#accordion").empty();

			console.log(verb);
			$.get("http://<?php echo $_SERVER['HTTP_HOST']; ?>/verb/_patterns.php", {
				v : verb
			}, findPatternsSucceed, "json");
		}
	}

	function findPatternsSucceed(data) {
		$("#loading").hide();

		var source = $("#pattern-template").html();
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
					
					var source = $("#meaning-group-template").html();
					var template = Handlebars.compile(source);
					var html = template(data);
					pbody.append(html);
					
					checkCollapseExampleVerbs(pbody.children(".example-verbs:first").children(".panel-body"));
				}, "json");
		}
	}

	function checkCollapseExampleVerbs(obj) {
		var shown_rows = 4;
		var tbody = obj.children("table:first").children("tbody");
		var row = tbody.children("tr").length;
		if(row>=shown_rows) {
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
		if(row>=shownRows){
			for(i=shownRows; i<=row; i++){
				tbody.children("tr:nth-child("+i+")").show();
			}
			
			obj.children(".show-all-example-verbs:first").hide();
			obj.children(".collapse-example-verbs:first").show();
		}
	}
	
	function collapseExampleVerbs(obj) {
		var shownRows = 4;
		var tbody = obj.children("table:first").children("tbody");
		var row = tbody.children("tr").length;
		if(row>=shownRows){
			for(i=shownRows; i<=row; i++){
				tbody.children("tr:nth-child("+i+")").hide();
			}
			
			obj.children(".collapse-example-verbs:first").hide();
			obj.children(".show-all-example-verbs:first").show();
		}
	}
</script>
</head>
<body>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/include/nav.php'; ?>
	
	<section>
		<div class="container-fluid">
			<div>
				<header>
					<h2><i class="fa fa-coffee"></i> Practice</h2>
				</header>
			</div>
		
			<div>
				<form role="form">
					<div class="form-group">
						<textarea id="editor1" class="form-control"></textarea>
					</div>
				</form>
		
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
	</section>
	
	<?php include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
</body>
</html>