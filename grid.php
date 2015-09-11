<?
include("includes/header.php");

if($_SESSION['logged_in'] == 1){
	if(@$_SESSION['course_select'] == 1){
?>
<!--=================================== MAIN DIV   ===================================-->
<div id="main" class="row">
<!--=================================== Ask Question DIV   ===================================-->
	<div class="col-xs-12 col-sm-12 col-md-12">
	 	<div id="box1" class="col-xs-6 col-sm-6 col-md-6 box center">
			<a href="ask.php"><label class="font-big">Ask Question</label><br/>
			<img src="images/quest.png"></a>
		</div>

<!--=================================== Ask Poll Question DIV   ===================================-->
		<div id="box2" class="col-xs-6 col-sm-6 col-md-6 box center">
			<a href="poll.php"><label class="font-big">Ask Poll Question</label><br/>
			<img src="images/poll.png"></a>
		</div>
	</div>

<!--=================================== Image Upload DIV   ===================================-->
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div id="box3" class="col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-xs-6 col-sm-6 col-md-6 box center" style="border-right: none;">
			<a href="image.php"><label class="font-big">Image Upload</label><br/>
			<img src="images/image.png"></a>
		</div>
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