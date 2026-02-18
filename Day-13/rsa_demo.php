<?php

/*
|--------------------------------------------------------------------------
| STEP 1: Generate RSA Keys (Run Once)
|--------------------------------------------------------------------------
*/

function generateKeys()
{
    $config = [
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ];

    $res = openssl_pkey_new($config);

    if (! $res) {
        die("Key Generation Failed: " . openssl_error_string());
    }

    // Export Private Key
    openssl_pkey_export($res, $privateKey);

    // Get Public Key
    $details   = openssl_pkey_get_details($res);
    $publicKey = $details["key"];

    // Save to files
    file_put_contents("private.pem", $privateKey);
    file_put_contents("public.pem", $publicKey);

    echo "✅ Keys Generated Successfully<br><br>";
}

/*
|--------------------------------------------------------------------------
| STEP 2: Encrypt Using Public Key
|--------------------------------------------------------------------------
*/

function encryptData($data)
{
    $publicKey = file_get_contents("public.pem");

    if (! $publicKey) {
        die("Public key not found");
    }


    openssl_public_encrypt(
        $data,
        $encrypted,
        $publicKey
    );

    return base64_encode($encrypted);
}

/*
|--------------------------------------------------------------------------
| STEP 3: Decrypt Using Private Key
|--------------------------------------------------------------------------
*/

function decryptData($encryptedData)
{
    $privateKey = file_get_contents("private.pem");

    if (! $privateKey) {
        die("Private key not found");
    }

    $encrypted = base64_decode($encryptedData);

    openssl_private_decrypt(
        $encrypted,
        $decrypted,
        $privateKey
    );

    return $decrypted;
}

/*
|--------------------------------------------------------------------------
| MAIN PROGRAM
|--------------------------------------------------------------------------
*/

// Generate keys only if not exist
if (! file_exists("private.pem") || ! file_exists("public.pem")) {
    generateKeys();
}

// Original Message
$message = "Hello Nazim! This is Secret Data";

// Encrypt
$encrypted = encryptData($message);

// Decrypt
$decrypted = decryptData($encrypted);

// Output
echo "Original Message:\n";
echo $message . "\n";

echo "Encrypted Message:\n";
echo $encrypted . "\n";

echo "Decrypted Message:\n";
echo $decrypted . "\n";