<?php

$inputFile  = "sample.enc";        // Encrypted file
$outputFile = "sample_dec.pdf";   // Restored file
$key        = "my_super_secret";  // Same password

// Make 32-byte key
$key = hash("sha256", $key, true);

// Read encrypted file
$data = file_get_contents($inputFile);

// Extract IV (first 16 bytes)
$iv = substr($data, 0, 16);

// Extract encrypted data
$encrypted = substr($data, 16);

// Decrypt
$decrypted = openssl_decrypt(
    $encrypted,
    "AES-256-CBC",
    $key,
    OPENSSL_RAW_DATA,
    $iv
);

// Save restored file
file_put_contents($outputFile, $decrypted);

echo "✅ File Decrypted: $outputFile";