<?php

$inputFile  = "sample.pdf";        // File to encrypt
$outputFile = "sample.enc";        // Encrypted file
$key        = "my_super_secret";   // Password

// Make 32-byte key
$key = hash("sha256", $key, true);

// Generate IV
$iv = openssl_random_pseudo_bytes(16);

// Read file
$data = file_get_contents($inputFile);

// Encrypt
$encrypted = openssl_encrypt(
    $data,
    "AES-256-CBC",
    $key,
    OPENSSL_RAW_DATA,
    $iv
);

// Save IV + Encrypted Data
file_put_contents($outputFile, $iv . $encrypted);

echo "✅ File Encrypted: $outputFile";