<?
	session_start();
	include_once($_SESSION['root']."/eclass/includes/database.php");
	include_once($_SESSION['root']."/eclass/includes/constants.php");
	include_once($_SESSION['root']."/eclass/includes/form.php");

class process{

	function process(){
//	user logged in and timer is not updated
		if(@$_GET['t'] == "0" && $_SESSION['logged_in'] == 1)
			$this->duration($_SESSION['page']);
//	Admin can change level of user
		if(@$_POST['form'] == "admin" && $_SESSION['logged_in'] == 1)
			$this->change_level($_POST['user'], $_POST['level']);
//	get level of user
		else if(@$_GET['get_level'] == "1")
			$this->get_level(@$_GET['user']);
//	login form is submitted
		else if(@$_POST['form'] == "Login")
			$this->login();
//	register form is submitted
		else if(@$_POST['form'] == "Register")
			$this->register();
//	logout is clicked by user
		else if(@$_GET['logout'] == "1")
			$this->logout();
//	profile button is clicked (view/edit profile)
		else if(@$_GET['profile'] == "0")
			$this->get_profile($_SESSION['user']);
//	update profile
		else if(@$_POST['edit'] == "profile")
			$this->update_profile($_SESSION['user'],$_POST['fname'],$_POST['lname'],$_POST['address'],$_POST['year']);
//	fetch content to be posted on wall
		else if(@$_GET['wall'] == "0")
			$this->get_wall();
//	answer page is generated with all comments
		else if(isset($_POST['q_id']))
			$this->answer_page($_POST['q_id'],$_POST['q_type'],$_POST['question'],$_POST['btn_value'],$_POST['opt1'],$_POST['opt2'],$_POST['opt3'],$_POST['opt4']);
//	answer to a wall query question is submitted
		else if(isset($_POST['postAns']))
			$this->post_answer($_SESSION['user'],$_SESSION['q_id'],$_POST['answer']);
//	fetch courses based on year
		else if(isset($_POST['year']))
			$this->courses($_POST['year']);
//	create/build live class
		else if(@$_POST['build'] == "build class")
			$this->build($_POST['course'],$_POST['duration']);
//	fetch live courses
		else if(@$_GET['courses'] == "0")
			$this->live_courses();
//	join course
		else if(@$_POST['join'] == "join class")
			$this->join($_POST['course']);
//	end live class
		else if(@$_POST['end'] == "end class")
			$this->end($_POST['course']);
//	view attendance
		else if(@$_POST['show'] == "attend")
			$this->show_attend($_POST['course'],$_POST['date']);
//	submit question
		else if((@$_POST['query'] == "ask") || (@$_POST['query'] == "poll"))
			$this->query($_SESSION['course_code'],$_SESSION['user'],$_POST['query'],$_POST['quest'],@$_POST['opt1'],@$_POST['opt2'],@$_POST['opt3'],@$_POST['opt4']);
//	upload image
		else if(@$_POST['image'] == "upload")
			$this->upload(@$_FILES['img'],'images','image.php',"0","0","0");
//	upload note
		else if(@$_POST['note'] == "upload")
			$this->upload(@$_FILES['file'],'notes','notes.php',"0","0","0");
//	fetch notes
		else if(@$_GET['notes'] == "0")
			$this->notes();
//	delete note
		else if(isset($_POST['note_id']) && isset($_POST['note_location']))
			$this->delete(TBL_NOTES, "note_id", @$_POST['note_id'], "notes.php", "Failed+to+delete+Note", "1", $_POST['note_location']);
//	download image function
		else if(isset($_GET['q']))
			$this->download(@$_GET['q']);
//	add new assignment
		else if(isset($_POST['assign_btn']))
			$this->add_assign(@$_POST['ass_name'],$_SESSION['course_code'],@$_POST['quest'],@$_POST['date'],@$_POST['time']);
//	fetch assignments
		else if(@$_GET['assign'] == "0")
			$this->get_assign();
//	upload assignment
		else if(isset($_POST['answer_assign_btn']))
			$this->upload(@$_FILES['assign'],'assign','assign.php',@$_POST['ass_name'],@$_POST['ass_id'],@$_POST['old_ass_name']);
//	hide assignment
		else if(isset($_POST['hide_assign_btn']))
			$this->toggle_assign(@$_POST['ass_id'],"1");
//	unhide assignment
		else if(isset($_POST['unhide_assign_btn']))
			$this->toggle_assign(@$_POST['ass_id'],"0");
//	view submitted assignments
		else if(isset($_POST['view_assign']))
			$this->view_assign(@$_POST['ass_id']);
	}

//	get the time left for course to end
	function duration($page){
		global $database;

		$date = $this->get_Date_Time("date");
		$time = $this->get_Date_Time("time");
		$database->time_left("class",$_SESSION['course_code'],"0",$date,$time);
		header("Location: ../$page?t=1");
	}

//	get level of user
	function get_level($user){
		global $database;

		echo $database->get_level($user);
	}

//	Admin can change level of user
	function change_level($user,$level){
		global $database;

		$database->modify_user($user,$level);
		header("Location: ../level.php?t=1");
	}

//	login form is submitted, validate and check user
	function login(){
		global $database, $form;
		
		$uname = $form->input($_POST['login-email']);	//	strip non escaped input
		$pass = $form->input($_POST['login-pass']);	//	strip non escaped input
		$pass = md5($pass);
		if(isset($_POST['remember']))
			$remember = 1;
		else
			$remember = 0;

		$ret =  $database->check_login($uname,$pass,$remember);	//	check if user is valid
		if($ret == 1){
			$_SESSION['logged_in'] = 1;
			header("Location: ../index.php");
		}
		else if($ret == "admin"){
			$_SESSION['logged_in'] = 1;
			header("Location: ../level.php?t=1&l=1");
		}
		else if($ret == "email"){
			$_SESSION['logged_in'] = 0;
			header("Location: ../login.php?log_msg=email+not+found");
		}
		else if($ret == "pass"){
			$_SESSION['logged_in'] = 0;
			header("Location: ../login.php?log_msg=password+mismatch");
		}
	}

//	registers user, validate user input and add user to database
	function register(){
		global $database, $form;

		$fname = $form->input($_POST['fname']);			//	strip invalid characters
		$lname = $form->input($_POST['lname']);			//
		$email = $form->input($_POST['reg-email']);		//
		$pass = $form->input($_POST['reg-pass']);		//
		$address = $form->input($_POST['address']);		//
		$year = $form->input($_POST['year']);			//

		$ret1 = $form->validate($fname,"name");			//	validate user input
		$ret2 = $form->validate($lname,"name");			//
		$ret3 = $form->validate($email,"email");		//
		$ret4 = $form->validate($pass,"pass");			//
		$ret5 = $form->validate($address,"quest");		//
		$ret6 = $form->validate($year,"year");			//
		if($ret1 == 0)
			header("Location: ../login.php?reg_msg='First+Name'+not+valid");
		else if($ret2 == 0)
			header("Location: ../login.php?reg_msg='Last+Name'+not+valid");
		else if($ret3 == 0)
			header("Location: ../login.php?reg_msg='Email'+not+valid");
		else if($ret4 == 0)
			header("Location: ../login.php?reg_msg='Password'+cannot+be+empty");
		else if($ret5 == 0)
			header("Location: ../login.php?reg_msg='Address'+must+be+10+characters+long");
		else if($ret6 == 0)
			header("Location: ../login.php?reg_msg='Year'+not+selected");
		else{
			$pass = md5($pass);
			$date = $this->get_Date_Time("date");
			$time = $this->get_Date_Time("time");

			$ret = $database->add_user($fname,$lname,$email,$pass,$address,$year,$date,$time);	//	add user info to database
			if($ret == 1)
				header("Location: ../login.php?reg_msg=Registration+Successful");
			else if($ret == "email")
				header("Location: ../login.php?reg_msg=user+already+exist");
			else
				header("Location: ../login.php?reg_msg=Registration+failed");
		}
	}

//	logout and destroy session and cookie data
	function logout(){
		setcookie('user','',time()-3600,'/');
		setcookie('PHPSESSID','',time()-3600,'/');
		$_SESSION['logged_in'] = 0;
		session_destroy();
		header("Location: ../login.php");
	}

//	get profile info and store in html_cache session variable
	function get_profile($user){
		global $database;

		$_SESSION['html_cache'] = $database->create_profile_page($user);
		header("Location: ../profile.php?t=1&profile=1");
		if($_SESSION['flag'] == 1){
			$_SESSION['flag'] = 0;
			header("Location: ../profile.php?t=1&profile=1&msg=Profile+updated+successfully");
		}
	}

//	update profile info and store in database
	function update_profile($user,$fname,$lname,$address,$year){
		global $database, $form;

		$ret1 = $form->validate($fname,"name");
		$ret2 = $form->validate($lname,"name");
		$ret3 = $form->validate($address,"quest");
		$ret4 = $form->validate($year,"year");
		if($ret1 == 0)
			header("Location: ../profile.php?t=1&profile=1&msg='First+Name'+not+valid");
		else if($ret2 == 0)
			header("Location: ../profile.php?t=1&profile=1&msg='Last+Name'+not+valid");
		else if($ret3 == 0)
			header("Location: ../profile.php?t=1&profile=1&msg='Address'+must+be+10+characters+long");
		else if($ret4 == 0)
			header("Location: ../profile.php?t=1&profile=1&msg='Year'+not+selected");
		else{
			$fname = $form->input($fname);
			$lname = $form->input($lname);
			$email = $form->input($address);
			$year = $form->input($year);

			$ret = $database->update_user($user,$fname,$lname,$address,$year);	//	query for update user info
			if($ret == 1){
				$_SESSION['flag'] = 1;
				header("Location: ../profile.php?t=1&profile=0&msg=updated");
			}
			else
				header("Location: ../profile.php?t=1&profile=1&msg=Profile+update+failed");
		}
	}

//	get all courses based on input year and store in html_cache session variable
	function courses($year){
		global $database;

		$_SESSION['year'] = $year;
		$_SESSION['html_cache'] = $database->fetch_records($year);
		if(@$_POST['page'] == "build")
			header("Location: ../build.php?t=1&year=$year");
		else
			header("Location: ../attend.php?t=1&year=$year");
	}

//	create new lecture with input duration
	function build($course,$duration){
		global $database, $form;

		$_SESSION['course'] = $form->input($course);
		$_SESSION['duration'] = $form->input($duration);
		$ret1 = $form->validate($course,"pass");
		$ret2 = $form->validate($duration,"duration");
			$year = $_SESSION['year'];
		if($ret1 == 0){
			header("Location: ../build.php?t=1&year=$year&msg='Course'+not+valid");
			break;
		}
		else if($ret2 == 0){
			header("Location: ../build.php?t=1&year=$year&msg='Lecture+Duration'+not+valid");
			break;
		}

		$date = $this->get_Date_Time("date");
		$time = $this->get_Date_Time("time");
		$ret = $database->build_class($course, $date, $time, $duration);	//	creating class
		if($ret == 1)
			$this->join($course);	//	join the course after creating class
		else
			header("Location: ../build.php?t=1&year=$year&msg=Lecture+already+active");
	}

//	end the live lecture
	function end($course){
		global $database;

		$ret = $database->end_class($course);
		header("Location: ../join.php?t=1");
	}

//	get live lectures and store in html_cache session variable
	function live_courses(){
		global $database;

		$date = $this->get_Date_Time("date");
		$time = $this->get_Date_Time("time");
		$_SESSION['html_cache'] = $database->live($date,$time);
		header("Location: ../join.php?t=1&courses=1");
	}

//	join course and set course_select session variable to 1
	function join($course){
		global $database;

		$date = $this->get_Date_Time("date");
		$time = $this->get_Date_Time("time");
		$_SESSION['course_select'] = $database->join_class($course);
		$database->mark_attend($_SESSION['user'], $course, $date, $time);
		header("Location: ../grid.php?t=0");
	}

//	get attendance and store in html_cache session variable
	function show_attend($course, $date){
		global $database;	

		$_SESSION['html_cache'] = $database->view_attend($course, $date);
		header("Location: ../attend.php?t=1&attend=1&course=$course&date=$date");
	}

//	post query question
	function query($course_code, $user, $q_type, $quest, $opt1, $opt2, $opt3, $opt4){
		global $database, $form;

		$date = $this->get_Date_Time("date");
		$time = $this->get_Date_Time("time");

		$ret1 = $form->validate($quest,"quest");
		$ret2 = $form->validate($opt1,"pass");
		$ret3 = $form->validate($opt2,"pass");
		$ret4 = $form->validate($opt3,"pass");
		$ret5 = $form->validate($opt4,"pass");

		$quest = $form->input($quest);
		$opt1 = $form->input($opt1);
		$opt2 = $form->input($opt2);
		$opt3 = $form->input($opt3);
		$opt4 = $form->input($opt4);
		
		$_SESSION['quest'] = $quest;
		$_SESSION['opt1'] = $opt1;
		$_SESSION['opt2'] = $opt2;
		$_SESSION['opt3'] = $opt3;
		$_SESSION['opt4'] = $opt4;

		if($q_type == "ask"){
			if($ret1 == 0)
				header("Location: ../ask.php?t=1&msg=Your+query+must+be+at+least+10+characters+long");
			else{
				//	post question
				$ret = $database->post_quest($course_code, $user, $q_type, $quest, $opt1, $opt2, $opt3, $opt4, $date, $time);
				if($ret == 0)
					header("Location: ../ask.php?t=1&msg=Unable+to+submit+your+query");
			}
		}
		else if($q_type == "poll"){
			if($ret1 == 0)
				header("Location: ../poll.php?t=1&msg=Your+query+must+be+at+least+10+characters+long");
			else if($ret2 == 0)
				header("Location: ../poll.php?t=1&msg=option+1+cannot+be+empty");
			else if($ret3 == 0)
				header("Location: ../poll.php?t=1&msg=option+2+cannot+be+empty");
			else if($ret4 == 0)
				header("Location: ../poll.php?t=1&msg=option+3+cannot+be+empty");
			else if($ret5 == 0)
				header("Location: ../poll.php?t=1&msg=option+4+cannot+be+empty");
			else{
				//	post poll question
				$ret = $database->post_quest($course_code, $user, $q_type, $quest, $opt1, $opt2, $opt3, $opt4, $date, $time);
				if($ret == 0)
					header("Location: ../ask.php?t=1&msg=Unable+to+submit+your+query");
			}
		}
		if($ret == 1){
			unset($_SESSION['quest']);
			unset($_SESSION['opt1']);
			unset($_SESSION['opt2']);
			unset($_SESSION['opt3']);
			unset($_SESSION['opt4']);
			header("Location: ../wall.php?t=1");
		}
	}

//	get LIVE WALL posts and store in html_cache session variable
	function get_wall(){
		global $database;

		$date = $this->get_Date_Time("date");
		$_SESSION['html_cache'] = $database->wall($_SESSION['course_code'],$date);
		header("Location: ../wall.php?t=1&wall=1");
	}

//	get answer page with comments if any and store in html_cache session variable
	function answer_page($q_id, $q_type, $question, $btn_value, $opt1, $opt2, $opt3, $opt4){
		global $database;

		$_SESSION['q_id'] = $q_id;
		$_SESSION['question'] = $question;
		$_SESSION['q_type'] = $q_type;
		$_SESSION['opt1'] = $opt1;
		$_SESSION['opt2'] = $opt2;
		$_SESSION['opt3'] = $opt3;
		$_SESSION['opt4'] = $opt4;
		$_SESSION['btn_value'] = $btn_value;
		$_SESSION['html_cache'] = $database->create_answer_page($q_id);
		header("Location: ../answer.php?t=1&ans=1");
	}

//	post answer and store in database
	function post_answer($user,$q_id,$answer){
		global $database, $form;

		$user = $form->input($user);
		$q_id = $form->input($q_id);
		$answer = $form->input($answer);

		$date = $this->get_Date_Time("date");
		$time = $this->get_Date_Time("time");
		$ret = $database->answerIt($user,$q_id,$answer,$date,$time);
		if($ret == 1){
			$this->answer_page($_SESSION['q_id'], $_SESSION['q_type'], $_SESSION['question'], $_SESSION['btn_value'],$_SESSION['opt1'],$_SESSION['opt2'],$_SESSION['opt3'],$_SESSION['opt4']);
		}
		else
			header("Location: ../answer.php?t=1&msg=Unable+to+post+your+answer");
	}

//	upload images/files
	function upload($file,$path,$page,$ass_name,$ass_id,$old_ass_name){
		global $database;

		$date = $this->get_Date_Time("date");
		$time = $this->get_Date_Time("time");

		if($page == "assign.php"){
			$dir = "uploads/".$path."/".$_SESSION['course_code']."/".$ass_name."/";
			@mkdir("../uploads/".$path."/".$_SESSION['course_code']."/".$ass_name,0700,true);
			$target_file = $dir . $_SESSION['user'] . "_" . basename($file['name']);
		}
		else if($page == "notes.php"){
			$dir = "uploads/".$path."/".$_SESSION['course_code']."/";
			@mkdir("../uploads/".$path."/".$_SESSION['course_code'],0700,true);
			$target_file = $dir . basename($file['name']);
		}
		else{
			$dir = "uploads/".$path."/".$date."/".$_SESSION['course_code']."/";
			@mkdir("../uploads/".$path."/".$date."/".$_SESSION['course_code'],0700,true);
			$target_file = $dir . basename($file['name']);
		}
		$file_size = filesize($file['tmp_name']);
		$file_details = getimagesize($file['tmp_name']);
		$file_type = pathinfo($target_file, PATHINFO_EXTENSION);

		if(file_exists("../".$target_file)){
			header("Location: ../$page?t=1&$path=1&msg=File+already+exists");
			return 1;
		}
		if($path == "images"){
			$size_limit = 2000000;
			if($file_details != true){
				header("Location: ../$page?t=1&msg=File+is+not+an+image");
				return 1;
			}
			if($file_type != "png" && $file_type != "jpg" && $file_type != "jpeg" && $filetype != "gif" && $file_type != "bmp"){
				header("Location: ../$page?t=1&msg=Only+JPG,+JPEG,+PNG+GIF+&+BMP images are allowed");
				return 1;
			}
		}
		else
			$size_limit = 50000000;

		if($file['size'] > $size_limit){
			header("Location: ../$page?t=1&$path=1&msg=File+is+too+large");
			return 1;
		}

		if($path == "assign")
			$this->delete(TBL_ASSIGN_SOLUTIONS, "ass_id", $ass_id, "assign.php", "Failed+to+upload+Assignment", "1", $old_ass_name);
		//	uploads file from destination to target
		if(move_uploaded_file($file['tmp_name'], "../".$target_file)){
			$ret = $database->add_file($path, $_SESSION['course_code'], $_SESSION['user'], $file['name'], $file_type, $target_file, $ass_id, $date, $time);
			if($ret == 1){
				if($path == "images")
					header("Location: ../wall.php?t=1");
				else if($path == "notes")
					header("Location: ../notes.php?t=1&notes=1&msg=Notes+uploaded+successfully");
				else if($path == "assign")
					header("Location: ../assign.php?t=1&assign=1&msg=Assignment+submitted+successfully");
			}
			else
				header("Location: ../$page?t=1&$path=1&msg=Failed+to+store+file+location");
		}
		else
			header("Location: ../$page?t=1&$path=1&msg=Error+uploading+file");
		//header("Content-Type: image/png");
		//echo $img_content."<br/>";
	}

//	get all notes of course and store in html_cache session variable
	function notes(){
		global $database;

		$_SESSION['html_cache'] = $database->get_notes($_SESSION['course_code']);
		header("Location: ../notes.php?t=1&notes=1");
	}

//	get profile info and store in html_cache session variable
	function answer_assign(){
		global $database;

		$_SESSION['html_cache'] = $database->submit_assign($_SESSION['course_code'],$_SESSION['user']);
		header("Location: ../assign.php?t=1&assign=1&msg=Assignment+sumbitted+successfully");
	}

//	get assignment page, submit assignment and store in html_cache session variable
	function get_assign(){
		global $database;

		$date = $this->get_Date_Time("date");
		$time = $this->get_Date_Time("time");
		$_SESSION['html_cache'] = $database->get_assign($_SESSION['course_code'],$_SESSION['user'],$date,$time);
		header("Location: ../assign.php?t=1&assign=1");
	}

//	teacher can create new assignment and store in html_cache session variable
	function add_assign($ass_name,$course,$quest,$date,$time){
		global $database;

		$_SESSION['html_cache'] = $database->create_assign($ass_name,$course, $quest, $date, $time);
		header("Location: ../assign.php?t=1&assign=1&msg=Assignment+added+successfully");
	}

//	teacher can hide/unhide assignment from student
	function toggle_assign($ass_id,$status){
		global $database;

		$ret = $database->hide_unhide_assign($ass_id,$status);
		if($ret == 1)
			header("Location: ../assign.php?t=1&assign=0&msg=Assignment+Hidden");
		else
			header("Location: ../assign.php?t=1&assign=0&msg=Error+Hiding+Assignment");
	}

//	teacher can view assignments submitted by students
	function view_assign($ass_id){
		global $database;

		$_SESSION['html_cache'] = $database->view_all_assign($ass_id);
		header("Location: ../assign.php?t=1&assign=1&all=1");
	}

//	date time function to convet date and time from seconds to required format
	function get_Date_Time($type,$date=NULL){
		if($date == NULL)
			$date = getdate(date("U")+12600);
		if($type == "date"){
			if(strlen($date['mday']) == 1)
				$day = "0".$date['mday'];
			else
				$day = $date['mday'];
			if(strlen($date['mon']) == 1)
				$month = "0".$date['mon'];
			else
				$month = $date['mon'];
		return $date['year']."-".$month."-".$day;
		}
		else{
			if(strlen($date['hours']) == 1)
				$hour = "0".$date['hours'];
			else
				$hour = $date['hours'];
			if(strlen($date['minutes']) == 1)
				$minute = "0".$date['minutes'];
			else
				$minute = $date['minutes'];
			if(strlen($date['seconds']) == 1)
				$second = "0".$date['seconds'];
			else
				$second = $date['seconds'];
		return $hour.":".$minute.":".$second;//.".000000";
		}
	}

//	download a image file on download button click
	function download($q){
		$file = "http://".$_SERVER['HTTP_HOST']."/eClassroom/".$q;
		if(file_exists("../".$q)){
			ob_clean();
			header('Content-Type: image/jpeg');
			header('Content-Disposition: attachment; filename='.basename($q));
			header('Content-Length: ' . filesize("../".$q));
			//$handle = fopen("../".$q, 'rb');
			//print fread($handle, filesize("../".$q));
			//fclose($handle);
			readfile("../".$q,true);
		}
	}

//	delete file and database link to file (row)
	function delete($db, $id, $id_val, $page, $msg, $isFile, $file_loc){
		global $database;

		if($isFile == "1"){
			if($page != "assign.php")
				$ret = $database->delete_record($db, $id, $id_val);
			unlink("../".$file_loc);
		}
		
		if($ret == 1){
			if($page == "assign.php" && $file_loc != "0")
				return 1;
			header("Location: ../$page?t=1&msg=Deleted+successfully");
		}
		else
			header("Location: ../$page?t=1&msg=$msg");
	}
};

//	instantiate process class
$process = new process();

?>