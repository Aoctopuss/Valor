<?php 
session_start();
include_once('../src/db.php');

$error = null;
if (isset($_POST['username']) && isset($_POST['password'])) {

    // usertable registration
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);

    // authentication
    if ($stmt->fetch()) {
        $error = ("username already exists!");
    } else {
        $password_hash = password_hash($password, PASSWORD_ARGON2ID);
        
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $stmt->execute([$username, $password_hash]);
        $userId = $pdo->lastInsertId();

        $kdf_salt = random_bytes(16);
        $stmt = $pdo->prepare("INSERT INTO user_keys (user_id, kdf_salt) VALUES (?, ?)");
        $stmt->execute([$userId, $kdf_salt]);
        $key = hash_pbkdf2('sha256', $password, $kdf_salt, 600000, 32, true);
        $_SESSION['user_id'] = $userId;
        $_SESSION['key'] = $key;
        header("Location: index.php");
        exit();
    }

}

?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Log in</title>
        <link href="assets/css/app.css" rel="stylesheet" />
    </head>

    <body class="text-white bg-black">
        <div class="min-h-screen flex items-center justify-center text-center flex-col">
            <div class="w-full max-w-[420px] text-center bg-body p-8 rounded-lg">
                <div class="mb-2 flex justify-center">
                    <div
                        class="w-[100px] h-[100px] bg-red-950/30 rounded-3xl border border-red-900/50 flex items-center justify-center shadow-xl"
                    >
                        <div
                            class="w-[100px] h-[100px] bg-red-950/30 rounded-3xl border border-red-900/50 flex items-center justify-center shadow-xl"
                        >
                            <svg
                                class="w-full h-full text-red-text"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"
                                ></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h1 class="text-4xl font-semibold tracking-tight mb-6">
                        <a class="text-red-text">V</a>alor
                    </h1>
                    <p class="text-gray-500 text-lg font-semibold">
                        Sign up for <a class="text-red-text">V</a><a class="">alor</a>
                    </p>
                </div>

                <form method="POST" class="text-left">
                    <div class="mb-4 relative d-flex place-content-center">
                        <label
                            for="username"
                            class="block mb-1 text-base font-bold text-white tracking-wide"
                        >
                            Username
                        </label>
                        <?php if ($error): ?>
                            <p class="text-red-500 text-sm"><?= htmlspecialchars($error) ?></p>
                        <?php endif; ?>
                        <input
                            type="text"
                            name="username"
                            id="username"
                            class="mb-4 w-full bg-[#181818] text-white text-lg rounded-xl border border-neutral-800 p-3 pr-14 focus:ring-2 focus:ring-red-700 focus:border-red-700 transition-all placeholder:text-neutral-700"
                            placeholder=""
                            required
                        />
                        <label
                            for="password"
                            class="block mb-1 text-base font-bold text-white tracking-wide"
                        >
                            Master Password
                        </label>
                        <div class="passwordContainer flex items-center">
                            <input
                                type="password"
                                name="password"
                                class="passwordInput w-full bg-[#181818] text-white text-lg rounded-xl border border-neutral-800 p-3 pr-14 focus:ring-2 focus:ring-red-700 focus:border-red-700 transition-all placeholder:text-neutral-700"
                                placeholder="************"
                                required
                            />
                            <button
                                type="button"
                                class="passBtn absolute right-5 bottom-3.8 text-gray-600 hover:text-white transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
    
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-red-text text-gray-200 text-lg font-bold rounded-xl p-3 hover:bg-red-950 active:bg-red-950 transition-colors shadow-lg shadow-black/30"
                    >
                        Register
                    </button>
                </form>
            </div>       
            <p class="mt-10 text-white">Already have a account? <a href="login.php" class="text-sky-300 font-semibold underline">Log in</a></p>
        </div>
        <script type="module" src="assets/js/main.js"></script>
    </body>
</html>
