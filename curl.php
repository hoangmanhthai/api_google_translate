<?php
$text = $_GET['text']; // nội dung cần dịch
$from = $_GET['from']; // dịch từ ngôn ngữ nào
$to = $_GET['to']; // dịch thành ngôn ngữ nào
function translate($text,$from,$to){
    $url = "http://translate.google.com/translate_a/single?client=gtx&dt=t&ie=UTF-8&oe=UTF-8&sl=$from&tl=$to&q=". urlencode($text);
    set_time_limit(0);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS,20);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 40);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    curl_close($ch);
        $result = json_decode($result);
    if(!empty($result)){
    foreach($result[0] as $k){
        $v[] = $k[0];
    }
    return implode(" ", $v);
    }
}
$translate=translate($text,$from,$to);
if($translate){
$info=$translate;
}else{
$info="https://apidich.alwaysdata.net/api.php?text=hello&from=en&to=vi";
}
echo $info;