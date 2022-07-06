<?php
//============== Set json header ===============
/*function set_json_header(){
	@header("content-type: application/json");
}

//========= Empty check ==========
function blank($var, $name='') {
	if( !empty($name) ){
		if( empty($var) && $var!='0' ) {
			set_json_header();
			echo json_encode(array('status'=>'0', 'data'=>'', 'message'=>"Check Your Input (".$name.")"));
			exit;
		}  else {
			return $var;
		}
	} else {
		if( empty($var) ) {
			set_json_header();
			echo json_encode(array('status'=>'0', 'data'=>'', 'message'=>"Check Your Input."));
			exit;
		} else {
			return $var;
		}
	}
}*/

//======== Authentication Function =========
/*function authenticate($uniquecode, $token) {
	$CI =& get_instance();
	set_json_header();
	
	//echo json_encode(array('status'=>'-1', 'data'=>'', 'message'=>'Contact helpdesk.'));
	//exit;

	if( empty($uniquecode) ) {
		echo json_encode(array('status'=>'0', 'data'=>'', 'message'=>'Check Your Input.'));
		exit;
	}
	$where = array(
		'uniquecode' => $uniquecode,
		'token' => $token,
	);
	$CI->db->where($where);
	$query = $CI->db->get('user');
	if( $query->num_rows() > 0 ){
		return true;
	} else {
		$data = array('status'=>'-1', 'data'=>'', 'message'=>'Authentication Failed!');
		echo json_encode($data);
		exit;
	}
}*/

//============ ios decrypt ============
/*function ios_decrypt($key, $data) {
	$data = base64_decode($data);
	if(16 !== strlen($key)) $key = hash('MD5', $key, true);
	$data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16));
	$padding = ord($data[strlen($data) - 1]); 
	return substr($data, 0, -$padding); 
}

//========= Decrypt =====================
function decrypt($Str, $Key) {
	$decrypted= mcrypt_decrypt(
		MCRYPT_RIJNDAEL_128,
		$Key,
		base64_decode($Str),
		MCRYPT_MODE_ECB
	);
	$dec_s = strlen($decrypted);
	$padding = ord($decrypted[$dec_s-1]);
	$decrypted = substr($decrypted, 0, -$padding);
	return $decrypted;
}*/

// ============= Error Code =============
function error_code($code){

	$CI =& get_instance();
	$output = false;
	$CI->db->select('*');
	$CI->db->from('m_error_code');
	$CI->db->where('code', $code);
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		$result = $query->row_array();
		$output = $result['message'];
	} else {
		$output = '';
	}
	return $output;
}

//======== Real escape string ============
function escape($text) {
	$db = get_instance()->db->conn_id;
    $text = mysqli_real_escape_string($db, $text);
    return $text;
}

//======== Keep json req and res ============
function keep_req_res($api = '', $json_input = '', $json_output = '', $uniquecode = '', $role = '', $device = '', $table_name = '', $table_id = '') {
	$CI =& get_instance();
	$insert_array = array(
		"api" => $api,
		"table_id" => $table_id,
		"table_name" => $table_name,
		"json_input" => $json_input,
		"json_output" => $json_output,
		"uniquecode" => $uniquecode,
		"role" => $role,
		"date" => date("Y-m-d"),
		"time" => date("H:i:s"),
		"device_info" => $device,
		"ip" => $_SERVER['REMOTE_ADDR']
	);
	$query_exception = $CI->db->insert('api_req_res', $insert_array);
	return true;
}

//======== Get user data ============
function get_data($uniquecode, $role = '') {
	$CI =& get_instance();
	$output = false;
	$CI->db->select('*');
	$CI->db->from('user');
	$CI->db->where('uniquecode', $uniquecode);
	if(!empty($role)) {
		$CI->db->where('role', $role);
	}
	$CI->db->where('status', 'Approved');
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		return $query->row_array();
	}
	return $output;
}

//======== Distance calculator ============
function distance($lat1, $lon1, $lat2, $lon2, $unit) {

	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);

	if ($unit == "K") {
		return ($miles * 1.609344);
	} else if ($unit == "N") {
		return ($miles * 0.8684);
	} else {
		return $miles;
	}
}

//======== Function to check retailer active ============
function retailer_active($uniquecode) {
	$CI =& get_instance();
	$output = false;
	$CI->db->select('*');
	$CI->db->from('user');
	$CI->db->where('uniquecode', $uniquecode);
	$CI->db->where('role', 'retailer');
	$CI->db->where('status', 'Approved');
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		return true;
	}
	return $output;
}

//======== change rupee format ============
// function rupee_format($amount) {	
// 	setlocale(LC_MONETARY, 'en_IN');
// 	if (ctype_digit($amount) ) {
// 		$amount = money_format('%!.0n', $amount);
// 	}
// 	else {
// 		$amount = money_format('%!i', $amount);
// 	}
// 	return $amount;
// }


function ars_in_check($uniquecode, $date) {
	$CI =& get_instance();
	$output = false;
	$CI->db->select('*');
	$CI->db->from('are_attendance_in_out');
	$CI->db->where('uniquecode', $uniquecode);
	$CI->db->where('datebyweb', $date);
	$CI->db->order_by('id', 'DESC');
	$CI->db->limit('1');
	$query = $CI->db->get();
	if($query->num_rows() > 0) {
		$data = $query->row_array();
		if( $data['status'] == 'IN' ) {
			return $data['store_id'];
		} else {
			set_json_header();
			echo json_encode(array('status'=>'0', 'data'=>'', 'message'=>'Kindly In from any Store.'));
			exit();
		}
	} else {
		set_json_header();
		echo json_encode(array('status'=>'0', 'data'=>'', 'message'=>'Kindly In from any Store.'));
		exit();
	}
}

//======== ASIN check ============
function asin_active( $asin ) {
	$CI =& get_instance();
	$output = false;
	$CI->db->select('*');
	$CI->db->from('product_model');
	$CI->db->where('asin', $asin);
	$CI->db->where('status', 'Active');
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		return true;
	} else {
		set_json_header();
		echo json_encode(array('status'=>'0', 'data'=>'', 'message'=>'Selected asin is not active.'));	
		exit();
	}
}

//======== Get role ============
function get_role($uniquecode) {
	$CI =& get_instance();
	$output = false;
	$CI->db->select('role');
	$CI->db->from('user');
	$CI->db->where('uniquecode', $uniquecode);
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		$result = $query->row_array();
		return $result['role'];
	}
	return $output;
}

//======== Get id ============
function get_id($uniquecode) {
	$CI =& get_instance();
	$output = false;
	$CI->db->select('id');
	$CI->db->from('user');
	$CI->db->where('uniquecode', $uniquecode);
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		$result = $query->row_array();
		return $result['id'];
	}
	return $output;
}

//======== Get product data ============
function get_product_data($asin, $role = '') {
	$CI =& get_instance();
	$output = false;
	$CI->db->from('product_model');
	$CI->db->where('asin', $asin);
	$CI->db->where('status', 'Active');
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		return $query->row_array();
	}
	return $output;
}

//======== Get user data ============
function get_office_data($uniquecode, $role = '') {
	$CI =& get_instance();
	$output = false;
	$CI->db->select('*');
	$CI->db->from('user');
	//$CI->db->where('email', $uniquecode);
	$CI->db->where('uniquecode', $uniquecode);
	if(!empty($role)) {
		$CI->db->where('role', $role);
	}
	$CI->db->where('status', 'Approved');
	$query = $CI->db->get();
	//echo $CI->db->last_query();
	if( $query->num_rows() > 0 ) {
		return $query->row_array();
	}
	return $output;
}
?>