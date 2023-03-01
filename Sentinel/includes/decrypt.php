<?php
function decryptData($encryptedData, $key)
    {
        // First decode the base 64 encryption 
        $decoded = base64_decode($encryptedData);
        // pull out the iv length in bytes dependant on the encryption algorithim (16 bytes)
        $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CTR");
        //Because the IV is appened to the ciphertext i need to extract the IV first using the substring method
        $iv = substr($decoded, 0, $ivlen); 
        // Extract the ciphertext from the decoded data
        $ciphertext = substr($decoded, $ivlen);
        // Decrypt the data using OpenSSL
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