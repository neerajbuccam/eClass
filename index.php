<?
	include("includes/header.php");

if($_SESSION['logged_in'] == 1){
  if($_SESSION['level'] == 1)
    header("Location: build.php");
  else
    header("Location: join.php");
}
else
  header("Location: login.php");

?>