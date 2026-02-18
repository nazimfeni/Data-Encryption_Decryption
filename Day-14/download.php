<?php
include 'config.php';

if (isset($_POST['unlock'])) {


    // Check file selected
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== 0) {
        die("❌ Please select a valid file!");
    }

    // Uploaded encrypted file info
    $encryptedFile = $_FILES['file']['tmp_name'];
    $originalName  = $_FILES['file']['name'];

    // Remove .lock from name
    $downloadName = str_replace(".lock", "", $originalName);

    // Read encrypted data
    $data = file_get_contents($encryptedFile);

    if ($data === false) {
        die("❌ Cannot read encrypted file!");
    }

    // Decode base64
    $data = base64_decode($data);

    if ($data === false) {
        die("❌ Invalid encrypted file!");
    }

    // Extract IV (first 16 bytes)
    $iv = substr($data, 0, 16);

    // Get encrypted content
    $encrypted = substr($data, 16);

    // Decrypt
    $decrypted = openssl_decrypt(
        $encrypted,
        METHOD,
        SECRET_KEY,
        0,
        $iv
    );

    if ($decrypted === false) {
        die("❌ Wrong key or corrupted file!");
    }

    // Send file to browser
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$downloadName\"");
    header("Content-Length: " . strlen($decrypted));

    echo $decrypted;
    exit;
}
?>

<form method="post" enctype="multipart/form-data">

      <input type="file" name="file" required>

      <button name="unlock">Unlock File</button>

</form>