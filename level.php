<?
include("includes/header.php");

if($_SESSION['logged_in'] == 1){
	if($_SESSION['level'] == 2){
?>
<!--=================================== MAIN DIV   ===================================-->
<div id="main" class="row">

	<div class="form-group center">
		<label class="font-big">
			Change User Level
		</label>
	</div>
		
<!--=================================== SELECT USERS DIV   ===================================-->
	<form id="frm-build" method="post" action="includes/process.php">
		<div class="row col-xs-12 col-sm-12 col-md-6">
			<div class="form-group center">
				<label class="font-big col-md-12">Users</label>
				<select id="user" name="user" multiple class="center font-big" style="height: 10em;">
				<?
					//if(isset($_GET['l'])){
						echo @$_SESSION['html_cache'];
					//}
				?>
				</select>
			</div>
		</div>

<!--=================================== SELECT LEVEL DIV   ===================================-->
		<div class="row col-xs-12 col-sm-12 col-md-6">
			<div class="form-group center">
				<label class="font-big col-md-12">Level</label>
				<select id="year" name="level" class="center font-big" style="height: 2em;">
				  <option id="0" value="0">0 - Student</option>
				  <option id="1" value="1">1 - Teacher</option>
				  <option id="2" value="2">2 - Admin</option>
				</select>
			</div>
		</div>
		<div class="form-group center row col-xs-12 col-md-12">
			<button name="form" value="admin" type="submit" class="btn btn-success" style="width: 10em;">Update Level</button>
		</div>
	</form>
</div>

<?
	}
	else
		header("Location: join.php?t=1&msg=You+are+not+a+admin");
}
else
  header("Location: login.php");

include("includes/footer.php");
?>