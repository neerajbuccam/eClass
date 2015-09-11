<?
include("includes/header.php");

if($_SESSION['logged_in'] == 1){
		if(!isset($_GET['courses']) || $_GET['courses'] != 1)
			header("Location: includes/process.php?t=1&courses=0");	//	update timer
?>
<!--=================================== MAIN DIV   ===================================-->
<div id="main" class="row">
	<form method="post" action="includes/process.php">
		<div class="form-group center">
			<div class="row col-xs-12 col-md-12">
				<div class="form-group msg">
					<label><? if(!isset($_SESSION['course_select'])) echo $_SESSION['join_msg']; ?></label>
				</div>
				<select id="course" name="course" multiple class="center font-big">
				<?
					if($_GET['courses'] == "1")
						echo @$_SESSION['html_cache'];	//	print content from html_cache session variable
				?>
				</select>
			</div>
		</div>
		<div class="form-group center">
			<div class="row col-xs-6 col-md-6 pad-top-2 pad-bottom-1">
				<button name="join" value="join class" type="submit" class="btn btn-success" style="width: 10em;">Join Class</button>
			</div>
			<div class="row col-xs-6 col-md-6 pad-top-2 pad-bottom-1">
			<?
				if($_SESSION['level'] == 1)
					echo "<button name=\"end\" value=\"end class\" type=\"submit\" class=\"btn btn-danger\" style=\"width: 10em;\">End Class</button>";
			?>
			</div>
		</div>
	</form>
</div>

<?
}
else
  header("Location: login.php");

include("includes/footer.php");
?>