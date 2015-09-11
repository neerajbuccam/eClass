<?
include("includes/header.php");

if($_SESSION['logged_in'] == 1){
	if($_SESSION['level'] == 1){
?>

<!--=================================== MAIN DIV   ===================================-->
<div id="main" class="row">

<?
if(@$_GET['attend'] == 1){
?>
<!--=================================== PRINT ATTENDEE LIST DIV   ===================================-->
	<div class="row col-xs-12 col-sm-12 col-md-2 center"></div>
	<div class="row col-xs-12 col-sm-12 col-md-9 center">
		<label class="font-big col-md-12"><?echo @$_GET['course'];?> attendance on <?echo @$_GET['date'];?></label>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>User Id</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Status</th>
				</tr>
			</thead>
			<? 
				if(@$_GET['attend'] == 1)
					echo @$_SESSION['html_cache'];
			?>
		</table>
	</div>
<?
}
else{
?>
<!--=================================== GET ATTENDANCE FORM ===================================-->
	<form method="post" action="includes/process.php">
		<div class="row col-xs-12 col-sm-6 col-md-3">
			<div class="form-group center pad-top-1">
				<label class="font-big col-md-12">Year</label>
				<select onchange="submit()" id="year" name="year" multiple class="center font-big">
				  <option value="1" <? if(@$_GET['year'] == 1) echo "selected=\"selected\""; ?> >First Year</option>
				  <option value="2" <? if(@$_GET['year'] == 2) echo "selected=\"selected\""; ?> >Second Year</option>
				  <option value="3" <? if(@$_GET['year'] == 3) echo "selected=\"selected\""; ?> >Third Year</option>
				</select>
				<input type="hidden" name="page" value="attend"/>
			</div>
		</div>
	</form>
		
	<form method="post" action="includes/process.php">
		<div class="row col-xs-12 col-sm-6 col-md-6">
			<div class="form-group center">
				<label class="font-big col-md-12">Courses</label>
				<select id="course" name="course" multiple class="center font-big">
				<?
					if(isset($_GET['year']))
						echo @$_SESSION['html_cache'];
				?>
				</select>
			</div>
		</div>
		<div class="row col-xs-12 col-sm-4 col-md-3"></div>
		<div class="row col-xs-12 col-sm-4 col-md-3">
			<div class="form-group center">
				<label class="font-big col-md-12">Date</label>
				<input type="date" name="date" min="2015-01-01" class="form-control" />
			</div>
		</div>
		<div class="form-group center row col-xs-12 col-md-12 pad-top-2">
			<button name="show" value="attend" type="submit" class="btn btn-success" style="width: 10em;">Show Attendance</button>
		</div>
	</form>
<?
}
?>

</div>

<?
	}
	else
		header("Location: join.php?t=1&msg=You+are+not+a+teacher");
}
else
  header("Location: login.php");

include("includes/footer.php");
?>