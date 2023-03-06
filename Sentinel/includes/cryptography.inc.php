<?php

// the data is encrypted using AES-256-CTR cipher and then encoded using base64, which obscures the encrypted data
function encryptData($formData, $key)
{
    //  Encrypt using AES 256 -CTR
    // sets the iv length for  AES-256 (CTR) which is 16 (and set the cipher variable)
    $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CTR");
    // Generates a crytographically secure iv of (iv length- in this case 16) size
    $iv = random_bytes($ivlen);
    //Ciphertext encryption  the openssl_encrypt requires the data,encryption method, key, options int and the iv
    $ciphertext = openssl_encrypt(
        $formData, // This is the data that will be encrypted
        $cipher, // The cipher being used which I set in line 8
        $key, // the random key for the encryption
        $options = OPENSSL_RAW_DATA, //specify that the input and output data should be handled as raw binary data
        $iv // the iv which was randomly generated on line 9
    );
    //  Encoded in base64 which will further obscure the encrypted data and also makes sure that there are no characters
    //  that could be intrepreted as special which would end up causing issues when adding it to the database
    return base64_encode($iv . $ciphertext); // Append the iv to the front of the cipher text
}

function decryptData($encryptedData, $key)
{
    // First decode the base 64 encryption and remove it
    $decoded = base64_decode($encryptedData);
    // grab the iv length for the chosen cipher
    $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CTR");
    // Because the IV is appened to the ciphertext I need to extract the IV first using the substring method
    // This will split the string. The decoded represents the data, the 0 is the start of the string and the iv length tells it where to stop
    $iv = substr($decoded, 0, $ivlen);
    // Extract what remains which will be the data that was encrypted actual.
    $ciphertext = substr($decoded, $ivlen);
    // Decrypt the data using openssl_decrypt which does the reverse of the openssl_encrypt
    $plaintext = openssl_decrypt(
        $ciphertext,
        $cipher,
        $key,
        $options = OPENSSL_RAW_DATA,
        $iv
    );
    // aaaaand done.
    // return the pt
    return $plaintext;
}
?>
