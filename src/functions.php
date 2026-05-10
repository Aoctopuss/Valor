<?php

function encryptedPassword(string $plaintext, string $key): array
{
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

function decryptingPassword(string $encrypted, string $key, string $iv, string $tag): string|false
{

    return openssl_decrypt(
        $encrypted,
        'aes-256-gcm',
        $key,
        OPENSSL_RAW_DATA,
        $iv,
        $tag
    );
}

function Category(PDO $pdo, int $userId): ?int {
    if (isset($_POST['new_category_name'])) {
        $newName = trim($_POST['new_category_name']);

        if ($newName !== '') {
            $check = $pdo->prepare("SELECT id FROM categories WHERE user_id = ? AND name = ?");
            $check->execute([$userId, $newName]);
            $existing = $check->fetch();

            if ($existing) {
                return (int) $existing['id'];
            }

            $stmt = $pdo->prepare("INSERT INTO categories (user_id, name) VALUES (?, ?)");
            $stmt->execute([$userId, $newName]);
            return (int) $pdo->lastInsertId();
        }
    }

    if (isset($_POST['category_id']) && $_POST['category_id'] !== '') {
        return (int) $_POST['category_id'];
    }

    return null;
}

function passwordValidation(string $site_name, string $username, string $password): ?string {
    if (empty(trim($site_name)) || empty(trim($username)) || empty(trim($password))) {
        return "Velden mogen niet leeg zijn!";
    }
    return null;
}
