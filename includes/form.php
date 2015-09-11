<?

class form{

//form fields validation function
	function validate($field,$type){
		//	trim input and check if empty
		if(!$field || strlen($field = trim($field)) == 0)
			return 0;
		//	password validation
		if($type != "pass")
			$field = strtolower($field);
		//	name validation
		if($type == "name"){
			if(!eregi("^[a-z]+$", $field))
				return 0;
		}
		//	email validation
		else if($type == "email"){
			if(!eregi("^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]{2,}){1})$", $field))
				return 0;
		}
		//	year validation
		else if($type == "year"){
			if(strlen($field = trim($field)) != 1)
				return 0;
		}
		//	question validation
		else if($type == "quest"){
			if(strlen($field = trim($field)) < 10)
				return 0;
		}
		//	option validation
		else if($type == "option"){
			if(strlen($field = trim($field)) < 1)
				return 0;
		}
		//	duration validation
		else if($type == "duration"){
			if(!eregi("^[0]?[1-9]{0,1}(\.[0-9]{1,2})?$", $field))
				return 0;
		}

		return 1;
	}

//	trim, stripslashes, ignore special charaters from input and return it
	function input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

};

//	instantiate form class
$form = new form();

?>