<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header page-scroll">
			<button type="button" class="navbar-toggle" data-toggle="collapse"
				data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/index.php">PAT GRAM</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<!-- <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right">
				<li class="hidden">
                    <a href="#page-top"></a>
                </li>
                <li class="page-scroll">
					<a href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/index.php">Home</a>
				</li>
				<li class="page-scroll">
					<a href="#about">About</a>
				</li>
			</ul>
		</div> -->
		
		<?php include $_SERVER['DOCUMENT_ROOT'].'/include/loginpanel.php'; ?>
	</div>
</nav>