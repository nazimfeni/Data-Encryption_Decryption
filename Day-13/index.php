<?php

$config = [
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
];

// Generate key
$res = openssl_pkey_new($config);

if ($res === false) {
    // Show OpenSSL errors
    while ($err = openssl_error_string()) {
        echo "OpenSSL Error: $err\n";
    }
    exit;
}

// Export private key
openssl_pkey_export($res, $privateKey);

// Get public key
$details = openssl_pkey_get_details($res);
$publicKey = $details["key"];
// Save to files
file_put_contents("private.pem", $privateKey);
file_put_contents("public.pem", $publicKey);

echo "✅ RSA Keys Generated Successfully!\n";
echo "private.pem and public.pem created.\n";