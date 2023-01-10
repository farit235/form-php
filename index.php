<?php

header('Access-Control-Allow-Origin: *');

// Токен телеграм бота
$tg_bot_token = "5018454472:AAFP0krVRniRXWDL_xsz5rGqxoYov1aUulM";
// ID Чата
$chat_id = "-665698473";

$text = '';

$text .= "\n" . $_SERVER['REMOTE_ADDR'];
$text .= "\n" . date('d.m.y H:i:s');
$text .= "\n" . "Заявка на обслуживание!\n\n";

foreach ($_POST as $key => $val) {
    $text .= $key . ": " . $val . "\n";
}


$param = [
    "chat_id" => $chat_id,
    "text" => $text
];

$url = "https://api.telegram.org/bot" . $tg_bot_token . "/sendMessage?" . http_build_query($param);

var_dump($text); //Выводит информацию о переменной

file_get_contents($url); //озвращает содержимое файла в строке, начиная с указанного смещения

foreach ( $_FILES as $file ) {

    $url = "https://api.telegram.org/bot" . $tg_bot_token . "/sendDocument";

    move_uploaded_file($file['tmp_name'], $file['name']); //Эта функция проверяет, является ли файл filename загруженным на сервер (переданным по протоколу HTTP POST). Если файл действительно загружен на сервер, он будет перемещён в место, указанное в аргументе destination.

    $document = new \CURLFile($file['name']); //могут быть использованы для загрузки файла 

    $ch = curl_init(); //создает новый сеанс CURL

    curl_setopt($ch, CURLOPT_URL, $url); //Устанавливает параметр для сеанса CURL
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ["chat_id" => $chat_id, "document" => $document]);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $out = curl_exec($ch); //Выполняет запрос cURL

    curl_close($ch); //Завершает сеанс cURL

    unlink($file['name']); //Удаляет файл
}

die('1');
