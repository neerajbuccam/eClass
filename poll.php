<?
include("includes/header.php");

if($_SESSION['logged_in'] == 1){
	if(@$_SESSION['course_select'] == 1){
?>
<!--=================================== MAIN DIV   ===================================-->
<div id="main" class="row">
	<form method="post" action="includes/process.php">
		<div class="center form-group">
	  	<div class="form-group msg">
				<label class="font-big">
				<?
					if(isset($_GET['msg'])){
						echo $_GET['msg'];
						$quest = @$_SESSION['quest'];
						$opt1 = @$_SESSION['opt1'];
						$opt2 = @$_SESSION['opt2'];
						$opt3 = @$_SESSION['opt3'];
						$opt4 = @$_SESSION['opt4'];
					}
					else{
						$quest = "";$opt1 = "";$opt2 = "";$opt3 = "";$opt4 = "";
					}
				?>
				</label>
			</div>
			<div class="pad-bottom-1 col-xs-12 col-md-12">
				<label class="font-big col-md-12">Please feel free to ask your poll query!</label>
				<textarea id="quest" name="quest" rows="5" cols="50" placeholder="Enter your question here"><?echo $quest;?></textarea> 
			</div>
			
			<div class="pad-bottom-1 col-xs-1 col-md-2"></div>
			<div class="pad-bottom-1 col-xs-10 col-md-8">
				<input type="text" name="opt1" value="<?echo $opt1;?>" class="form-control pad-bottom-1" placeholder="Option 1"/>
				<input type="text" name="opt2" value="<?echo $opt2;?>" class="form-control pad-bottom-1" placeholder="Option 2"/>
				<input type="text" name="opt3" value="<?echo $opt3;?>" class="form-control pad-bottom-1" placeholder="Option 3"/>
				<input type="text" name="opt4" value="<?echo $opt4;?>" class="form-control pad-bottom-1" placeholder="Option 4"/>
			</div>
			<div class="pad-bottom-1 col-xs-1 col-md-2"></div>

			<div class="col-xs-12 col-md-12 pad-bottom-1 center">
				<button name="query" value="poll" type="submit" class="btn btn-success" style="width: 10em;">Ask Poll Question</button>
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