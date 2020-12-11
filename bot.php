<?php
ini_set('log_errors', 'On');
ini_set('error_log', 'php_errors.log');

include "mysql.php";

ob_start();
$API_KEY = "1316374694:AAFmWamAV-hC0r6o4L33rTf6ft2uslVM6-0";

define('API_KEY',$API_KEY);
function bot($method,$datas=[]){
$url = "https://api.telegram.org/bot".API_KEY."/".$method;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url); curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
$res = curl_exec($ch);
if(curl_error($ch)){
var_dump(curl_error($ch));
}else{return json_decode($res);}}

$update = json_decode(file_get_contents('php://input'));

$message = $update->message;
$text = $message->text;
$uid = $message->chat->id;
$username = $message->from->username;
$first_name = $message->from->first_name;
$date = $message->date;
$msgid = $message->message_id;
#Получаем данные
$users=getUsers('users');
$mess=getmess('messages');
$user_info =  userget($uid);
$user_status = $user_info['status'];

#start
if($text == '/start'){
    $answer = "Добро пожаловать в Сокращатель URL-адресов \nЧтобы сократить ссылку отправьте его мне!";
}
elseif(preg_match('/http[s]?:\/\/[^\s]+/',$text)){
    $get = file_get_contents("https://clck.ru/--?url=$text");
    $answer = "$get\n\nBy @LinkSH_Robot!";
}elseif($text == '/stat' and $user_status == '1'){
    $answer = "Пользователей в боте ".count($users) . "\nБоту написали всего " .count($mess) . " раз";
}else{
    $answer = "Ошибка! Укажите правильный адресс!\nПримеры:\nhttp://google.com \nhttps://google.com";
}

$message_array = array(
    "user_id" => $uid,
    "username" => $username, 
    "first_name" => $first_name, 
    "message_id" => $msgid, 
    "text" => $text, 
    "date" => $date,
    "ndate" =>  gmdate("d.m.Y H:i:s", $date)
); 

$func = ['chat_id'=>$uid, 'text'=>$answer, 'disable_web_page_preview'=>false,]; 
bot(sendmessage, $func);

MessageSave($message_array);
addUser($uid,$username,$first_name);
