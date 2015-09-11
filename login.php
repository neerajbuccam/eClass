<?
include("includes/header.php");

if(@$_SESSION['logged_in'] != 1){
?>
<!--=================================== MAIN DIV   ===================================-->
<div id="main" class="row">
<!--=================================== REGISTER DIV   ===================================-->
	<div id="reg-form" class="col-xs-12 col-sm-9 col-md-8">
		<form method="post" action="includes/process.php">
			<div class="right bold">
				<button type="button" onclick="toggle('reg-form')" class="btn btn-default" style="background-color: rgb(225, 117, 45);">X</button>
			</div>
			<h3 class="center bold" style="border-bottom: 1px dotted;">Register on e-Classroom</h3>
				<div class="col-xs-12 col-md-6">
					<div class="form-group msg">
						<? if(isset($_GET['reg_msg'])){ ?>
							<label>
							<? echo $_GET['reg_msg']; ?>
							</label>
								<script type="text/javascript">
   								toggle('reg-form');
								</script>
						<? } ?>
					</div>
					<div class="form-group">
						<input type="text" name="fname" class="form-control" placeholder="First Name"/>
					</div>
					<div class="form-group">
						<input type="text" name="lname" class="form-control" placeholder="Last Name"/>
					</div>
					<div class="form-group">
						<input type="text" name="reg-email" class="form-control" placeholder="Email"/>
					</div>
					<div class="form-group">
						<input type="password" name="reg-pass" class="form-control" placeholder="Password"/>
					</div>
				</div>
				<div class="col-xs-12 col-md-6">
					<br/>
					<div class="form-group">
						<input type="text" name="address" class="form-control" placeholder="Address"/>
					</div>
					<div class="form-group">
					<label>You are currently in</label>
						<div class="radio">
						  <label>
						    <input type="radio" name="year" value="1" checked/>
						    1<sup>st</sup> Year
						  </label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="year" value="2"/>
						    2<sup>nd</sup> Year
						  </label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="year" value="3"/>
						    3<sup>rd</sup> Year
						  </label>
						</div>
					</div>
				</div>
			<button name="form" value="Register" type="submit" class="btn btn-success right" style="width: 10em;">Register</button><br/>
		</form>
	</div>

<!--=================================== LOGIN DIV   ===================================-->
	<div id="login" class="col-xs-12 col-md-6">
		<form method="post" action="includes/process.php">
			<h3 class="center bold" style="border-bottom: 1px dotted;">Login to e-Classroom</h3>
	  	<div class="form-group msg">
				<label><? if(isset($_GET['log_msg'])) echo $_GET['log_msg']; ?></label>
			</div>
		  <div class="form-group">
				<label for="login-email">Email</label>
				<input type="text" name="login-email" class="form-control" placeholder="Email" />
			</div>
	  	<div class="form-group">
				<label for="login-pass">Password</label>
				<input type="password" name="login-pass" class="form-control" placeholder="Password" />
			</div>
	  	<div class="form-group">
				<input type="checkbox" name="remember"/>
				<label for="remember">Remember Me</label>
			</div>
			<button name="form" value="Login" type="submit" class="btn btn-success" style="width: 10em;">Login</button>
		</form>
	</div>

<!--=================================== EXTRA DETAILS ===================================-->
	<div id="register" class="col-xs-12 col-sm-8 col-md-6">
		<div id="reg_top" class="col-xs-offset-2 col-xs-10 col-sm-11 col-md-12">
			<h3 class="bold"><code>New User?</code></h3>
			Join e-Classroom now.<br/>
			It takes less than a minute.<br/>
			<ul>
				<li>Post your questions</li>
				<li>Answer the questions</li>
				<li>Upload Assignments</li>
				<li>Auto Attendance System</li>
			</ul>
		</div>
		<div id="reg_bottom" class="col-xs-12 col-sm-12 col-md-12 center">
			<button type="button" onclick="toggle('reg-form')" class="btn btn-success" style="width: 10em;">Register</button>
		</div>
	</div>
</div>

<?
}
else
	header("Location: wall.php?t=1");
include("includes/footer.php");
?>