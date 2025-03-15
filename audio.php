<?php
header('Access-Control-Allow-Origin: *');

function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if(isset($_FILES["audio"])){
    $fileName = generateRandomString();
    if(file_exists("audios/" . $fileName)) unlink("audios/" . $fileName);
    $data = file_get_contents($_FILES['audio']['tmp_name']);    
    $fp = fopen("audios/" . $fileName . '.mp3', 'wb');

    fwrite($fp, $data);
    fclose($fp);

    echo $fileName;
}
?>