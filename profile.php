<?
include("includes/header.php");

if($_SESSION['logged_in'] == 1){         
		if(!isset($_GET['profile']) || $_GET['profile'] != 1)
			header("Location: includes/process.php?t=1&profile=0");	//	update timer
?>
<!--=================================== MAIN DIV   ===================================-->
<div id="main" class="row">
	<form method="post" action="includes/process.php">
		<div class="center form-group">
	  	<div class="form-group msg">
				<label class="font-big"><? if(isset($_GET['msg'])) echo $_GET['msg']; ?></label>
			</div>
			<div class="pad-bottom-1 col-xs-12 col-md-12">
				<label class="font-big col-md-12">My Profile</label>
			</div>
			<?
				if($_GET['profile'] == "1")
					echo @$_SESSION['html_cache'];	//	print content from html_cache session variable
			?>
			<div class="col-xs-12 col-md-12 pad-bottom-1 center">
				<button name="edit" value="profile" type="submit" class="btn btn-success" style="width: 10em;">Update Profile</button>
			</div>
		</div>
	</form>
</div>

<?
}
else
  header("Location: login.php");

include("includes/footer.php");
?>