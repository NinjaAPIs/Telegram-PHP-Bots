<?php


////////////////=========[✅ SOURCE CODE BY (C)NINJAAPIS ✅]=========////////////////

ob_start();
date_default_timezone_set('Asia/Kolkata');
$date1 = date("Y-m-d");


///////////===[Custom Vars]===///////////

define('API_KEY',''); //TOKEN
$ninjaapikey = ''; //Your API Key

///////////===[Vars From Message]===///////////

$update = json_decode(file_get_contents("php://input"));
$chat_id = $update->message->chat->id;
$userId = $update->message->from->id;
$firstname = $update->message->from->first_name;
$username = $update->message->from->username;
$message = $update->message->text;
$message_id = $update->message->message_id;

///////////===[Callback Query]===///////////

$data = $update->callback_query->data;
$callbackfname = $update->callback_query->from->first_name;
$callbackuname = $update->callback_query->from->username;
$callbackchatid = $update->callback_query->message->chat->id;
$callbackmessageid = $update->callback_query->message->message_id;


///////////===[functions]===///////////

function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

//////////////////////////////////////////////

function SendMessage($chat_id,$text,$keyboard){
	bot('SendMessage',[
	'chat_id'=>$chat_id,
	'text'=>$text,
	'reply_markup'=>$keyboard]);
}

//////////////////////////////////////////////

function edit($chat_id,$meesage_id,$text,$reply_markup){
	bot('editMessageText',[
	'chat_id'=>$chat_id,
	'message_id'=>$message_id,
	'text'=>$text,
	'reply_markup'=>$reply_markup]);
}

//////////////////////////////////////////////

function save($filename, $data)
{
$file = fopen($filename, 'w');
fwrite($file, $data);
fclose($file);
}

//////////////////////////////////////////////

function ForwardMessage($chatid,$from_chat,$message_id){
	bot('ForwardMessage',[
	'chat_id'=>$chatid,
	'from_chat_id'=>$from_chat,
	'message_id'=>$message_id]);
}

function multiexplode($delimiters, $string){
$one = str_replace($delimiters, $delimiters[0], $string);
$two = explode($delimiters[0], $one);
return $two;}

function GetStr($string, $start, $end){
$str = explode($start, $string);
$str = explode($end, $str[1]);  
return $str[0];
};
////////////////=========[START MESSAGE]=========////////////////

if(strpos($message, "/start") === 0){

    bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"<b>Hello $firstname, Welcome To The Mail Validator

This Bot Uses @NinjaAPIs Mail Validator API to validate the mail.

To Know About commands type:
/cmds</b>",
	'parse_mode'=>'html',
	'reply_to_message_id'=> $message_id,
	
  ]);

}


////////////////=========[COMMANDS MESSAGE]=========////////////////

if(strpos($message, "/cmds") === 0){
    bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"<b>My Commands Are As Follows:-

/start - To Restart Bot !!
/cmds - To Show This.
/info -  To Get Info About This Bot.
/mail xxxxx@xxx.com - To check if the provided email is valid.</b>",
	'parse_mode'=>'html',
	'reply_to_message_id'=> $message_id,
	
  ]);
}

////////////////==================////////////////


if(strpos($message, "/mail") === 0){

$email = substr($message, 6);

/////////////////////==========[1st CURL REQ]==========////////////////

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://api.ninja-apis.cf/v1/api/mailvalidator?q='.$email.'&api_key='.$ninjaapikey.'');
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
'Host: api.ninja-apis.cf',
'Sec-Fetch-Dest: document',
'Sec-Fetch-Mode: navigate',
'Sec-Fetch-Site: none',
'Sec-Fetch-User: ?1'
));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$fim = curl_exec($ch);
curl_close($ch); 
$domain = GetStr($fim, '"domain":"', '"');

/////////////////////==========[Result]==========////////////////


if(strpos($fim, '"is_disposable":"true"')){ //Temp Mails

    bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"<b>INVALID EMAIL❌</b> - [ RESPONSE: <b>Disposable Mail.</b> ]

<u>Email:</u> <code>$email</code>
<u>Domain:</u> <b>$domain</b>

<b>━━━━━━━━━━━━━
Checked By </b><b>@$username</b> (<code>$userId</code>)",
	'parse_mode'=>'html',
	'reply_to_message_id'=> $message_id,
	
  ]);
}

/////////////////////////////////////////////////////////////


elseif(strpos($fim, '"is_disposable":"false"')){  //Non-Temp Mail

    bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"<b>VALID EMAIL ✅</b> - [ RESPONSE: <b>Provided Email is Valid.</b> ]

<u>Email:</u> <code>$email</code>
<u>Domain:</u> <b>$domain</b>

<b>━━━━━━━━━━━━━
Checked By </b><b>@$username</b> (<code>$userId</code>)",
	'parse_mode'=>'html',
	'reply_to_message_id'=> $message_id,
	
  ]);
}}


?>
