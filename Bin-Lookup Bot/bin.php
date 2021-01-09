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
	'text'=>"<b>Hello $firstname, Welcome To The Bin-Lookup Bot

This Bot Uses @NinjaAPIs Bin API to get the information about the bin.

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
/commands - To Show This.
/info -  To Get Info About This Bot.
/zee5 xxxx@xxx.com:xxxxxx - To Check the provided Zee5 Account

Share And Support Us❤️❤️</b>",
	'parse_mode'=>'html',
	'reply_to_message_id'=> $message_id,
	
  ]);
}

////////////////==================////////////////


if(strpos($message, "/bin") === 0){


//////////////////////////////////////////
$bin1 = substr($message, 5);
$bin = substr($bin1, 0, 6);
$binlenth = strlen($bin1);

/////////////////////==========[1st CURL REQ]==========////////////////

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://api.ninja-apis.cf/v1/api/bin?q='.$bin1.'&api_key='.$ninjaapikey.'');
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

$scheme = GetStr($fim, '"scheme":"', '"');
$type = GetStr($fim, '"type":"', '"');
$brand = GetStr($fim, '"brand":"', '"');
$bank = GetStr($fim, '"bank":{"name":"', '"');
$country = GetStr($fim, '"name":"', '"');
$countryemoji = GetStr($fim, '"emoji":"', '"');
$currency = GetStr($fim, '"currency":"', '"');
$bankurl = GetStr($fim, '"url":"', '"');

$schemename = ucfirst("$scheme");
$typename = ucfirst("$type");

/////////////////////==========[Unavailable if empty]==========////////////////


if (empty($schemename)) {
	$schemename = "Unavailable";
}
if (empty($typename)) {
	$typename = "Unavailable";
}
if (empty($brand)) {
	$brand = "Unavailable";
}
if (empty($bank)) {
	$bank = "Unavailable";
}
if (empty($country)) {
	$country = "Unavailable";
}
if (empty($countryemoji)) {
	$countryemoji = "Unavailable";
}
if (empty($currency)) {
	$currency = "Unavailable";
}
if (empty($bankurl)) {
	$bankurl = "Unavailable";
}


/////////////////////==========[Result]==========////////////////


if($binlenth < '6'){ //If Bin Length is Less than 6, Then it is Invalid. Because to get Bin info, it should have minimum 6 Digits -_-
    bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"<b>❌ INVALID BIN LENGTH ❌

Checked By</b> <b>@$username</b>",
	'parse_mode'=>'html',
	'reply_to_message_id'=> $message_id,
	
  ]);
}

/////////////////////////////////////////////////////////////


elseif(strpos($fim, "Invalid Bin.")){ //If No response, Mark it as Invalid Bin

    bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"<b>❌ INVALID BIN ❌

Checked By</b> <b>@$username</b>",
	'parse_mode'=>'html',
	'reply_to_message_id'=> $message_id,
	
  ]);
}}

/////////////////////////////////////////////////////////////


elseif($fim){ //If Response from Bin Lookup Site exists

    bot('sendmessage',[
	'chat_id'=>$chat_id,
	'text'=>"BIN/IIN: <code>$bin</code> $emoji
Card Brand: <b><ins>$schemename</ins></b>
Card Type: <b><ins>$typename</ins></b>
Card Level: <b><ins>$brand</ins></b>
Bank Name: <b><ins>$bank</ins></b>
Country: <b><ins>$country</ins> $countryemoji - 💲<ins>$currency</ins></b>
Issuers Contact: <b><ins>$bankurl</ins></b>
<b>━━━━━━━━━━━━━
Checked By </b><b>@$username</b> (<code>$userId</code>)",
	'parse_mode'=>'html',
	'reply_to_message_id'=> $message_id,
	
  ]);
}


?>
