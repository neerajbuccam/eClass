<?
include("includes/header.php");

if($_SESSION['logged_in'] == 1){
	if(@$_SESSION['course_select'] == 1){
		if(!isset($_GET['ans']) || $_GET['ans'] != 1)
			header("Location: includes/process.php?ans=0");
?>
<!--=================================== MAIN DIV   ===================================-->
<div id="main" class="row">
<?
			
			if($_SESSION['q_type'] != "image"){
?>
<!--=================================== VIEW IMAGE AND DOWNLOAD DIV   ===================================-->
				<div class="center pad-bottom-1 col-xs-12 col-md-12">
					<div class="form-group msg">
						<label class="font-big"><? if(isset($_GET['msg'])) echo $_GET['msg']; ?></label>
					</div>
						<label class="font-big col-md-12">Q. <?echo @$_SESSION['question'];?></label>
				</div>
<? 
			}
			else{
?>
				<div class="col-xs-12 col-md-12 pad-bottom-1 center">
					<a href="includes/process.php?q=<?echo $_SESSION['question'];?>"><button class="btn btn-success">Download this Image</button></a>
				</div>
				<div onmousedown="downimg(event);" onmouseup="upimg(event);">
				<div class="col-xs-12 col-md-12 pad-bottom-1 center" onmousemove="mvImg(event);" style="overflow: hidden; height: 25em;">
					<img id="img" class="ans-img" src="<? echo $_SESSION['question']; ?>"/>
				</div>
				</div>
<?
			}
?>
<!--=================================== ANSWER QUESTION FORM ===================================-->
	<form method="post" action="includes/process.php">
		<div class="center form-group">
<?
			if(@$_SESSION['q_type'] == "ask"){
?>
<!--=================================== ANSWER ASK QUESTION DIV   ===================================-->
			<div class="center pad-bottom-1 col-xs-12 col-md-12">
		  	<textarea id="quest" name="answer" rows="5" cols="48" placeholder="Enter your answer here"><?echo@$_SESSION['ans'];?></textarea> 
			</div>
<? 			}
			else if(@$_SESSION['q_type'] == "poll"){
				$ans = @$_SESSION['answered'];
?>
<!--=================================== ANSWER POLL QUESTION DIV   ===================================-->
				<div class="pad-bottom-1 col-xs-1 col-sm-3 col-md-4"></div>
				<div class="pad-bottom-1 col-xs-11 col-sm-9 col-md-8">
			  	<label class="left">
						<input type="radio" name="answer" value="<? echo @$_SESSION['opt1']; ?>" <?if($ans == 1) echo "disabled=\"disabled\"";?>/> <? echo @$_SESSION['opt1']; ?>
					</label>
				</div>
				<div class="pad-bottom-1 col-xs-1 col-sm-3 col-md-4"></div>
				<div class="pad-bottom-1 col-xs-11 col-sm-9 col-md-8">
		  		<label class="left">
						<input type="radio" name="answer" value="<? echo @$_SESSION['opt2']; ?>" <?if($ans == 1) echo "disabled=\"disabled\"";?>/> <? echo @$_SESSION['opt2']; ?>
					</label>
				</div>
				<div class="pad-bottom-1 col-xs-1 col-sm-3 col-md-4"></div>
				<div class="pad-bottom-1 col-xs-11 col-sm-9 col-md-8">
		  		<label class="left">
						<input type="radio" name="answer" value="<? echo @$_SESSION['opt3']; ?>" <?if($ans == 1) echo "disabled=\"disabled\"";?>/> <? echo @$_SESSION['opt3']; ?>
					</label>
				</div>
				<div class="pad-bottom-1 col-xs-1 col-sm-3 col-md-4"></div>
				<div class="pad-bottom-1 col-xs-11 col-sm-9 col-md-8">
		  		<label class="left">
						<input type="radio" name="answer" value="<? echo @$_SESSION['opt4']; ?>" <?if($ans == 1) echo "disabled=\"disabled\"";?>/> <? echo @$_SESSION['opt4']; ?>
					</label>
				</div>
<?
			}
?>
			</div>
<?
		
		if($_SESSION['q_type'] != "image"){
?>
			<div class="col-xs-12 col-md-12 pad-bottom-1 center">
				<button name="postAns" value="answerIt" type="submit" <?if(@$ans == 1) echo "disabled=\"disabled\"";?> class="btn btn-success" style="width: 10em;"><? echo @$_SESSION['btn_value']; ?></button>
			</div>
<?
		}
		if($_GET['ans'] == "1")
			echo @$_SESSION['html_cache'];	//	print content from html_cache session variable
?>
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