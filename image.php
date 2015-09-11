<?
include("includes/header.php");

if($_SESSION['logged_in'] == 1){
	if(@$_SESSION['course_select'] == 1){
?>
<!--=================================== MAIN DIV   ===================================-->
<div id="main" class="row">
	<form method="post" action="includes/process.php" enctype="multipart/form-data">
		<div class="form-group center">
	  		<div class="form-group msg">
				<label class="font-big">
				<?
					if(isset($_GET['msg'])){
						echo $_GET['msg'];	//	print error message
					}
				?>
				</label>
			</div>
			<label class="font-big col-xs-12 col-sm-12 col-md-12">Share a image with your class!</label>
			<div class="col-xs-offset-2 col-sm-offset-3 col-md-offset-4 col-xs-4 col-sm-4 col-md-4 pad-top-2 font-big">
				<input type="file" name="img"/>
			</div>
			<div class="row col-xs-12 col-md-12 pad-top-2 pad-bottom-2">
				<button name="image" value="upload" type="submit" class="btn btn-success" style="width: 10em;">Post Image</button>
			</div>
		</div>
	</form>
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