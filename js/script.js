/*
              Project Name: eClassroom
                   Authors: Neeraj Buccam
                            Girish Matarbhog
                            Amit Chiplunkar

              Roll Numbers: 1360, 1336, 1338

              Release Date: 07-May-2015
                   Version: 1.0
*/
//  Declarations
    var sec=0,min=0,hour=0;
    var xmlhttp,user,reply;
    var x=0, y=0, new_x=0, old_x=0, new_y=0, old_y=0, tmp=0;
//  toggle function
	function toggle (id) {
	  var div = document.getElementById(id);
	  if(div.style.display == "block")
	      div.style.display = "none";
	  else
	      div.style.display = "block";
	}

//  Timer function
    function timer(){
        var time, H, M, S;
            time = document.getElementById("time");
            sec--;
            if(sec < 0){
                sec=59;
                min--;
            }
            if(min < 0){
                min=59;
                hour--;
            }
            if(hour < 0){
                hour=0;
            }
            H = (hour < 10) ? "0"+hour : hour;
            M = (min < 10) ? "0"+min : min;
            S = (sec < 10) ? "0"+sec : sec;
            time.innerHTML = H+":"+M+":"+S;
            if(sec <= 0 && min <= 0 && hour <= 0){
                time.innerHTML = "";
                clearInterval(t_interval);
                window.location.href = "grid.php?t=0";
            }
    }

    function set_user(option){
        user = option.value;
        setTimeout(get_level, 1);
    }

//  get user level
    function get_level(){
        var x;
        xmlhttp = new XMLHttpRequest();
      
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                reply = xmlhttp.responseText;

                document.getElementById("0").removeAttribute("selected");
                document.getElementById("1").removeAttribute("selected");
                document.getElementById("2").removeAttribute("selected");
            x = document.getElementById(reply);
            x.setAttribute("selected","selected");
            }
        }
        xmlhttp.open("GET","includes/process.php?get_level=1&user="+user,true);
        xmlhttp.send();
    }

//  Image Move
    function downimg(e){
        e.preventDefault();
        if(img = document.getElementById('img')){
            tmp = 1;
            x = e.clientX;
            y = e.clientY;
        }
    }
    function mvImg(e){
        if(img = document.getElementById('img')){
            e.preventDefault();
            if(tmp == 1){
                img.style.position = 'absolute';
                new_x = e.clientX;
                new_y = e.clientY;
                img.style.left = new_x - x + old_x;
                img.style.top = new_y - y + old_y;
            }
        }
    }
    function upimg(e){
        e.preventDefault();
        if(img = document.getElementById('img')){
            old_x = parseInt(img.style.left);
            old_y = parseInt(img.style.top);
        tmp = 0;
        }  
    }