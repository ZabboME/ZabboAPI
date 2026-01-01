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
    if ($_FILES['audio']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        exit;
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if ($finfo === false) {
        $mime = $_FILES['audio']['type'];
    } else {
        $mime = $finfo->file($_FILES['audio']['tmp_name']);
    }
    $allowedMimes = ['audio/mpeg', 'audio/mp3'];
    if (!in_array($mime, $allowedMimes)) {
        http_response_code(400);
        echo "Invalid file type";
        exit;
    }
    $fileName = generateRandomString();
    $targetPath = "audios/" . $fileName . '.mp3';
    if (!is_dir("audios")) mkdir("audios");
    if (move_uploaded_file($_FILES['audio']['tmp_name'], $targetPath)) {
        echo $fileName;
    } else {
        http_response_code(500);
        echo "Error saving file";
    }
}
?>