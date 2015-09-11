<!--
              Project Name: eClassroom
                   Authors: Neeraj Buccam
                            Girish Matarbhog
                            Amit Chiplunkar

              Roll Numbers: 1360, 1336, 1338

              Release Date: 07-May-2015
                   Version: 1.0
-->
<?
session_start();

//	set cookie if user asked to remember log in attempt
if(isset($_COOKIE['user']))
	setcookie('PHPSESSID',$_COOKIE['user'],time()+84600,'/');

	$_SESSION['root'] =  $_SERVER["DOCUMENT_ROOT"];		//	stores document root in session
	$_SESSION['join_msg'] = "You have not joined any course, Choose one!";

	$_SESSION['menu3'] = "grid.php?t=0";		//	3rd button link in menu
	$_SESSION['menu3_src'] = "grid.png";		//	3rd button image source in menu
	$_SESSION['title3'] = "Grid View";			//	page title when on 3rd button link
	$_SESSION['menu4'] = "profile.php?t=0";		//	4th button link in menu
	$_SESSION['menu4_src'] = "profile.png";		//	4th button image source in menu
	$_SESSION['title4'] = "Edit Profile";		//	page title when on 4th button link

if(@$_SESSION['level'] == 1 || @$_SESSION['level'] == 2){					// if teacher
	$_SESSION['menu2'] = "attend.php?t=0";		//	2nd button link in menu
	$_SESSION['menu2_src'] = "attendance.png";	//	2nd button image source in menu
	$_SESSION['title2'] = "Attendance";			//	page title when on 2nd button link
}
else{
	$_SESSION['menu2'] = "join.php?t=0";
	$_SESSION['menu2_src'] = "live.png";
	$_SESSION['title2'] = "";
}
if((basename($_SERVER['PHP_SELF']) != "join.php") && (basename($_SERVER['PHP_SELF']) != "build.php")){
	if(@$_SESSION['level'] == 0){
		$_SESSION['menu2'] = "profile.php?t=0";
		$_SESSION['menu2_src'] = "profile.png";
		$_SESSION['title2'] = "Edit Profile";
	}
	$_SESSION['menu3'] = "notes.php?t=0";
	$_SESSION['menu3_src'] = "notes.png";
	$_SESSION['title3'] = "Notes";
	$_SESSION['menu4'] = "assign.php?t=0";
	$_SESSION['menu4_src'] = "assign.png";
	$_SESSION['title4'] = "Assignments";
}

if(basename($_SERVER['PHP_SELF']) == "attend.php"){		//	if current page is attand.php
	$_SESSION['menu3'] = "grid.php?t=0";
	$_SESSION['menu3_src'] = "grid.png";
	$_SESSION['title3'] = "Grid View";
	$_SESSION['menu4'] = "profile.php?t=0";
	$_SESSION['menu4_src'] = "profile.png";
	$_SESSION['title4'] = "Edit Profile";
	$_SESSION['cur_page'] = "Attendance";
}
else if(basename($_SERVER['PHP_SELF']) == "wall.php"){
	header("Refresh: 20; url=".basename($_SERVER['PHP_SELF']));		//	reload wall page after every 20 seconds
	$_SESSION['menu2'] = "grid.php?t=0";
	$_SESSION['menu2_src'] = "grid.png";
	$_SESSION['title2'] = "Grid View";
	$_SESSION['cur_page'] = "Live Wall";
}
else if(basename($_SERVER['PHP_SELF']) == "grid.php")
	$_SESSION['cur_page'] = "Grid View";
else if(basename($_SERVER['PHP_SELF']) == "join.php")
	$_SESSION['cur_page'] = "Join Class";
else if(basename($_SERVER['PHP_SELF']) == "build.php")
	$_SESSION['cur_page'] = "Build Class";
else if(basename($_SERVER['PHP_SELF']) == "notes.php")
	$_SESSION['cur_page'] = "Notes Grabber";
else if(basename($_SERVER['PHP_SELF']) == "assign.php")
	$_SESSION['cur_page'] = "Assignments";
else if(basename($_SERVER['PHP_SELF']) == "index.php")
	$_SESSION['cur_page'] = "Welcome!";
else if(basename($_SERVER['PHP_SELF']) == "profile.php")
	$_SESSION['cur_page'] = "My Profile";
else if(basename($_SERVER['PHP_SELF']) == "ask.php")
	$_SESSION['cur_page'] = "Ask Question";
else if(basename($_SERVER['PHP_SELF']) == "poll.php")
	$_SESSION['cur_page'] = "Ask Poll";
else if(basename($_SERVER['PHP_SELF']) == "image.php")
	$_SESSION['cur_page'] = "Upload Image";
else if(basename($_SERVER['PHP_SELF']) == "answer.php")
	$_SESSION['cur_page'] = "Answer It";

if((basename($_SERVER['PHP_SELF']) != "index.php") && (basename($_SERVER['PHP_SELF']) != "process.php") && (basename($_SERVER['PHP_SELF']) != "database.php"))
	include("includes/top.php");

?>