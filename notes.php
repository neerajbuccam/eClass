<?
include("includes/header.php");

if($_SESSION['logged_in'] == 1){
	if(@$_SESSION['course_select'] == 1){
		if(!isset($_GET['notes']) || $_GET['notes'] != 1)
			header("Location: includes/process.php?t=0&notes=0");	//	update timer
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
		if($_GET['notes'] == "1")
			echo @$_SESSION['html_cache'];	//	print content from html_cache session variable
	?>
  	</div>
<?
	if($_SESSION['level'] == 1){
?>
	<form method="post" action="includes/process.php" enctype="multipart/form-data">
	  <div class="form-group center">
			<label class="font-big col-xs-12 col-sm-12 col-md-12 pad-top-1" style="border-top: 1px groove;">Add Notes</label>
			<div class="col-xs-offset-2 col-sm-offset-3 col-md-offset-4 col-xs-4 col-sm-4 col-md-4 pad-top-1 font-big">
				<input type="file" name="file"/>
			</div>
			<div class="row col-xs-12 col-md-12 pad-top-2 pad-bottom-2">
				<button name="note" value="upload" type="submit" class="btn btn-success" style="width: 10em;">Upload Note</button>
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