<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<ul class="nav navbar-nav navbar-right">
<!-- <li> -->
<!-- 	<span class="sign">For Teachers</span> -->
<!-- </li> -->
<?php

if(isset($_SESSION["userId"])) {

?>
<li>
	<a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/user">Account</a>
</li>
<li>
	<a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/_logout.php">Logout</a>
</li>
<?php

}
else {

?>
<li>
	<a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/login.php">Login</a>
</li>
<li>
	<a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/register.php">Register</a>
</li>

<?php

}

?>
</ul>
<span class="sign">For Teachers</span>
</div>