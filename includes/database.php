<?
	include_once($_SESSION['root']."/eclass/includes/constants.php");

class MySqlDB{
	var $connection;
//	Connection object
	function MySqlDB(){
		$this->connection = mysql_connect(HOST,USER,PASS) or die(mysql_error());
		mysql_select_db(DB,$this->connection) or die(mysql_error());
	}

//	Shows the time left of the live class
	function time_left($type,$course,$ass_id,$date,$time){
		if($type == "class")
			$q = "SELECT * FROM ".TBL_LIVE." WHERE course_code='$course'";	//	query for fetching all live lectures
		else if($type == "assign")
			$q = "SELECT * FROM ".TBL_ASSIGNMENTS." WHERE ass_id='$ass_id'";	//	query for fetching all assignments of class
		$result = mysql_query($q, $this->connection);
		if($result){
			$tbl_date = mysql_result($result, 0, "date");
			$tbl_time = mysql_result($result, 0, "time");
			$duration = mysql_result($result, 0, "duration");
			$duration *= 3600;
		//	query for selecting current time and time from table
			$q = "SELECT UNIX_TIMESTAMP('$date $time') AS d1, UNIX_TIMESTAMP('$tbl_date $tbl_time') AS d2";	
			$result = mysql_query($q, $this->connection);
			$datetime = mysql_result($result, 0, "d1");
			$course_datetime = mysql_result($result, 0, "d2");

			if($type != "assign")
				$_SESSION['duration'] = (($course_datetime+$duration) - $datetime);
			if($datetime > ($course_datetime+$duration-1)){
				if($type == "class")
					$this->end_class($course);	//	end the class if duration exceeds
				else
					return 0;
			}
		}
	return 1;
	}

//	get user level
	function get_level($user){
		$q = "SELECT * FROM ".TBL_USERS." WHERE u_id='$user'";
		$result = mysql_query($q, $this->connection);
		return mysql_result($result, 0, "level");
	}

//	Admin can change level of user
	function modify_user($user,$level){
		$q = "UPDATE ".TBL_USERS." SET level='$level' WHERE u_id='$user'";	//	query to change user level
		$result = mysql_query($q, $this->connection);
		if($result)
			return 1;
		else
			return 0;
	}

//	Check if the login credentials are valid or not and performs login
	function check_login($email,$pass,$remember){
		$q = "SELECT * FROM ".TBL_USERS." WHERE email='$email'";	//	query to checking if user exists
		$result = mysql_query($q,$this->connection);
		if(mysql_numrows($result) < 1)
			return "email";
		else{
			$q = "SELECT * FROM ".TBL_USERS." WHERE email='$email' and password='$pass'";	//	query to checking if user entered correct password
			$result = mysql_query($q,$this->connection);
			if(mysql_numrows($result) < 1)
				return "pass";
			else{
				$_SESSION['user'] = mysql_result($result, 0, "u_id");
				$_SESSION['level'] = mysql_result($result, 0, "level");
				if($remember == 1){
					setcookie('user',$_COOKIE['PHPSESSID'],time()+86400,'/');	//	set the cookie if user selected remember me option
				}
				if($_SESSION['level'] == "2"){
					$_SESSION['html_cache'] = $this->fetch_records(1, "1");
					return "admin";
				}
				return 1;
			}
		}
	}

//	Add a new user to the database
	function add_user($fname,$lname,$email,$pass,$address,$year,$date,$time){
		$ret = $this->check_login($email,$pass);	//	check if no user with same email exists, if doesnt exists then returns "email"
		if($ret == "email"){
			$q = "INSERT INTO ".TBL_USERS." (first_name,last_name,email,password,address,year,date,time) VALUES ('$fname','$lname','$email','$pass','$address','$year','$date','$time')";
			$result = mysql_query($q,$this->connection);
			if($result == 1)
				return 1;
			else
				return 0;
		}
		else
			return "email";
	}

//	generate profile page with info from database and return in a string
	function create_profile_page($user){
		$q = "SELECT * FROM ".TBL_USERS." WHERE u_id='$user'";
		$result = mysql_query($q, $this->connection);
		if(mysql_numrows($result) > 0){
			$fname = mysql_result($result, 0, "first_name");
			$lname = mysql_result($result, 0, "last_name");
			$email = mysql_result($result, 0, "email");
			$address = mysql_result($result, 0, "address");
			$year = mysql_result($result, 0, "year");
			if($year == "A") $y0 ="checked";
			else if($year == 1) $y1 ="checked";
			else if($year == 2) $y2 ="checked";
			else if($year == 3) $y3 ="checked";
			$string.= "<div class=\"row form-group\"><span class=\"col-xs-0 col-md-1\"></span><label class=\"col-xs-4 col-md-2\">First Name: </label><input class=\"col-xs-7 col-md-7\" type=\"text\" name=\"fname\" value=\"".$fname."\"/></div>";
			$string.= "<div class=\"row form-group\"><span class=\"col-xs-0 col-md-1\"></span><label class=\"col-xs-4 col-md-2\">Last Name: </label><input class=\"col-xs-7 col-md-7\" type=\"text\" name=\"lname\" value=\"".$lname."\"/></div>";
			$string.= "<div class=\"row form-group\"><span class=\"col-xs-0 col-md-1\"></span><label class=\"col-xs-4 col-md-2\">Email: </label><input class=\"col-xs-7 col-md-7\" type=\"text\" name=\"email\" value=\"".$email."\" disabled=\"disabled\"/></div>";
			$string.= "<div class=\"row form-group\"><span class=\"col-xs-0 col-md-1\"></span><label class=\"col-xs-4 col-md-2\">Address: </label><input class=\"col-xs-7 col-md-7\" type=\"text\" name=\"address\" value=\"".$address."\"/></div>";
			$string.= "<div class=\"row form-group\"><span class=\"col-xs-0 col-md-1\"></span><label class=\"col-xs-4 col-md-2\">Year: </label>";
			if($_SESSION['level'] == 1)
				$string.= "<span class=\"col-xs-2 col-md-2\"><input type=\"radio\" name=\"year\" value=\"A\" ".$y0." />Admin</span>";
			$string.= "<span class=\"col-xs-1 col-md-1\"><input type=\"radio\" name=\"year\" value=\"1\" ".$y1." />1<sup>st</sup> Year</span>";
			$string.= "<span class=\"col-xs-1 col-md-1\"><input type=\"radio\" name=\"year\" value=\"2\" ".$y2." />2<sup>nd</sup> Year</span>";
			$string.= "<span class=\"col-xs-1 col-md-1\"><input type=\"radio\" name=\"year\" value=\"3\" ".$y3." />3<sup>rd</sup> Year</span>";
			$string.= "</div>";
		return $string;
		}
	}
	
//	update user profile
	function update_user($user,$fname,$lname,$address,$year){
		$q = "UPDATE ".TBL_USERS." SET first_name='$fname', last_name='$lname', address='$address', year='$year' WHERE u_id='$user'";
		$result = mysql_query($q, $this->connection);
		if($result)
			return 1;
		else
			return 0;
	}

//	courses on year select / users on 2nd parameter as 1 and return HTML in string
	function fetch_records($year, $user="0"){
		if($user == "1")
			$q = "SELECT * FROM ".TBL_USERS;
		else
			$q = "SELECT * FROM ".TBL_COURSES." WHERE year='$year'";
		$result = mysql_query($q, $this->connection);
		if(mysql_numrows($result) > 0){
			$rows = mysql_numrows($result);
			for($i=0; $i<$rows; $i++){
				if($user == 1){
					$u_id = mysql_result($result, $i, "u_id");
					$email = mysql_result($result, $i, "email");
					$temp = "<option onclick=\"set_user(this)\" value=\"".$u_id."\">".$email."</option>";
				}
				else{
					$course_code = mysql_result($result, $i, "course_code");
					$course_name = mysql_result($result, $i, "course_name");
					$temp = "<option value=\"".$course_code."\">".$course_name."</option>";
				}
				$string.=$temp;
			}
		return $string;
		}
	}

//	build class (create live lecture)
	function build_class($course, $date, $time, $duration){
		$q = "SELECT * FROM ".TBL_LIVE." WHERE course_code='$course'";
		$result = mysql_query($q, $this->connection);
		if(mysql_numrows($result) == 0){
			$q = "INSERT INTO ".TBL_LIVE." (course_code,date,time,duration) VALUES ('$course','$date','$time','$duration')";
			$result = mysql_query($q,$this->connection);
			if($result == 1)
				return 1;
		}
		return 0;
	}

//	end the live lecture
	function end_class($course){
		$q = "DELETE FROM ".TBL_LIVE." WHERE course_code='$course'";
		$result = mysql_query($q,$this->connection);
		
		$q = "DELETE FROM ".TBL_CLASS_STUDENTS." WHERE course_code='$course'";
		$result = mysql_query($q,$this->connection);
		if($result == 1){
			if($_SESSION['course_code'] == $course){
				unset($_SESSION['course_code']);
				unset($_SESSION['course_name']);
				unset($_SESSION['course_select']);
			}
			return 1;
		}
		else
			return 0;
	}

//	get current live courses and return HTML in string
	function live($date,$time){
		$q = "SELECT * FROM ".TBL_LIVE.",".TBL_COURSES." WHERE ".TBL_LIVE.".course_code=".TBL_COURSES.".course_code";
		$result = mysql_query($q, $this->connection);
		if($result){
			$rows = mysql_numrows($result);
			for($i=0; $i<$rows; $i++){
				$course_code = mysql_result($result, $i, "course_code");
				$this->time_left("class",$course_code,0,$date,$time);
			}
			$result = mysql_query($q, $this->connection);
			$rows = mysql_numrows($result);
			for($i=0; $i<$rows; $i++){
				$course_code = mysql_result($result, $i, "course_code");
				$course_name = mysql_result($result, $i, "course_name");
				$temp = "<option value=\"".$course_code."\">".$course_name."</option>";
				@$string.=$temp;
			}
		return @$string;
		}
	}

//	join the course from live courses
	function join_class($course){
		$user = $_SESSION['user'];
		$q = "SELECT * FROM ".TBL_COURSES." WHERE course_code='$course'";
		$result = mysql_query($q,$this->connection);
		$course_name = mysql_result($result, 0, "course_name");
		if($result){
			$q = "DELETE FROM ".TBL_CLASS_STUDENTS." WHERE u_id='$user'";	//	query to remove the user from live class if already present
			$result = mysql_query($q,$this->connection);

			$q = "INSERT INTO ".TBL_CLASS_STUDENTS." (u_id,course_code) VALUES ('$user','$course')";	//	query to add the same user from live class
			$result = mysql_query($q,$this->connection);
			if($result == 1){
				$_SESSION['course_code'] = $course;
				$_SESSION['course_name'] = $course_name;
				return 1;
			}
			else
				return 0;
		}
	}

//	mark user present when user joins a course
	function mark_attend($user, $course, $date, $time){
		$q = "SELECT * FROM ".TBL_ATTENDANCE." WHERE u_id='$user' AND course_code='$course' AND date='$date'";
		$result = mysql_query($q,$this->connection);
		if(mysql_numrows($result) == 0){
			$q = "INSERT INTO ".TBL_ATTENDANCE." (course_code,u_id,date,time) VALUES ('$course','$user','$date','$time')";
			$result = mysql_query($q,$this->connection);
		}
	}

//	allows teacher to view attendance for perticular lecture sorted with course and date
	function view_attend($course, $date){
		$q = "SELECT * FROM ".TBL_ATTENDANCE." WHERE course_code='$course' AND date='$date'";
		$result = mysql_query($q,$this->connection);
		if(mysql_numrows($result) > 0){
			$rows = mysql_numrows($result);
			for($i=0; $i<$rows; $i++){
				$u_id = mysql_result($result, $i, "u_id");
					$q = "SELECT * FROM ".TBL_USERS." WHERE u_id='$u_id'";
					$result2 = mysql_query($q,$this->connection);
						$fname = mysql_result($result2, 0, "first_name");
						$lname = mysql_result($result2, 0, "last_name");
				$temp = "<tr><td>".$u_id."</td><td>".$fname."</td><td>".$lname."</td><td> P </td></tr>";
				$string.=$temp;
			}
		return $string;
		}
	}

//	post query question on LIVE WALL
	function post_quest($course_code, $user, $q_type, $quest, $opt1, $opt2, $opt3, $opt4, $date, $time){
		$q = "INSERT INTO ".TBL_WALL." (course_code,u_id,q_type,question,opt1,opt2,opt3,opt4,date,time) VALUES ('$course_code','$user','$q_type','$quest','$opt1','$opt2','$opt3','$opt4','$date','$time')";
		$result = mysql_query($q,$this->connection);
		if($result == 1)
			return 1;
		else
			return 0;
	}

//	generate LIVE WALL page and return HTML in string
	function wall($course_code,$date){
		$q = "SELECT * FROM ".TBL_WALL." WHERE course_code='$course_code' AND date='$date' ORDER BY date,time DESC";
		$result = mysql_query($q,$this->connection);
		if(mysql_numrows($result) > 0){
			$rows = mysql_numrows($result);
			for($i=0; $i<$rows; $i++){
				$q_id = mysql_result($result, $i, "q_id");
				$q_type = mysql_result($result, $i, "q_type");
				$question = mysql_result($result, $i, "question");
				$opt1 = mysql_result($result, $i, "opt1");
				$opt2 = mysql_result($result, $i, "opt2");
				$opt3 = mysql_result($result, $i, "opt3");
				$opt4 = mysql_result($result, $i, "opt4");
				if($q_type == "ask")
					$btn_value = "Answer Quest";
				else if($q_type == "poll")
					$btn_value = "Answer Poll";
				else if($q_type == "image")
					$btn_value = "View Image";
				$string.="<form method=\"post\" action=\"includes/process.php\" class=\"row post\" style=\"margin: 5px 0;\" >";
				$string.=	"<div class=\"col-xs-8 col-md-10 l-post\">";
				$string.=		"<div class=\"col-xs-1 col-md-1\">".($i+1).".</div>";
				if($q_type == "image"){
					$string.=	"<div class=\"center\">";
					$string.= 	"<img class=\"wall-img\" src=\"".$question."\"/>";
					$string.= "</div>";
				}
				else
					$string.=	"<div class=\"col-xs-11 col-md-11\">".$question."</div>";
				$string.="</div><div class=\"col-xs-4 col-md-2 r-post center\">";
				$string.=		"<input type=\"hidden\" name=\"q_type\" value=\"".$q_type."\"/>";
				$string.=		"<input type=\"hidden\" name=\"question\" value=\"".$question."\"/>";
				if($q_type == "poll"){
					$string.= "<input type=\"hidden\" name=\"opt1\" value=\"".$opt1."\"/>";
					$string.= "<input type=\"hidden\" name=\"opt2\" value=\"".$opt2."\"/>";
					$string.= "<input type=\"hidden\" name=\"opt3\" value=\"".$opt3."\"/>";
					$string.= "<input type=\"hidden\" name=\"opt4\" value=\"".$opt4."\"/>";
				}
				$string.=		"<input type=\"hidden\" name=\"btn_value\" value=\"".$btn_value."\"/>";
				$string.=		"<button type=\"submit\" name=\"q_id\" value=\"".$q_id."\" class=\"btn btn-primary\">".$btn_value."</button>";
				$string.="</div></form>";
			}
		return $string;
		}
		else{
			return "<label class=\"font-big col-xs-12 col-md-12 pad-top-4 pad-bottom-2 center\">No posts on this wall yet!</label>";
		}
	}

//	generate answer query page and return HTML in string
	function create_answer_page($q_id){
		$q = "SELECT * FROM ".TBL_SOLUTIONS." WHERE q_id='$q_id' ORDER BY date,time DESC";
		$result = mysql_query($q,$this->connection);
		if(mysql_numrows($result) > 0){
			$user = $_SESSION['user'];
			$q = "SELECT * FROM ".TBL_SOLUTIONS." WHERE q_id='$q_id' AND u_id='$user'";
			$result2 = mysql_query($q,$this->connection);
			if(mysql_numrows($result2) > 0)
				$_SESSION['answered'] = 1;
			else
				$_SESSION['answered'] = 0;

			$rows = mysql_numrows($result);
			for($i=0; $i<$rows; $i++){
				$u_id = mysql_result($result, $i, "u_id");
					$q = "SELECT * FROM ".TBL_USERS." WHERE u_id='$u_id'";
					$result2 = mysql_query($q,$this->connection);
						$fname = mysql_result($result2, 0, "first_name");
						$lname = mysql_result($result2, 0, "last_name");
				$answer = mysql_result($result, $i, "answer");
				$date = mysql_result($result, $i, "date");
				$time = mysql_result($result, $i, "time");
				$string.="<div class=\"col-xs-12 col-md-12 pad-bottom-1\">";
				if((($i+1) % 2) != 0){
					$string.="<div class=\"ans-left left\" style=\"width: 70%;\">";
					$string.="<div class=\"pad-5 comment-box\"><span class=\"left\">".$fname." ".$lname."</br>".$date."</br>".$time."</span></div>";
					$string.="<div class=\"l_box\"><span class=\"left\">".$answer."</span></div>";
				}
				else{
					$string.="<div class=\"ans-right right\" style=\"width: 70%;\">";
					$string.="<div class=\"pad-5 comment-box\"><span class=\"right\">".$fname." ".$lname."</br>".$date."</br>".$time."</span></div>";
					$string.="<div class=\"l_box\"><span class=\"right\">".$answer."</span></div>";
					}
				$string.="</div></div>";
			}
		return $string;
		}
	}

//	answer post query question posted on LIVE WALL
	function answerIt($user,$q_id,$answer,$date,$time){
		$q = "INSERT INTO ".TBL_SOLUTIONS." (u_id,q_id,answer,date,time) VALUES ('$user','$q_id','$answer','$date','$time')";
		$result = mysql_query($q,$this->connection);
		if($result == 1)
			return 1;
		else
			return 0;
	}

//	file uploading for (images on LIVE WALL), (documents on NOTES PAGE) and (files on ASSIGNMENT PAGE)
	function add_file($path, $course, $user, $name, $type, $location, $ass_id, $date, $time){
		if($path == "images")
			$q = "INSERT INTO ".TBL_WALL." (course_code,u_id,q_type,question,date,time) VALUES ('$course','$user','image','$location','$date','$time')";
		else if($path == "notes")
			$q = "INSERT INTO ".TBL_NOTES." (course_code,note_name,note_type,note_location) VALUES ('$course','$name','$type','$location')";
		else if($path == "assign"){
			$q = "DELETE FROM ".TBL_ASSIGN_SOLUTIONS." WHERE u_id='$user' AND ass_id='$ass_id'";
			$result = mysql_query($q,$this->connection);

			$q = "INSERT INTO ".TBL_ASSIGN_SOLUTIONS." (u_id,ass_id,location,date,time) VALUES ('$user','$ass_id','$location','$date','$time')";
		}
		$result = mysql_query($q,$this->connection);
		if($result == 1)
			return 1;
		else
			return 0;
	}

//	generate Notes page and return HTML in string
	function get_notes($course){
		$q = "SELECT * FROM ".TBL_NOTES." WHERE course_code='$course'";
		$result = mysql_query($q,$this->connection);
		$rows = mysql_numrows($result);
		if($rows > 0){
			for($i=0; $i<$rows; $i++){
				$note_id = mysql_result($result, $i, "note_id");
				$note_name = mysql_result($result, $i, "note_name");
				$note_type = mysql_result($result, $i, "note_type");
				$note_location = mysql_result($result, $i, "note_location");
				
				$string.="<div class=\"row post\" style=\"margin: 5px 0;\">";
				$string.=	"<div class=\"col-xs-9 col-sm-9 col-md-9 l-post\">";
				$string.=		"<div class=\"col-xs-offset-1 col-xs-2 col-sm-1 col-md-1\">".($i+1).".</div>";
				
					$type_img = $this->get_note_img($note_type);

				$string.=		"<div class=\"col-xs-8 col-sm-2 col-md-1\"><img class=\"note-img\" src=\"".$type_img."\"/></div>";
				$string.=		"<div class=\"col-xs-offset-1 col-xs-11 col-sm-9 col-md-10 note-font\">".$note_name."</div>";
				$string.=	"</div><div class=\"col-xs-3 col-sm-3 col-md-3 r-post center\">";
				$string.=		"<a href=\"".$note_location."\"><button name=\"q_id\" value=\"".$q_id."\" class=\"btn btn-primary\">Download</button></a>";
				if($_SESSION['level'] == 1){
					$string.=	"<form method=\"post\" action=\"includes/process.php\" style=\"padding-top: 0.2em;\"/>";
					$string.=		"<button type=\"submit\" name=\"note_id\" value=\"".$note_id."\" class=\"btn btn-danger\">Delete</button>";
					$string.=		"<input type=\"hidden\" name=\"note_location\" value=\"".$note_location."\"/>";
					$string.=	"</form>";
				}
				$string.="</div></div>";
			}
		return $string;
		}
		else{
			return "<label class=\"font-big col-xs-12 col-md-12 pad-top-4 pad-bottom-2 center\">Notes not found for this Course!</label>";
		}
	}

//	returns image source based on file type
	function get_note_img($note_type){
		if($note_type == "pdf")
			$type_img = "images/notes/pdf.png";
		else if($note_type == "doc" || $note_type == "docx")
			$type_img = "images/notes/doc.png";
		else if($note_type == "ppt" || $note_type == "pptx")
			$type_img = "images/notes/ppt.png";
		else if($note_type == "xls" || $note_type == "xlsx")
			$type_img = "images/notes/xls.png";
		else if($note_type == "zip")
			$type_img = "images/notes/zip.png";
		else if($note_type == "exe" || $note_type == "msi")
			$type_img = "images/notes/exe.png";
		else if($note_type == "rar")
			$type_img = "images/notes/rar.png";
		else if($note_type == "jar" || $note_type == "java")
			$type_img = "images/notes/java.png";
		else if($note_type == "txt")
			$type_img = "images/notes/txt.png";
		else if($note_type == "html")
			$type_img = "images/notes/html.png";
		else if($note_type == "js")
			$type_img = "images/notes/js.png";
		else if($note_type == "css")
			$type_img = "images/notes/css.png";
		else if($note_type == "jpg" || $note_type == "jpeg" || $note_type == "png" || $note_type == "gif" || $note_type == "bmp")
			$type_img = "images/notes/image.png";
		else if($note_type == "mp4" || $note_type == "mpg" || $note_type == "avi" || $note_type == "mov" || $note_type == "wmv" || $note_type == "vob" || $note_type == "3gp" || $note_type == "flv")
			$type_img = "images/notes/video.png";
		else if($note_type == "mp3" || $note_type == "wav" || $note_type == "wma")
			$type_img = "images/notes/audio.png";
		else
			$type_img = "images/notes/other.png";

		return $type_img;
	}

//	generate Assignment page and return HTML in string
	function get_assign($course,$user,$cur_date,$cur_time){
		$q = "SELECT * FROM ".TBL_ASSIGNMENTS." WHERE course_code='$course' ORDER BY ass_id DESC";
		$result = mysql_query($q,$this->connection);
		$rows = mysql_numrows($result);
		if($rows > 0){
			for($i=0; $i<$rows; $i++){
				$ass_id = mysql_result($result, $i, "ass_id");
				$ass_name = mysql_result($result, $i, "ass_name");
				$question = mysql_result($result, $i, "question");
				$date = mysql_result($result, $i, "date");
				$time = mysql_result($result, $i, "time");
				$hide = mysql_result($result, $i, "hide");

				$q = "SELECT * FROM ".TBL_ASSIGN_SOLUTIONS." WHERE ass_id='$ass_id' AND u_id='$user'";
				$result2 = mysql_query($q,$this->connection);
				$rows2 = mysql_numrows($result2);
				
				if($hide == "0" || $_SESSION['level'] == 1){
					$string.="<div class=\"row post\" style=\"margin: 5px 0;\">";
					if($_SESSION['level'] == 1)
						$string.=	"<div class=\"col-xs-10 col-sm-11 col-md-11 l-post\"><div class=\"col-xs-12 col-sm-12 col-md-12\">";
					else
						$string.=	"<div class=\"col-md-12\"><div class=\"col-xs-12 col-sm-12 col-md-12\">";
					$string.=		"<div class=\"col-xs-2 col-sm-1 col-md-1 right note-font\">".($i+1).".</div>";
					$string.=		"<div class=\"col-xs-10 col-sm-11 col-md-11 bold\">".$ass_name."</div></div>";
					$string.=		"<div class=\"col-xs-offset-1 col-xs-10 col-sm-11 col-md-11\">".$question."</div>";

					if($rows2 > 0){
						$location = mysql_result($result2, 0, "location");
						$date2 = mysql_result($result2, 0, "date");
						$time2 = mysql_result($result2, 0, "time");
						$string.=	"<div class=\"col-xs-12 col-sm-12 col-md-12 pad-top-1 center\">";
						$string.=	"<a href=\"".$location."\"><button class=\"btn btn-primary\">".basename($location)."</button></a> <span style=\"font-style: italic\"><span class=\"bold\">Uploaded on: </span> ".$date2." ".$time2." </span></div>";
					}
					if($this->time_left("assign",$_SESSION['course_code'],$ass_id,$cur_date,$cur_time)){
						$string.=	"<form method=\"post\" class=\"center\" action=\"includes/process.php\" enctype=\"multipart/form-data\">";
						$string.=	"<div class=\"col-xs-offset-1 col-sm-offset-3 col-md-offset-4 col-xs-11 col-sm-9 col-md-8 pad-top-1\"><input type=\"file\" name=\"assign\"/></div>";
						$string.=	"<input type=\"hidden\" name=\"ass_id\" value=\"".$ass_id."\" />";
						$string.=	"<input type=\"hidden\" name=\"ass_name\" value=\"".$ass_name."\" />";
						$string.=	"<input type=\"hidden\" name=\"old_ass_name\" value=\"".$location."\" />";
						$string.=	"<div class=\"col-xs-12 col-sm-12 col-md-12 pad-top-1\">";
						$string.=	"<button name=\"answer_assign_btn\" value=\"upload\" type=\"submit\" class=\"btn btn-success\" style=\"width: 10em;\">Submit Assignment</button></div></form>";
					}
					$string.=		"<div class=\"col-xs-12 col-sm-12 col-md-12 right\" style=\"color: red;\">Due Date: ".$date." ".$time."</div>";
					$string.=	"</div>";
					if($_SESSION['level'] == 1){
						$string.=	"<div class=\"col-xs-2 col-sm-1 col-md-1 pad-top-2\"><form method=\"post\" action=\"includes/process.php\">";
						$string.=		"<input type=\"hidden\" name=\"ass_id\" value=\"".$ass_id."\" />";
						if($hide == 0)
							$string.=		"<button name=\"hide_assign_btn\" value=\"hide\" type=\"submit\" class=\"btn btn-danger\" style=\"width: 5em;\">Hide</button>";
						else
							$string.=		"<button name=\"unhide_assign_btn\" value=\"unhide\" type=\"submit\" class=\"btn btn-danger\" style=\"width: 5em;\">UnHide</button>";
						$string.=		"<button name=\"view_assign\" value=\"show\" type=\"submit\" class=\"btn btn-info\" style=\"width: 5em; margin-top: 5px;\">View</button>";
						$string.=	"</form></div>";
					}
					$string.=	"</div>";
				}
			}
		return $string;
		}
		else{
			return "<label class=\"font-big col-xs-12 col-md-12 pad-top-4 pad-bottom-2 center\">Assignments not found for this Course!</label>";
		}
	}

//	create new assignment in database
	function create_assign($ass_name,$course, $quest, $date, $time){
		$q = "INSERT INTO ".TBL_ASSIGNMENTS." (ass_name,course_code,question,date,time) VALUES ('$ass_name','$course','$quest','$date','$time')";
		$result = mysql_query($q,$this->connection);
		if($result == 1)
			return 1;
		else
			return 0;
	}

//	hide or unhide assignment
	function hide_unhide_assign($ass_id,$status){
		$q = "UPDATE ".TBL_ASSIGNMENTS." SET hide='$status' WHERE ass_id='$ass_id'";
		$result = mysql_query($q, $this->connection);
		if($result == 1)
			return 1;
		else
			return 0;
	}

//	teacher can view all assignments submitted by student
	function view_all_assign($ass_id){
		$q = "SELECT * FROM ".TBL_ASSIGN_SOLUTIONS." WHERE ass_id='$ass_id' ORDER BY date,time ASC";
		$result = mysql_query($q,$this->connection);
		$rows = mysql_numrows($result);
		if($rows > 0){
			$q = "SELECT ass_id,ass_name FROM ".TBL_ASSIGNMENTS." WHERE ass_id='$ass_id'";
			$result2 = mysql_query($q,$this->connection);
			$ass_name = mysql_result($result2, 0, "ass_name");

			$string.=	"<div class=\"col-xs-12 col-sm-12 col-md-12 center post\">";
			$string.=	"<div class=\"col-xs-12 col-sm-12 col-md-12 bold font-big\">".$ass_name."</div>";
			$string.=	"<div class=\"col-xs-4 col-sm-4 col-md-4 assign bold\">Student Name</div>";
			$string.=	"<div class=\"col-xs-4 col-sm-4 col-md-4 assign bold\">File</div>";
			$string.=	"<div class=\"col-xs-4 col-sm-4 col-md-4 assign bold\">Uploaded On</div></div>";

			for($i=0; $i<$rows; $i++){
				$user = mysql_result($result, $i, "u_id");
				$location = mysql_result($result, $i, "location");
				$date = mysql_result($result, $i, "date");
				$time = mysql_result($result, $i, "time");

				$q = "SELECT u_id,first_name,last_name FROM ".TBL_USERS." WHERE u_id='$user'";
				$result2 = mysql_query($q,$this->connection);
				$first_name = mysql_result($result2, 0, "first_name");
				$last_name = mysql_result($result2, 0, "last_name");

						$string.=	"<div class=\"col-xs-12 col-sm-12 col-md-12 center post\">";
						$string.=	"<div class=\"col-xs-4 col-sm-4 col-md-4 bold\">".$first_name." ".$last_name."</div>";
						$string.=	"<div class=\"col-xs-4 col-sm-4 col-md-4 overflow\"><a href=\"".$location."\">";
						$string.=		"<button class=\"btn btn-primary\">".basename($location)."</button></a></div>";
						$string.=		"<div class=\"col-xs-4 col-sm-4 col-md-4\" style=\"color: red;\">".$date." ".$time."</div>";
					$string.=	"</div>";
			}
		return $string;
		}
		else
			return "<label class=\"font-big col-xs-12 col-md-12 pad-top-2 pad-bottom-2 center\">No Assignments uploaded!</label>";
	}

//	deletes a record from database
	function delete_record($db, $id, $id_val){
		$q = "DELETE FROM ".$db." WHERE ".$id."='$id_val'";
		$result = mysql_query($q,$this->connection);
		if($result == 1)
			return 1;
		else
			return 0;
	}
};

//	instantiate MySqlDB class
$database = new MySqlDB();
?>