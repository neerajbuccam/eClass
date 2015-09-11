<?
include("includes/header.php");

if($_SESSION['logged_in'] == 1){
	if($_SESSION['level'] == 1){
?>
<!--=================================== MAIN DIV   ===================================-->
<div id="main" class="row">

	<div class="form-group msg">
		<label class="font-big">
		<?
			if(isset($_GET['msg'])){
				echo $_GET['msg'];	//	print error message
				$duration = @$_SESSION['duration'];
			}
			else{
				$duration = "";
			}
		?>
		</label>
	</div>

<!--=================================== SELECT YEAR DIV   ===================================-->
	<form method="post" action="includes/process.php">
		<div class="row col-xs-12 col-sm-12 col-md-6">
			<div class="form-group center">
				<label class="font-big col-md-12">Year</label>
				<select onchange="submit()" id="year" name="year" multiple class="center font-big">
				  <option value="1" <? if(@$_GET['year'] == 1) echo "selected=\"selected\""; ?> >First Year</option>
				  <option value="2" <? if(@$_GET['year'] == 2) echo "selected=\"selected\""; ?> >Second Year</option>
				  <option value="3" <? if(@$_GET['year'] == 3) echo "selected=\"selected\""; ?> >Third Year</option>
				</select>
				<input type="hidden" name="page" value="build"/>
				<br/><br/>
				<label class="font-big col-md-6 col-md-offset-3  col-sm-6 col-sm-offset-3">Lecture Duration
					<input type="text" name="duration" value="<?if(isset($_GET['msg'])){echo @$_SESSION['duration'];}?>" class="form-control" placeholder="Hours" form="frm-build"/>
				</label>
			</div>
		</div>
	</form>
		
<!--=================================== SELECT COURSE DIV   ===================================-->
	<form id="frm-build" method="post" action="includes/process.php">
		<div class="row col-xs-12 col-sm-12 col-md-6">
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
		
		<div class="form-group center row col-xs-12 col-md-12">
			<button name="build" value="build class" type="submit" class="btn btn-success" style="width: 10em;">Build Class</button>
		</div>
	</form>

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