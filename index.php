<?php
require 'vendor/autoload.php';
use flight\Engine;
use Medoo\Medoo;
$database = new Medoo([
  'database_type' => 'mysql',
  'database_name' => '',
  'server' => '',
  'username' => '',
  'password' => '',
  'charset' => 'utf8',
]);
$app = new Engine();
$app->route('/', function(){
  echo 'hello';
});
$app->route('POST /api/all/@tag', function($tag){
  if (ctype_digit($tag) && $tag > 0 && $tag < 4) {
    $privilege = privilege($tag);
    if ( $privilege['view'] ) {
      global $database;
      $datas = $database->select('password', ['id', 'ip', 'service', 'location'], ['type' => $tag, "ORDER" => ["password.id" => "DESC"]]);
      $out = array('data' => $datas, 'status' => 'success', 'add' => $privilege['manage']);
      echo json_encode($out);
    }
  }
});
$app->route('POST /api/validadd/@tag', function($tag){
  if (ctype_digit($tag) && $tag > 0 && $tag < 4) {
    $privilege = privilege($tag);
    if ($privilege['manage']) {
      echo json_encode(array('status' => 'success'));
    }else{
      echo json_encode(array('status' => 'failure'));
    }
  }
});
$app->route('POST /api/add/@tag', function($tag){
  if (ctype_digit($tag) && $tag > 0 && $tag < 4) {
    $privilege = privilege($tag);
    if ($privilege['manage']) {
      $ip = $_POST['ip'];
      $loginUser = $_POST['loginUser'];
      $loginPassword = $_POST['loginPassword'];
      $rootPassword = $_POST['rootPassword'];
      $sshPort = $_POST['sshPort'];
      $service = nl2br(addslashes($_POST['service']));
      $location = nl2br(addslashes($_POST['location']));
      $jump = $_POST['jump'];
      $other = nl2br(addslashes($_POST['other']));

      global $database;
      if ($database->insert('password', [
        "ip" => $ip,
        "loginUser" => $loginUser,
        "loginPassword" => $loginPassword,
        "rootPassword" => $rootPassword,
        "sshPort" => $sshPort,
        "service" => $service,
        "location" => $location,
        "jump" => $jump,
        "other" => $other,
        "type" => $tag,
        "addip" => $_SERVER["REMOTE_ADDR"],
        "addtime" => date('Y-m-d H:i:s')
      ])) {
        echo json_encode(array('status' => 'success'));
      }else{
        echo json_encode(array('status' => 'failure'));
      }
    }
  }
});
$app->route('POST /api/checkip/@tag', function($tag){
  if (ctype_digit($tag) && $tag > 0 && $tag < 4) {
    $privilege = privilege($tag);
    if ($privilege['manage']) {
      $ip = $_POST['ip'];
      global $database;
      if ($database->has('password', ['ip' => $ip])) {
        echo json_encode(array('status' => 'success'));
      }else{
        echo json_encode(array('status' => 'failure'));
      }
    }else{
      echo json_encode(array('status' => 'failure'));
    }
  }
});
$app->route('POST /api/update/@tag/@id', function($tag, $id){
  if (ctype_digit($tag) && ctype_digit($id) && $tag > 0 && $tag < 4) {
    $privilege = privilege($tag);
    if ($privilege['manage']) {
      $ip            = $_POST['ip'];
      $loginUser     = $_POST['loginUser'];
      $loginPassword = $_POST['loginPassword'];
      $rootPassword  = $_POST['rootPassword'];
      $sshPort       = $_POST['sshPort'];
      $service       = nl2br(addslashes($_POST['service']));
      $location      = nl2br(addslashes($_POST['location']));
      $jump          = $_POST['jump'];
      $other         = nl2br(addslashes($_POST['other']));

      global $database;
      if ($database->update('password', [
        "ip" => $ip,
        "loginUser" => $loginUser,
        "loginPassword" => $loginPassword,
        "rootPassword" => $rootPassword,
        "sshPort" => $sshPort,
        "service" => $service,
        "location" => $location,
        "jump" => $jump,
        "other" => $other
      ], [
        "id" => $id
      ])) {
        echo json_encode(array('status' => 'success'));
      }
    }
  }
});
$app->route('POST /api/one/detail/@tag/@id', function($tag, $id){
  include 'GoogleAuthenticator.php';
  if (ctype_digit($tag) && ctype_digit($id) && $tag > 0 && $tag < 4) {
    $privilege = privilege($tag);
    $valid     = true;
    if ( $privilege['view'] ) {
      if ($tag == 1) {
        $secret = '';
      }elseif ($tag == 2) {
        $secret = '';
      }elseif ($tag == 3) {
        $secret = '';
      }
    }else{
      $valid = false;
    }
    if ($valid) {
      $ga = new PHPGangsta_GoogleAuthenticator();
      $livecodeView = $_POST['livecodeView'];
      $oneCode = $ga->getCode($secret);
      if ($livecodeView === $oneCode) {
        global $database;
        $datas = $database->get("password", ['ip', 'loginUser', 'loginPassword', 'rootPassword', 'sshPort', 'jump', 'service', 'location', 'other'], ['id' => $id]);
        $out = array('data' => $datas, 'status' => 'success', 'add' => $privilege['manage']);
        echo json_encode($out);
      }
    }
  }
});
$app->route('POST /api/one/update/@tag/@id', function($tag, $id){
  include 'GoogleAuthenticator.php';
  if (ctype_digit($tag) && ctype_digit($id) && $tag > 0 && $tag < 4) {
    $privilege = privilege($tag);
    $valid     = true;
    if ($privilege['manage']) {
      if ($tag == 1) {
        $secret = '';
      }elseif ($tag == 2) {
        $secret = '';
      }elseif ($tag == 3) {
        $secret = '';
      }
    }else{
      $valid = false;
    }
    if ($valid) {
      $ga = new PHPGangsta_GoogleAuthenticator();
      $livecodeView = $_POST['livecodeView'];
      $oneCode = $ga->getCode($secret);
      if ($livecodeView === $oneCode) {
        global $database;
        $datas = $database->get("password", ['ip', 'loginUser', 'loginPassword', 'rootPassword', 'sshPort', 'jump', 'service', 'location', 'other'], ['id' => $id]);
        $out = array('data' => $datas, 'status' => 'success', 'add' => $privilege['manage']);
        echo json_encode($out);
      }
    }
  }
});
$app->route('POST /api/manage/validate', function(){
  include 'GoogleAuthenticator.php';
  $code = $_POST['code'];
  $secret = 'QHG4A76VR3CKWIQK';
  $ga = new PHPGangsta_GoogleAuthenticator();
  $oneCode = $ga->getCode($secret);
  if ($code == $oneCode) {
    global $database;
    $datas = $database->select('thegroup', ['id', 'gid', 'ip']);
    $group = $out = array();
    foreach ($datas as $d) {
      $group['g'.$d['gid']][] = array('id' => $d['id'], 'ip' => $d['ip']);
    }
    $out['data'] = $group;
    $out['code'] = generateCode($code);
    $out['status'] = 'success';
    echo json_encode($out);
  }
});
$app->route('POST /api/manage/add', function(){
  $code = $_POST['code'];
  $livecode = $_POST['livecode'];
  if($code == generateCode($livecode)){
    $gid     = $_POST['gid'];
    $value   = $_POST['value'];
    $ip      = $_SERVER["REMOTE_ADDR"];
    $addtime = date('Y-m-d H:i:s');
    global $database;
    if ($database->insert('thegroup', [
      'gid' => $gid,
      'ip' => $value,
      'addip' => $ip,
      'addtime' => $addtime
    ])) {
      $id = $database->id();
      echo json_encode(array('data' => array('id' => $id, 'ip' => $value), 'status' => 'success'));
    }else{
      echo json_encode(array('status' => 'failure'));
    }
  }
});
$app->route('POST /api/manage/del/@id', function($id){
  $code = $_POST['code'];
  $livecode = $_POST['livecode'];
  if($code == generateCode($livecode)){
    global $database;
    if ($database->delete('thegroup', ['id' => $id])) {
      echo json_encode(array('status' => 'success'));
    }else{
      echo json_encode(array('status' => 'failure'));
    }
  }
});
$app->start();

function privilege($tag){
  global $database;
  $ip  = $_SERVER["REMOTE_ADDR"];
  $gid = $tag + ($tag - 1);
  $res1 = $database->select('thegroup', ['ip'], ['gid' => $gid]);
  $res2 = $database->select('thegroup', ['ip'], ['gid' => $gid + 1]);
  $view   = 0;
  $manage = 0;
  foreach ($res1 as $r1) {
    if ($r1['ip'] == $ip) {
      $view = 1;
      break;
    }
  }
  foreach ($res2 as $r2) {
    if ($r2['ip'] == $ip) {
      $manage = 1;
      break;
    }
  }
  return array('view' => $view, 'manage' => $manage);
}
function generateCode($secret){
  $ip = $_SERVER["REMOTE_ADDR"];
  $ipa = explode('.', $ip);
  $ips = $ipa[3];
  $length = 0;
  if($ips <= 9){
    $length = $ips;
  }else if($ips > 9 && $ips < 100){
    $length = $ips[0] + $ips[1];
  }else{
    $length = $ips[1] + $ips[2];
  }
  if($length < 5){
    $length = 5;
  }
  if($length > 9){
    $length = 9;
  }
  return substr(md5($secret), $length, $length);
}
?>