<?php
require_once 'app/db_config.php';

//in order to not create a function that already exist
if(!function_exists('is_logged_in')){
    //"returns" true or false value if logged in
    function is_logged_in(){
       return isset($_SESSION['user_id']);
    }
}

if(!function_exists('redirect_auth')){
    //func with default parameters to redirect client that is offline to the home page
    //if the client is logged in then he will be redirected according to his url search
function redirect_auth($redirect_if_logged = true,$location = './'){
    if( $redirect_if_logged && is_logged_in() || 
        !$redirect_if_logged && !is_logged_in()){

        header("Location: $location");
        exit();
    }
  }
}

if(!function_exists('field_errors')){
    function field_errors($field_name){
        //if the global $errors wouldn't be existed then create it here
        global $errors;
        if(isset($errors) && !empty($errors[$field_name])){
            return "<span class='text-danger form-text'>$errors[$field_name]</span>";
        }
    }
}

if(!function_exists('posted_value')){
   
   function posted_value($field_name){
      return isset($_REQUEST[$field_name]) && !empty($_REQUEST[$field_name]) ? $_REQUEST[$field_name]:'';
   }
}

//time ago finction
if(!function_exists('ago')){
    /**
     * Expresses given timestam as how long ago
     *
     * @param string $time
     *
     * @return string with expression of how long ago (we will translate 
     * it back to integer later on)
     */
function ago($time)
{
    date_default_timezone_set('Asia/Jerusalem');
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

    $now = time();
    $time = strtotime($time); //converts string to time

    $difference = $now - $time;

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if ($difference != 1) {
        $periods[$j] .= "s";
    }

    return "$difference $periods[$j] ago ";
  }
}

if(!function_exists('active_nav_link')){
    /*
    * @param string $item
    *
    * return string
    */
    function active_nav_link ($item_title){
        global $page_title;
        $class_str='';

        if(isset($page_title) && $page_title === $item_title){
         return ' active'; // the space is important for the bootstrap classes
        } 

        return $class_str;
    }
}

if(!function_exists('csrf')){

    function csrf(){
        $token = sha1(time()); //create a random token based on "time()".
        $_SESSION['token']=$token; //stores the toekn in $_SESSION for later validation.
        return $token;// returns the value to the "HTML"
    }
}

if(!function_exists('validate_csrf')){

    function validate_csrf(){
        if(isset($_REQUEST['token']) && isset($_SESSION['token'])){
            //if this is true (===), the functions ends and returns "true"
            return $_REQUEST['token'] === $_SESSION['token'];
        }
        return false;
    }
}

if(!function_exists('random_image')){
/* 
* generate random bot-avatar as a profile image if the user created new account with no profile image uploaded
*/
    function random_image(){

        $rand = time() . '_' . rand(1, 1000);
        $save_to = "images/profiles/$rand.svg";
        $curl = curl_init("https://avatars.dicebear.com/api/bottts/$rand.svg");

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // will return data as string of the return value of curl_exec
        //or else i will get an '1' instead if not the string

        $response = curl_exec($curl);
        curl_close($curl); //close session

        if(file_exists($save_to)){
            unlink($save_to);
        }

        $f = fopen($save_to, 'x'); // the 'x' mode is Create and open for writing only // i dont want to load anything from there
        fwrite($f, $response); //create the new file at $f=location from the $response=url
        fclose($f);

        return "$rand.svg";
    }
}