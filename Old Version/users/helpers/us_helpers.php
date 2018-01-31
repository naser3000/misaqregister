<?php

require_once $abs_us_root.$us_url_root.'usersc/includes/custom_functions.php';
require_once $abs_us_root.$us_url_root.'usersc/includes/analytics.php';

function testUS(){
	echo "<br>";
	echo "UserSpice Functions have been properly included";
	echo "<br>";
}


function get_gravatar($email, $s = 120, $d = 'mm', $r = 'pg', $img = false, $atts = array() ) {
	$url = 'https://www.gravatar.com/avatar/';
	$url .= md5( strtolower( trim( $email ) ) );
	$url .= "?s=$s&d=$d&r=$r";
	if ( $img ) {
		$url = '<img src="' . $url . '"';
		foreach ( $atts as $key => $val )
		$url .= ' ' . $key . '="' . $val . '"';
		$url .= ' />';
	}
	return $url;
}

//Check if a permission level ID exists in the DB
function permissionIdExists($id) {
	$db = DB::getInstance();
	$query = $db->query("SELECT id FROM permissions WHERE id = ? LIMIT 1",array($id));
	$num_returns = $query->count();

	if ($num_returns > 0) {
		return true;
	} else {
		return false;
	}
}

//Check if a user ID exists in the DB
function userIdExists($id) {
	$db = DB::getInstance();
	$query = $db->query("SELECT * FROM users WHERE id = ?",array($id));
	$num_returns = $query->count();
	if ($num_returns > 0){
		return true;
	}else{
		return false;
	}
}

//Check if a plan ID exists in the DB
function planIdExists($id) {
	$db = DB::getInstance();
	$query = $db->query("SELECT * FROM plans WHERE id = ?",array($id));
	$num_returns = $query->count();
	if ($num_returns > 0){
		return true;
	}else{
		return false;
	}
}

//Retrieve information for a single permission level
function fetchPermissionDetails($id) {
	$db = DB::getInstance();
	$query = $db->query("SELECT id, name FROM permissions WHERE id = ? LIMIT 1",array($id));
	$results = $query->first();
	$row = array('id' => $results->id, 'name' => $results->name);
	return ($row);
}

//Change a permission level's name
function updatePermissionName($id, $name) {
	$db = DB::getInstance();
	$fields=array('name'=>$name);
	$db->update('permissions',$id,$fields);
}

//Checks if a username exists in the DB
function usernameExists($username)   {
	$db = DB::getInstance();
	$query = $db->query("SELECT * FROM users WHERE username = ?",array($username));
	$results = $query->results();
	return ($results);
}

//Retrieve information for all users
function fetchAllUsers() {
	$db = DB::getInstance();
	$query = $db->query("SELECT * FROM users");
	$results = $query->results();
	return ($results);
}

//Retrieve complete user information by username, token or ID
function fetchUserDetails($username=NULL,$token=NULL, $id=NULL){
	
	if($username!=NULL) {
		$column = "username";
		$data = $username;
	}elseif($id!=NULL) {
		$column = "id";
		$data = $id;
	}
	//mysql_query("SET NAMES utf8");
	$db = DB::getInstance();
	$query = $db->query("SELECT * FROM users WHERE $column = $data LIMIT 1");
	$results = $query->first();
	return ($results);
}

//Retrieve complete plan information by token or ID
function fetchPlanDetails($token=NULL, $id=NULL){
	if($id!=NULL) {
		$column = "id";
		$data = $id;
	}
	$db = DB::getInstance();
	$query = $db->query("SELECT * FROM plans WHERE $column = $data LIMIT 1");
	$results = $query->first();
	return ($results);
}

//Retrieve complete capacity information by ID
function fetchCapacityDetails($id){
	$db = DB::getInstance();
	$query = $db->query("SELECT * FROM capacity WHERE id = $id LIMIT 1");
	$results = $query->first();
	return ($results);
}

//Retrieve complete register information by user_id, plan_id, capacity_id
function fetchPlanRegisterDetails($user_id, $plan_id, $capacity_id){
	$db = DB::getInstance();
	$query = $db->query("SELECT * FROM plan_register WHERE user_id = $user_id AND plan_id = $plan_id AND capacity_id = $capacity_id");
	$results = $query->results();
	return ($results);
}

//Retrieve user registered in plan by plan_id
function fetchPlanRegisteredUsers($plan_id){
	$db = DB::getInstance();
	$query = $db->query("SELECT * FROM plan_register WHERE plan_id = $plan_id");
	$results = $query->results();
	return ($results);
}

function shiftQue($reserved_number, $plan_id, $capacity_id){
	$db = DB::getInstance();
	$query = $db->query("SELECT * FROM  plan_register WHERE plan_id = $plan_id AND capacity_id = $capacity_id");
	$results = $query->results();

	$string = "";
	foreach ($results as $register_data) {
		$shift = $shift1 = $shift2 = $shift3 = 0;
		$register_status = "رزرو";
		if ($register_data->reserved_number > $reserved_number)
			$shift++;
		if ($register_data->reserved_number1 > $reserved_number)
			$shift1++;
		if ($register_data->reserved_number2 > $reserved_number)
			$shift2++;
		if ($register_data->reserved_number3 > $reserved_number)
			$shift3++;

		if ( $register_data->reserved_number3 == 1 )
			$register_status = "ثبت نام";

		$shfit_data = array('reserved_number'=> ($register_data->reserved_number - $shift),
				'reserved_number1'=> ($register_data->reserved_number1 - $shift1),
				'reserved_number2'=> ($register_data->reserved_number2 - $shift2),
				'reserved_number3'=> ($register_data->reserved_number3 - $shift3),
				'status'=> $register_status);
		$db->update('plan_register', $register_data->id, $shfit_data);
		$string .= $register_data->id."*********";
	}
	return ($string);
}


/*
//Retrieve complete capacity information by UserID & PlanID
function fetchCapacityDetails($user_id, $plan_id) {
	$db = DB::getInstance();
	$userdetails = $db->query("SELECT * FROM users WHERE id = $user_id LIMIT 1");
	
	$status = $userdetails->first()->status;
	$yinter = $userdetails->first()->yinter;
	$gender = $userdetails->first()->gender;
	$capacity_query = $db->query("SELECT * FROM capacity WHERE plan_id = $plan_id AND status = ?", array($status));
	//$capacity_query = $db->query("SELECT * FROM capacity WHERE plan_id = $plan_id AND status IN ({$status}) AND yinter IN ({$yinter}) AND gender IN ({$gender})");
	$capacity_results = $capacity_query->results();
	print_r($capacity_results[1]);
	return ($capacity_results);
}
*/

//Retrieve list of permission levels a user has
function fetchUserPermissions($user_id) {
	$db = DB::getInstance();
	$query = $db->query("SELECT * FROM user_permission_matches WHERE user_id = ?",array($user_id));
	$results = $query->results();
	return ($results);
}


//Retrieve list of users who have a permission level
function fetchPermissionUsers($permission_id) {
	$db = DB::getInstance();
	$query = $db->query("SELECT id, user_id FROM user_permission_matches WHERE permission_id = ?",array($permission_id));
	$results = $query->results();
	return ($results);
	$row[$user] = array('id' => $id, 'user_id' => $user);
	if (isset($row)){
		return ($row);
	}
}


//Fetch information for all capacities of plan by plan ID
function fetchAllPlanCapacities($id) {
	$db = DB::getInstance();
	$query = $db->query("SELECT * FROM capacity WHERE plan_id = $id");
	$results = $query->results();
	return ($results);
}


//Retrieve information for all plans
function fetchAllPlans() {
	$db = DB::getInstance();
	$query = $db->query("SELECT * FROM plans");
	$results = $query->results();
	return ($results);
}

//Unmatch permission level(s) from user(s)
function removePermission($permissions, $members) {
	$db = DB::getInstance();
	if(is_array($members)){
		$memberString = '';
		foreach($members as $member){
		  $memberString .= $member.',';
		}
		$memberString = rtrim($memberString,',');

		$q = $db->query("DELETE FROM user_permission_matches WHERE permission_id = ? AND user_id IN ({$memberString})",[$permissions]);
	}elseif(is_array($permissions)){
		$permissionString = '';
		foreach($permissions as $permission){
			$permissionString .= $permission.',';
		}
		$permissionString = rtrim($permissionString,',');
		$q = $db->query("DELETE FROM user_permission_matches WHERE user_id = ? AND permission_id IN ({$permissionString})",[$members]);
	}
	return $q->count();
}

//Retrieve a list of all .php files in root files folder
function getPathPhpFiles($absRoot,$urlRoot,$fullPath) {
	$directory = $absRoot.$urlRoot.$fullPath;
	//bold ($directory);
	$pages = glob($directory . "*.php");

	foreach ($pages as $page){
		$fixed = str_replace($absRoot.$urlRoot,'',$page);
		$row[$fixed] = $fixed;
	}
	return $row;
}

//Retrieve a list of all .php files in root files folder
function getPageFiles() {
	$directory = "../";
	$pages = glob($directory . "*.php");
	foreach ($pages as $page){
		$fixed = str_replace('../','/'.$us_url_root,$page);
		$row[$fixed] = $fixed;
	}
	return $row;
}

//Retrive a list of all .php files in users/ folder
function getUSPageFiles() {
	$directory = "../users/";
	$pages = glob($directory . "*.php");
	foreach ($pages as $page){
		$fixed = str_replace('../users/','/'.$us_url_root.'users/',$page);
		$row[$fixed] = $fixed;
	}
	return $row;
}

//Delete a page from the DB
function deletePages($pages) {
	$db = DB::getInstance();
	if(!$query = $db->query("DELETE FROM pages WHERE id IN ({$pages})")){
		throw new Exception('There was a problem deleting pages.');
	}else{
		return true;
	}
}

//Fetch information on all pages
function fetchAllPages() {
	$db = DB::getInstance();
	$query = $db->query("SELECT id, page, private FROM pages ORDER BY id DESC");
	$pages = $query->results();
	//return $pages;

	if (isset($row)){
		return ($row);
	}else{
		return $pages;
	}
}

//Fetch information for a specific page
function fetchPageDetails($id) {
	$db = DB::getInstance();
	$query = $db->query("SELECT id, page, private FROM pages WHERE id = ?",array($id));
	$row = $query->first();
	return $row;
}


//Check if a page ID exists
function pageIdExists($id) {
	$db = DB::getInstance();
	$query = $db->query("SELECT private FROM pages WHERE id = ? LIMIT 1",array($id));
	$num_returns = $query->count();
	if ($num_returns > 0){
		return true;
	}else{
		return false;
	}
}

//Toggle private/public setting of a page
function updatePrivate($id, $private) {
	$db = DB::getInstance();
	$result = $db->query("UPDATE pages SET private = ? WHERE id = ?",array($private,$id));
	return $result;
}

//Add a page to the DB
function createPages($pages) {
	$db = DB::getInstance();
	foreach($pages as $page){
		$fields=array('page'=>$page, 'private'=>'0');
		$db->insert('pages',$fields);
	}
}

//Match permission level(s) with page(s)
function addPage($page, $permission) {
	$db = DB::getInstance();
	$i = 0;
	if (is_array($permission)){
		foreach($permission as $id){
			$query = $db->query("INSERT INTO permission_page_matches (
			permission_id, page_id ) VALUES ( $id , $page )");
			$i++;
		}
	} elseif (is_array($page)){
		foreach($page as $id){
			$query = $db->query("INSERT INTO permission_page_matches (
			permission_id, page_id ) VALUES ( $permission , $id )");
			$i++;
		}
	} else {
		$query = $db->query("INSERT INTO permission_page_matches (
		permission_id, page_id ) VALUES ( $permission , $page )");
		$i++;
	}
	return $i;
}

  //Retrieve list of permission levels that can access a page
function fetchPagePermissions($page_id) {
	$db = DB::getInstance();
	$query = $db->query("SELECT id, permission_id FROM permission_page_matches WHERE page_id = ? ",array($page_id));
	$results = $query->results();
	return($results);
}

//Retrieve list of pages that a permission level can access
function fetchPermissionPages($permission_id) {
	$db = DB::getInstance();

	$query = $db->query(
	"SELECT m.id as id, m.page_id as page_id, p.page as page, p.private as private
	FROM permission_page_matches AS m
	INNER JOIN pages AS p ON m.page_id = p.id
	WHERE m.permission_id = ?",[$permission_id]);
	$results = $query->results();
	return ($results);
}

//Unmatched permission and page
function removePage($pages, $permissions) {
	$db = DB::getInstance();
	if(is_array($permissions)){
		$ids = '';
		for($i = 0; $i < count($permissions);$i++){
			$ids .= $permissions[$i].',';
		}
		$ids = rtrim($ids,',');
		if($query = $db->query("DELETE FROM permission_page_matches WHERE permission_id IN ({$ids}) AND page_id = ?",array($pages))){
			return $query->count();
		}
	}elseif(is_array($pages)){
		$ids = '';
		for($i = 0; $i < count($pages);$i++){
			$ids .= $pages[$i].',';
		}
		$ids = rtrim($ids,',');
		if($query = $db->query("DELETE FROM permission_page_matches WHERE page_id IN ({$ids}) AND permission_id = ?",array($permissions))){
			return $query->count();
		}
	}
}

//Delete a defined array of users
function deleteUsers($users) {
	$db = DB::getInstance();
	$i = 0;
	foreach($users as $id){
		$query1 = $db->query("DELETE FROM users WHERE id = ?",array($id));
		$query2 = $db->query("DELETE FROM user_permission_matches WHERE user_id = ?",array($id));
		$query3 = $db->query("DELETE FROM profiles WHERE user_id = ?",array($id));
		$i++;
	}
	return $i;
}

// retrieve ?dest=page and check that it exists in the legitimate pages in the
// database or is in the Config::get('whitelisted_destinations')
function sanitizedDest($varname='dest') {
	if ($dest = Input::get($varname)) {
		// if it exists in the database then it is a legitimate destination
		$db = DB::getInstance();
		$query = $db->query("SELECT id, page, private FROM pages WHERE page = ?",[$dest]);
		$count = $query->count();
		if ($count>0){
			return $dest;
		}
		// if the administrator has intentionally whitelisted a destination it is legitimate
		if ($whitelist = Config::get('whitelisted_destinations')) {
			if (in_array($dest, (array)$whitelist)) {
				return $dest;
			}
		}
	}
	return false;
}

//Check if a user has access to a page
function securePage($uri){
	//Separate document name from uri
	//$tokens = explode('/', $uri);
	//$page = end($tokens);

	$abs_us_root=$_SERVER['DOCUMENT_ROOT'];

	$self_path=explode("/", $_SERVER['PHP_SELF']);
	$self_path_length=count($self_path);
	$file_found=FALSE;

	for($i = 1; $i < $self_path_length; $i++){
		array_splice($self_path, $self_path_length-$i, $i);
		$us_url_root=implode("/",$self_path)."/";

		if (file_exists($abs_us_root.$us_url_root.'z_us_root.php')){
			$file_found=TRUE;
			break;
		}else{
			$file_found=FALSE;
		}
	}

	$urlRootLength=strlen($us_url_root);
	$page=substr($uri,$urlRootLength,strlen($uri)-$urlRootLength);

	//bold($page);

	$db = DB::getInstance();
	$id = null;
	$private = null;
	// dnd($page);
	global $user;
	// dnd($user);
	if(isset($user) && $user->data() != null){
		if($user->data()->permissions==0){
			bold('<br><br><br>Sorry. You have been banned. If you feel this is an error, please contact the administrator.');
			die();
		}
	}
	//retrieve page details
	$query = $db->query("SELECT id, page, private FROM pages WHERE page = ?",[$page]);
	$count = $query->count();
	if ($count==0){
		bold('<br><br>You must go into the Admin Panel and click the Manage Pages button to add this page to the database. Doing so will make this error go away.');
		die();
	}
	$results = $query->first();

	$pageDetails = array( 'id' =>$results->id, 'page' => $results->page, 'private' =>$results->private);

	$pageID = $results->id;
	$ip = ipCheck();
	//If page does not exist in DB, allow access
	if (empty($pageDetails)){
		return true;
	}elseif ($pageDetails['private'] == 0){//If page is public, allow access
		return true;
	}elseif(!$user->isLoggedIn()){ //If user is not logged in, deny access
		$fields = array(
			'user'	=> 0,
			'page'	=> $pageID,
			'ip'		=> $ip,
		);
		$db->insert('audit',$fields);
		require_once $abs_us_root.$us_url_root.'usersc/scripts/not_logged_in.php';
		Redirect::to($us_url_root.'users/login.php', '?dest='.$page);
		return false;
	}else {
		//Retrieve list of permission levels with access to page

		$query = $db->query("SELECT permission_id FROM permission_page_matches WHERE page_id = ?",[$pageID]);

		$permission = $query->results();
		$pagePermissions[] = $permission;

		//Check if user's permission levels allow access to page
		if (checkPermission($pagePermissions)){
			return true;
		}elseif  (in_array($user->data()->id, $master_account)){ //Grant access if master user
			return true;
		}else {
			if (!$homepage = Config::get('homepage'))
				$homepage = 'index.php';
			$fields = array(
				'user'	=> $user->data()->id,
				'page'	=> $pageID,
				'ip'		=> $ip,
			);
			$db->insert('audit',$fields);
			require_once $abs_us_root.$us_url_root.'usersc/scripts/did_not_have_permission.php';
			Redirect::to($homepage);
			return false;
		}
	}
}

//Does user have permission
//This is the old school UserSpice Permission System
function checkPermission($permission) {
	$db = DB::getInstance();
	global $user;
	//Grant access if master user
	$access = 0;

	foreach($permission[0] as $perm){
		if ($access == 0){
			$query = $db->query("SELECT id FROM user_permission_matches  WHERE user_id = ? AND permission_id = ?",array($user->data()->id,$perm->permission_id));
			$results = $query->count();
			if ($results > 0){
				$access = 1;
			}
		}
	}
	if ($access == 1){
		return true;
	}
	if ($user->data()->id == 1){
		return true;
	}else{
		return false;
	}
}

function checkMenu($permission, $id) {
	$db = DB::getInstance();
	global $user;
	//Grant access if master user
	$access = 0;

	if ($access == 0){
		$query = $db->query("SELECT id FROM user_permission_matches  WHERE user_id = ? AND permission_id = ?",array($id,$permission));
		$results = $query->count();
		if ($results > 0){
			$access = 1;
		}
	}
	if ($access == 1){
		return true;
	}
	if ($user->data()->id == 1){
		return true;
	}else{
		return false;
	}
}

//Retrieve information for all permission levels
function fetchAllPermissions() {
	$db = DB::getInstance();
	$query = $db->query("SELECT id, name FROM permissions");
	$results = $query->results();
	return ($results);
}

//Displays error and success messages
function resultBlock($errors,$successes){
	//Error block
	if(count($errors) > 0){
		echo "<div class='alert alert-danger alert-dismissible' role='alert'> <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
		<ul>";
		foreach($errors as $error){
			echo "<li>".$error."</li>";
		}
		echo "</ul>";
		echo "</div>";
	}

	//Success block
	if(count($successes) > 0){
		echo "<div class='alert alert-success alert-dismissible' role='alert'> <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
		<ul>";
		foreach($successes as $success){
			echo "<li>".$success."</li>";
		}
		echo "</ul>";
		echo "</div>";
	}
}

//Inputs language strings from selected language.
function lang($key,$markers = NULL){
	global $lang;
	if($markers == NULL){
		$str = $lang[$key];
	}else{
	//Replace any dyamic markers
	$str = $lang[$key];
	$iteration = 1;
		foreach($markers as $marker){
			$str = str_replace("%m".$iteration."%",$marker,$str);
			$iteration++;
		}
	}
	//Ensure we have something to return
	if($str == ""){
		return ("No language key found");
	}else{
		return $str;
	}
}


//Check if a permission level name exists in the DB
function permissionNameExists($permission) {
	$db = DB::getInstance();
	$query = $db->query("SELECT id FROM permissions WHERE
	name = ?",array($permission));
	$results = $query->results();
}

//Match permission level(s) with user(s)
function addPermission($permission_ids, $members) {
	$db = DB::getInstance();
	$i = 0;
	if(is_array($permission_ids)){
		foreach($permission_ids as $permission_id){
			if($db->query("INSERT INTO user_permission_matches (user_id,permission_id) VALUES (?,?)",[$members,$permission_id])){
				$i++;
			}
		}
	}elseif(is_array($members)){
		foreach($members as $member){
			if($db->query("INSERT INTO user_permission_matches (user_id,permission_id) VALUES (?,?)",[$member,$permission_ids])){
				$i++;
			}
		}
	}
	return $i;
}


//Delete a permission level from the DB
function deletePermission($permission) {
	global $errors;
	$i = 0;
	$db = DB::getInstance();
	foreach($permission as $id){
		if ($id == 1){
		$errors[] = lang("CANNOT_DELETE_NEWUSERS");
		}
		elseif ($id == 2){
			$errors[] = lang("CANNOT_DELETE_ADMIN");
		}else{
			$query1 = $db->query("DELETE FROM permissions WHERE id = ?",array($id));
			$query2 = $db->query("DELETE FROM user_permission_matches WHERE permission_id = ?",array($id));
			$query3 = $db->query("DELETE FROM permission_page_matches WHERE permission_id = ?",array($id));
			$i++;
		}
	}
	return $i;

	//Redirect::to('admin_permissions.php');
}

//Checks if an email is valid
function isValidEmail($email){
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	}
	else {
		return false;
	}
}

//Check if an email exists in the DB
function emailExists($email) {
	$db = DB::getInstance();
	$query = $db->query("SELECT email FROM users WHERE email = ?",array($email));
	$num_returns = $query->count();
	if ($num_returns > 0){
		return true;
	}else{
		return false;
	}
}

//Update a user's email
function updateEmail($id, $email) {
	$db = DB::getInstance();
	$fields=array('email'=>$email);
	$db->update('users',$id,$fields);

	return true;
}

function echoId($id,$table,$column){
$db = DB::getInstance();
$query = $db->query("SELECT $column FROM $table WHERE id = $id LIMIT 1");
$count=$query->count();

if ($count > 0) {
  $results=$query->first();
  foreach ($results as $result){
    echo $result;
  }
} else {
  echo "Not in database";
  Return false;
}
}

function bin($number){
  if ($number == 0){
    echo "<strong><font color='red'>No</font></strong>";
  }
  if ($number == 1){
    echo "<strong><font color='green'>Yes</font></strong>";
  }
  if ($number != 0 && $number !=1){
    echo "<strong><font color='blue'>Other</font></strong>";
  }
}

function echouser($id){
  $db = DB::getInstance();
	$settingsQ = $db->query("SELECT echouser FROM settings");
	$settings = $settingsQ->first();

	if($settings->echouser == 0){
	$query = $db->query("SELECT fname,lname FROM users WHERE id = ? LIMIT 1",array($id));
  $count=$query->count();
	if ($count > 0) {
		$results=$query->first();
		echo $results->fname." ".$results->lname;
	} else {
		echo "-";
	}
	}

	if($settings->echouser == 1){
	$query = $db->query("SELECT username FROM users WHERE id = ? LIMIT 1",array($id));
  $count=$query->count();
	if ($count > 0) {
		$results=$query->first();
		echo ucfirst($results->username);
	} else {
		echo "-";
	}
	}

	if($settings->echouser == 2){
	$query = $db->query("SELECT username,fname,lname FROM users WHERE id = ? LIMIT 1",array($id));
  $count=$query->count();
	if ($count > 0) {
		$results=$query->first();
		echo ucfirst($results->username).'('.$results->fname.' '.$results->lname.')';
	} else {
		echo "-";
	}
	}

	if($settings->echouser == 3){
	$query = $db->query("SELECT username,fname FROM users WHERE id = ? LIMIT 1",array($id));
  $count=$query->count();
	if ($count > 0) {
		$results=$query->first();
		echo ucfirst($results->username).'('.$results->fname.')';
	} else {
		echo "-";
	}
	}


}

function generateForm($table,$id, $skip=[]){
    $db = DB::getInstance();
    $fields = [];
    $q=$db->query("SELECT * FROM {$table} WHERE id = ?",array($id));
    $r=$q->first();

    foreach($r as $field => $value) {
      if(!in_array($field, $skip)){
        echo '<div class="form-group">';
      		echo '<label for="'.$field.'">'.ucfirst($field).'</label>';
      		echo '<input type="text" class="form-control" name="'.$field.'" id="'.$field.'" value="'.$value.'">';
      	echo '</div>';
      }
    }
    return true;
  }

  function generateAddForm($table, $skip=[]){
    $db = DB::getInstance();
    $fields = [];
    $q=$db->query("SELECT * FROM {$table}");
    $r=$q->first();

    foreach($r as $field => $value) {
      if(!in_array($field, $skip)){
        echo '<div class="form-group">';
          echo '<label for="'.$field.'">'.ucfirst($field).'</label>';
          echo '<input type="text" class="form-control" name="'.$field.'" id="'.$field.'" value="">';
        echo '</div>';
      }
    }
    return true;
  }

  function updateFields2($post, $skip=[]){
    $fields = [];
    foreach($post as $field => $value) {
      if(!in_array($field, $skip)){
        $fields[$field] = sanitize($post[$field]);
      }
    }
    return $fields;
  }

  function hasPerm($permissions, $id) {
  	$db = DB::getInstance();
  	global $user;
  	//Grant access if master user
  	$access = 0;

  foreach($permissions as $permission){

  	if ($access == 0){
  		$query = $db->query("SELECT id FROM user_permission_matches  WHERE user_id = ? AND permission_id = ?",array($id,$permission));
  		$results = $query->count();
  		if ($results > 0){
  			$access = 1;
  		}
  	}
  }
  	if ($access == 1){
  		return true;
  	}
  	if (in_array($user->data()->id, $master_account)){
  		return true;
  	}else{
  		return false;
  	}
  }

	function echopage($id){
		$db = DB::getInstance();
		$query = $db->query("SELECT page FROM pages WHERE id = ? LIMIT 1",array($id));
		$count=$query->count();

		if ($count > 0) {
	    $results=$query->first();
	  	echo $results->page;
		} else {
			echo "Unknown";
		}
	}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function mod($a, $b) {return $a - ($b * floor($a / $b));}

function leap_gregorian($year)
{
    return (($year % 4) == 0) &&
            (!((($year % 100) == 0) && (($year % 400) != 0)));
}

function gregorian_to_jd($year, $month, $day)
{
	$GREGORIAN_EPOCH = 1721425.5;
    return ($GREGORIAN_EPOCH - 1) +
           (365 * ($year - 1)) +
           floor(($year - 1) / 4) +
           (-floor(($year - 1) / 100)) +
           floor(($year - 1) / 400) +
           floor((((367 * $month) - 362) / 12) +
           (($month <= 2) ? 0 :
                               (leap_gregorian($year) ? -1 : -2)
           ) +
           $day);
}
function jd_to_gregorian($jd) {
    //$wjd, $depoch, $quadricent, $dqc, $cent, $dcent, $quad, $dquad,
        //$yindex, $dyindex, $year, $yearday, $leapadj;
	$GREGORIAN_EPOCH = 1721425.5;
    $wjd = floor($jd - 0.5) + 0.5;
    $depoch = $wjd - $GREGORIAN_EPOCH;
    $quadricent = floor($depoch / 146097);
    $dqc = mod($depoch, 146097);
    $cent = floor($dqc / 36524);
    $dcent = mod($dqc, 36524);
    $quad = floor($dcent / 1461);
    $dquad = mod($dcent, 1461);
    $yindex = floor($dquad / 365);
    $year = ($quadricent * 400) + ($cent * 100) + ($quad * 4) + $yindex;
    if (!(($cent == 4) || ($yindex == 4))) {
        $year++;
    }
    $yearday = $wjd - gregorian_to_jd($year, 1, 1);
    $leapadj = (($wjd < gregorian_to_jd($year, 3, 1)) ? 0
                                                  :
                  (leap_gregorian($year) ? 1 : 2)
              );
    $month = floor(((($yearday + $leapadj) * 12) + 373) / 367);
    $day = ($wjd - gregorian_to_jd($year, $month, 1)) + 1;

    return array($year, $month, $day);
}

function leap_persian($year)
{
    return (((((($year - (($year > 0) ? 474 : 473)) % 2820) + 474) + 38) * 682) % 2816) < 682;
}

function persian_to_jd($year, $month, $day)
{
    //$epbase, $epyear;
	$PERSIAN_EPOCH = 1948320.5;
    $epbase = $year - (($year >= 0) ? 474 : 473);
    $epyear = 474 + mod($epbase, 2820);

    return $day +
            (($month <= 7) ?
                (($month - 1) * 31) :
                ((($month - 1) * 30) + 6)
            ) +
            floor((($epyear * 682) - 110) / 2816) +
            ($epyear - 1) * 365 +
            floor($epbase / 2820) * 1029983 +
            ($PERSIAN_EPOCH - 1);
}
function jd_to_persian($jd)
{
    //$year, $month, $day, $depoch, $cycle, $cyear, $ycycle,
   //     $aux1, $aux2, $yday;


    $jd = floor($jd) + 0.5;

    $depoch = $jd - persian_to_jd(475, 1, 1);
    $cycle = floor($depoch / 1029983);
    $cyear = mod($depoch, 1029983);
    if ($cyear == 1029982) {
        $ycycle = 2820;
    } else {
        $aux1 = floor($cyear / 366);
        $aux2 = mod($cyear, 366);
        $ycycle = floor(((2134 * $aux1) + (2816 * $aux2) + 2815) / 1028522) +
                    $aux1 + 1;
    }
    $year = $ycycle + (2820 * $cycle) + 474;
    if ($year <= 0) {
        $year--;
    }
    $yday = ($jd - persian_to_jd($year, 1, 1)) + 1;
    $month = ($yday <= 186) ? ceil($yday / 31) : ceil(($yday - 6) / 30);
    $day = ($jd - persian_to_jd($year, $month, 1)) + 1;
    return array($year, $month, $day);
}

    function jalali_to_gregorian($d) {
        $adjustDay = 0;
        if($d[1]<0){
            $adjustDay = leap_persian($d[0]-1)? 30: 29;
            $d[1]++;
        }
        $gregorian = jd_to_gregorian(persian_to_jd($d[0], $d[1] + 1, $d[2])-$adjustDay);
        $gregorian[1]--;
        return $gregorian;
        //return implode('/', $gregorian);
    }

    function gregorian_to_jalali($d) {
        $jalali = jd_to_persian(gregorian_to_jd($d[0], $d[1] + 1, $d[2]));
        $jalali[1]--;
        $zero = $zero1 = "";
        if ($jalali[1] < 10)
        	$zero = "0";
        if ($jalali[2] < 10)
        	$zero1 = "0";
        return ($jalali[0]."/".$zero.$jalali[1]."/".$zero1.$jalali[2]-1);
    }



