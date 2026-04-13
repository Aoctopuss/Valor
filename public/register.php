<?php 
include_once('../src/db.php');

session_start();

$created = null;

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $loginHash = password_hash($password, PASSWORD_ARGON2ID);
    $encryptionSalt = random_bytes(16);

    try {
        $sql = "INSERT INTO users (username, password_hash, encrypted_salt) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);


        $stmt->execute([$username, $loginHash, $encryptionSalt]);
        $created = true;
    } catch (PDOException $e) {
        error_log($e -> getMessage());
        $created = false;
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
            <div class="w-full max-w-[420px] text-center bg-[#0c0c0c] p-8 rounded-lg">
                <div class="mb-2 flex justify-center">
                    <div
                        class="w-[100px] h-[100px] bg-red-950/30 rounded-3xl border border-red-900/50 flex items-center justify-center shadow-xl"
                    >
                        <div
                            class="w-[100px] h-[100px] bg-red-950/30 rounded-3xl border border-red-900/50 flex items-center justify-center shadow-xl"
                        >
                            <svg
                                class="w-full h-full text-[#991a18]"
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
                        <a class="text-[#991a18]">V</a>alor
                    </h1>
                    <p class="text-gray-500 text-lg font-semibold">
                        Sign up for <a class="text-[#991a18]">V</a><a class="">alor</a>
                    </p>
                </div>

                <form method="POST" class="text-left">
                    <div class="mb-4 relative">
                        <label
                            for="username"
                            class="block mb-1 text-base font-bold text-white tracking-wide"
                        >
                            Username
                        </label>
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
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="w-full bg-[#181818] text-white text-lg rounded-xl border border-neutral-800 p-3 pr-14 focus:ring-2 focus:ring-red-700 focus:border-red-700 transition-all placeholder:text-neutral-700"
                            placeholder="•••••••••••••••••••••"
                            required
                        />
                        <button
                            type="button"
                            class="absolute right-5 bottom-5 text-gray-600 hover:text-white transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                                <path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd" />
                            </svg>

                        </button>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-[#991a18] text-gray-200 text-lg font-bold rounded-xl p-3 hover:bg-red-950 active:bg-red-950 transition-colors shadow-lg shadow-black/30"
                    >
                        Create account
                    </button>
                </form>
            </div>       
            <p class="mt-10 text-white">Already have a account? <a href="login.php" class="text-sky-300 font-semibold underline">Log in</a></p>
        </div>
    </body>
</html>
