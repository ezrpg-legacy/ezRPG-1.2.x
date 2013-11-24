<?php
/*
 * ezRPG API
 * Will open up ezRPG API via json call
 */
define('IN_EZRPG', true);
require_once '../init.php';
global $settings;
if(isset($_GET['api_key']))
	$api['key'] = $_GET['api_key'];
	
if(isset($_GET['api_user']))
	$api['user'] = $_GET['api_user'];
	
if(isset($_GET['api_pass']))
	$api['pass'] = $_GET['api_pass'];
	
if($settings->setting['api']['apistatus']['value']['value'])
{
	if(isset($_GET['api_method']))
	{
		switch($_GET['api_method']){
			case 'stats':
				send_stats($api);
				break;
			//case 'mybblogin':
				//mybblogin();
				//break;
		}
	}else{
		echo json_encode(array('status'=>'failed','msg'=>'No method given'));
	}
}

function send_stats($api){
	global $dbase;
	
	if(!isset($api['user']))
	{
		echo json_encode(array('status'=>'failed','error'=>'Missing username'));
	}else{
		$query = $dbase->execute("SELECT id, username FROM <ezrpg>players WHERE username='". $api['user'] ."'");
		if($dbase->numRows($query) != 0)
		{
			$pid = $dbase->fetch($query);
			$query2 = $dbase->execute("SELECT * FROM <ezrpg>players_meta WHERE pid=".$pid->id);
			$result = $dbase->fetch($query2);
			$stats = array();
			$stats['username'] = $pid->username;
			foreach($result as $item=>$key)
			{
				$stats[$item] = $key;
			}
			
			echo json_encode(array('status'=>'success', 'player'=>$stats));
		}
	}
}

function require_key()
{
	global $dbase, $settings;
	if($settings->setting['api'])
	{
		if(isset($_GET['api_key']))
		{
			if($settings->setting['api']['api_key']['value'] != $_GET['api_key'])
			{
				echo json_encode(array('status'=>'failed','msg'=>'API Key mismatch'));
				return false;
			}else{
				return true;
			}			
		}else{
			echo json_encode(array('status'=>'failed','msg'=>'No API Key given'));
			return false;
		}
	}else{
	return true;
	}
}
?>