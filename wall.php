<?
include("includes/header.php");

if($_SESSION['logged_in'] == 1){
	if(@$_SESSION['course_select'] == 1){
		if(!isset($_GET['wall']) || $_GET['wall'] != 1)
			header("Location: includes/process.php?t=0&wall=0");	//	update timer
?>
<!--=================================== MAIN DIV   ===================================-->
<div id="main" class="row">
  <div class="col-xs-12 col-md-12">
  <?
		if($_GET['wall'] == "1")
			echo @$_SESSION['html_cache'];	//	print content from html_cache session variable
	?>
  </div>
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