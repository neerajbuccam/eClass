<?
include("includes/header.php");

if($_SESSION['logged_in'] == 1){
	if(@$_SESSION['course_select'] == 1){
?>
<!--=================================== MAIN DIV   ===================================-->
<div id="main" class="row">
	<form method="post" action="includes/process.php">
		<div class="center form-group">
			<div class="center pad-bottom-1 col-xs-12 col-md-12">
	  		<div class="form-group msg">
				<label class="font-big">
				<?
					if(isset($_GET['msg'])){
						echo $_GET['msg'];	//	print error message
						$quest = @$_SESSION['quest'];
					}
					else{
						$quest = "";
					}
				?>
				</label>
<!--=================================== ASK QUESTION DIV   ===================================-->
			</div>
				<label class="font-big col-md-12">Please feel free to ask your query!</label>
				<textarea id="quest" name="quest" rows="5" cols="55" placeholder="Enter your question here"><?echo $quest;?></textarea> 
			</div>
			
			<div class="col-xs-12 col-md-12 pad-bottom-1 center">
				<button name="query" value="ask" type="submit" class="btn btn-success" style="width: 10em;">Ask Question</button>
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