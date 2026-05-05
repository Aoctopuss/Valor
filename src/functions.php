<?php 

function encryptedPassword(string $plaintext, string $key): array {
    $iv = random_bytes(12);

    $tag = '';

    $encrypted = openssl_encrypt(
        $plaintext,
        'aes-256-gcm',
        $key,
        OPENSSL_RAW_DATA,
        $iv,
        $tag,
        '',
        16
    );

    return [
        'encrypted_password' => $encrypted,
        'iv' => $iv,
        'auth_tag' => $tag
    ];
}

function decryptingPassword(string $encrypted, string $key, string $iv, string $tag): string|false {

    return openssl_decrypt(
        $encrypted,
        'aes-256-gcm',
        $key, 
        OPENSSL_RAW_DATA,
        $iv,
        $tag
    );
}