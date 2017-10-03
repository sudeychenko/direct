<?php
$ini = parse_ini_file('config.ini');
// отправка запроса $query на сервер. Возвращает массив.
function sendQuery($query, $services, $opt_header=null){
    if (!$query) {return false;}
    $ini = parse_ini_file('config.ini');
    // обязателдьные заголовки
    $headers =  array('Expect:',
        'Authorization: Bearer '.$ini['token'],
        'Content-Type:application/json'
     );
    // Пользовательские заголовки
    if($opt_header){
        foreach ($opt_header as $item=>$value){
             $headers[] =$item.": ".$value;
        }
    }
    // формирование запроса
    $hostname = $ini['url'] . $services;
    $ch = curl_init($hostname);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_REFERER, 'https://' . $hostname);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_exec($ch);
    $info = curl_getinfo($ch);

    $result = curl_multi_getcontent($ch);
    curl_close($ch);
    return array(
        "http_code" => $info['http_code'],
        "result"    => $result,
        "info"      => $info
    );
}

// TSV to Array Function
function tsv_to_array($text) {
    $data = explode("\n", $text);
    $headers = explode("\t",$data[1]);
    array_splice($data, 0,2);
    array_splice($data, -2);
    $result = array();
    foreach ($data as $item){
        $arr = explode("\t", $item);
        $h = 0;
        $result_d = array();
        foreach ($arr as $value){
            $result_d[$headers[$h]] = $value;
            $h++;
        }
        $result[] = $result_d;
    }
    return $result;
}