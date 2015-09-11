<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="icon" type="image/png" href="images/favicon.ico">
	<script src="js/script.js" type="text/javascript"></script>
	<link href="css/style.css" rel="stylesheet" type="text/css">
	<link href="css/boot/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="css/boot/bootstrap-theme.css" rel="stylesheet" type="text/css">
	<title> E-classroom </title>
</head>

<body>
	<div id="wrap" class="container">
<div id="top" class="row">

<!--=================================== DIV TOGGLES   ===================================-->
	<div id="my-choice" class="row col-xs-5 col-sm-5 col-md-5">
		<div class="center">
			Thank You for choosing e-Classroom as your daily Classroom App & we know, you'l say, its "MY CHOICE!"
		</div>
	</div>
<?
if(@$_SESSION['logged_in'] == 1 && @$_SESSION['level'] != 2){
?>
	<div id="drop-menu">
			<a onclick="toggle('menu')"><img title="Click to drop" src="images/menu.png"></a>
	</div>
	<div id="drop-side-menu">
			<a onclick="toggle('side-menu')"><img title="Click to drop" src="images/menu2.png"></a>
	</div>

<!--=================================== LEFT SIDE MENU ===================================-->
	<div id="side-menu">
<?
if($_SESSION['level'] == 1){
?>
		<a href="build.php"><button id="side-menu1" class="left center"><img title="Build Class" src="images/build.png"></button></a>
<?
}
?>
		<a href="join.php"><button id="side-menu2" class="left center"><img title="Join Class" src="images/live.png"></button></a>
<?
if((@$_SESSION['course_select'] == 1) && (basename($_SERVER['PHP_SELF']) != "grid.php") && (basename($_SERVER['PHP_SELF']) != "wall.php") && (basename($_SERVER['PHP_SELF']) != "build.php") && (basename($_SERVER['PHP_SELF']) != "join.php") && (basename($_SERVER['PHP_SELF']) != "attend.php")){
?>
		<a href="grid.php"><button id="side-menu2" class="left center"><img title="Grid View" src="images/grid.png"></button></a>
<?
}
?>
	</div>

<!--=================================== MAIN MENU   ===================================-->
	<span class="col-xs-12 col-md-3">
			<div id="logo" onclick="toggle('my-choice')" class="col-xs-12 col-md-12 left center">
				<img src="images/logo.png">
			</div>
	</span>
	<span class="col-xs-12 col-md-9" style="padding: 0px;">
		<div id="menu" class="col-xs-12 col-md-12">
			<a href="wall.php?t=0" title="Wall"><button id="menu1" class="col-md-3 left center"><img src="images/wall.png"></button></a>
			<a href="<?echo $_SESSION['menu2'];?>" title="<?echo $_SESSION['title2'];?>"><button id="menu2" class="col-md-2 left center"><img src="images/<?echo $_SESSION['menu2_src'];?>"></button></a>
			<a href="<?echo $_SESSION['menu3'];?>" title="<?echo $_SESSION['title3'];?>"><button id="menu3" class="col-md-2 left center"><img src="images/<?echo $_SESSION['menu3_src'];?>"></button></a>
			<a href="<?echo $_SESSION['menu4'];?>" title="<?echo $_SESSION['title4'];?>"><button id="menu4" class="col-md-2 left center"><img src="images/<?echo $_SESSION['menu4_src'];?>"></button></a>
			<a href="includes/process.php?t=1&logout=1" title="Logout"><button id="menu5" class="col-md-3 left center"><img src="images/logout.png"></button></a>
		</div>
<!--=================================== TITLE BAR   ===================================-->
		<div id="current" class="col-xs-12 col-md-12 center bold">
			<a href="<?echo basename($_SERVER['PHP_SELF']);?>?t=1&ans=1"><div id="cur_page" title="Click to Reload" class="col-xs-3 col-md-3 full-height"><?echo @$_SESSION['cur_page'];?></div></a>
			<div id="cur_subject" class="col-xs-5 col-md-7 full-height">
				<?echo @$_SESSION['course_code']." - ".@$_SESSION['course_name'];?>
			</div>
			<div id="cur_timer" class="col-xs-4 col-md-2 full-height">
			<?
				$_SESSION['page'] = basename($_SERVER['PHP_SELF']);
				if(!isset($_GET['t']) || $_GET['t'] != 1)
					header("Location: includes/process.php?t=0");
				$t = @getdate($_SESSION['duration']);
				$sec = $t['seconds'];
				$min = $t['minutes'];
				$hour = $t['hours']-1;
				echo "<script>sec=$sec; min=$min; hour=$hour;</script>";
//	=================================== TIMER SCRIPT   ===================================-->
			if(@$_SESSION['course_select'] == 1){
				echo "<div id=\"time\"></div>";
				echo "<script type=\"text/javascript\"> t_interval = setInterval(function(){timer()},1000);</script>";
			}
			else
				echo "<script type=\"text/javascript\">var t_interval; clearInterval(t_interval);</script>";

			?>
			</div>
		</div>
	</span>

<?
}
else{
?>

	<span class="col-xs-12 col-md-12">
			<div id="logo" onclick="toggle('my-choice')" class="col-xs-12 col-md-12 left center">
				<img src="images/logo.png">
			</div>
	</span>

	<?if(@$_SESSION['level'] == 2){ ?>
		<a href="includes/process.php?t=1&logout=1" title="Logout"><button id="logout" ><img src="images/logout.png"></button></a>
	<? } ?>
<?
}
?>
</div>