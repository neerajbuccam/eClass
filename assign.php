<?
include("includes/header.php");

if($_SESSION['logged_in'] == 1){
	if(@$_SESSION['course_select'] == 1){
		if(!isset($_GET['assign']) || $_GET['assign'] != 1)
			header("Location: includes/process.php?t=0&assign=0");	//	update timer
?>
<!--=================================== MAIN DIV   ===================================-->
<div id="main" class="row">
  	<div class="msg">
			<label class="font-big">
			<?
				if(isset($_GET['msg']))
					echo $_GET['msg'];
			?>
			</label>
		</div>
  	<div class="col-xs-12 col-md-12">
	<?
		if($_GET['assign'] == "1")
			echo @$_SESSION['html_cache'];	//	print content from html_cache session variable
	?>
  	</div>
<?
	if($_SESSION['level'] == 1 && @$_GET['all'] != "1"){	//	if teacher
?>
<!--=================================== ADD ASSIGNMENT FORM ===================================-->
	<form method="post" action="includes/process.php" enctype="multipart/form-data">
	  <div class="form-group center">
			<label class="font-big col-xs-12 col-sm-12 col-md-12 pad-top-1" style="border-top: 1px groove;">Create Assignment</label>
			<div class="font-big center pad-bottom-1 col-xs-offset-3 col-xs-6 col-md-6">
		  		<input name="ass_name" placeholder="Assignment Name"/>
			</div>
			<div class="form-group center col-xs-12 col-sm-12 col-md-12">
				<label class="font-big col-xs-offset-1 col-xs-4 col-md-4">Due Date</label>
				<label class="font-big col-xs-offset-1 col-xs-4 col-md-4">Due Time</label>
				<input class="font-big col-xs-offset-1 col-xs-4 col-md-4" type="date" name="date" min="2015-01-01" class="form-control" />
				<input class="font-big col-xs-offset-1 col-xs-4 col-md-4" type="time" name="time" class="form-control" />
			</div>
			<div class="center pad-bottom-1 col-xs-12 col-md-12">
		  		<textarea id="quest" name="quest" rows="5" cols="48" placeholder="Enter assignment details here"><?echo@$_SESSION['quest'];?></textarea> 
			</div>
			<div class="row col-xs-12 col-md-12 pad-top-2 pad-bottom-2">
				<button name="assign_btn" value="upload" type="submit" class="btn btn-success" style="width: 10em;">Add Assignment</button>
			</div>
		</div>
	</form>
<?
	}
?>
</div>

<?
	}
	else
		header("Location: join.php?t=1&msg=".$_SESSION['join_msg']);
}
else
  header("Location: login.php");

include("includes/footer.php");
?>