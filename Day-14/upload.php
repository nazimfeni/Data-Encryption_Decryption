<?php
    include 'config.php';

    if (isset($_POST['upload'])) {

    // Folder Path
    $dir = "files";

    // Create folder if not exists
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    // Check file upload
    if (! isset($_FILES['file']) || $_FILES['file']['error'] !== 0) {
        die("❌ File upload failed!");
    }

    // Original file info
    $originalName = $_FILES['file']['name'];
    $tmpFile      = $_FILES['file']['tmp_name'];

    // Get file extension
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);

    // Read file data
    $data = file_get_contents($tmpFile);

    if ($data === false) {
        die("❌ Cannot read file!");
    }

    // Generate IV (16 bytes for AES)
    $iv = openssl_random_pseudo_bytes(16);

    // Encrypt
    $encrypted = openssl_encrypt(
        $data,
        METHOD,
        SECRET_KEY,
        0,
        $iv
    );

    if ($encrypted === false) {
        die("❌ Encryption failed!");
    }

    // Combine IV + Encrypted data
    $finalData = base64_encode($iv . $encrypted);

    // Generate secure filename (keep extension)
    $uniqueName = uniqid("lock_", true);
    $filename   = $uniqueName . "." . $extension . ".lock";

    // Full path
    $path = $dir . "/" . $filename;

    // Save encrypted file
    if (file_put_contents($path, $finalData)) {

        echo "✅ File Locked Successfully 🔒<br>";
        echo "📁 Saved In: <b>$path</b><br>";
        echo "📄 Original Name: <b>$originalName</b><br>";
        echo "🔐 Encrypted Name: <b>$filename</b>";

    } else {

        echo "❌ Failed to save encrypted file!";
    }
    }
?>

<form method="post" enctype="multipart/form-data">
      <input type="file" name="file" required>
      <button name="upload">Lock File</button>
</form>
