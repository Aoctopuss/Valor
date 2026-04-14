<?php
session_start();
include_once('../src/db.php');


if (isset($_POST['site_name']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['master_confirm'])) {
    $site_name = $_POST['site_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $master_pass = $_POST['master_confirm'];

    $salt  = $_SESSION['encrypted_salt'];

    $key = hash_pbkdf2("sha256", $master_confirm, $salt, 100000, 32, true);

    $iv = random_bytes(16);


    try {
        $sql = "INSERT INTO passwords (user_id, site_name, encrypted_data, iv, tag) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt ->execute([
            $_SESSION['user_id'],
            $site_name,
            $encrypted_data,
            $iv,
            $tag
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $error = "error failed to save";
    }
}



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link href="assets/css/app.css" rel="stylesheet" />
</head>

<body class="bg-black">


    <nav class="bg-[#0c0c0c] flex flex-row items-center justify-between px-6 py-3 border-b border-mist-800">
        <div class="flex flex-row items-center gap-3">
            <div class="w-12 h-12 bg-red-950/30 rounded-xl border border-red-900/50 flex items-center justify-center">
                <svg class="text-[#991a18]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <h1 class="text-white font-semibold text-md"><a class="text-[#991a18] font-bold">V</a>alor</h1>
        </div>

        <div class="w-1/2 mx-8">
            <form class="bg-[#151516] border border-neutral-800 rounded-md">
                <label class="flex flex-row items-center m-3 gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 text-gray-500 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input
                        class="w-full bg-transparent text-white outline-none focus:ring-0 placeholder:text-neutral-600 text-sm"
                        placeholder="Search vault..." />
                </label>
            </form>
        </div>

        <div class="flex items-center text-white cursor-pointer hover:text-[#991a18] transition-colors">
            <a href="../src/logout.php" class="flex flex-row items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                </svg>
                <p class="font-semibold text-md">Logout</p>
            </a>
        </div>
    </nav>


    <div class="flex">

        <div
            class="flex flex-col justify-items-start w-[20%] border-r border-mist-800 bg-[#0c0c0c] min-h-screen text-white">
            <h1>hi</h1>
            <h1>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima cum error iste adipisci molestiae ut!
                Ad cumque voluptatem ipsum veritatis cupiditate, perferendis corrupti obcaecati laborum iste dicta
                assumenda, suscipit reprehenderit.</h1>
        </div>

        <div class="flex w-[80%] justify-self-auto content-start flex-wrap text-white">
            <div class="bg-[#0c0c0c] border rounded-md h-[200px] w-[350px] p-4 m-6">
                <p class="text-white font-bold">Github</p>
                <p>son.grandsom@sigma.li</p>
                <div class="flex flex-row mt-2 items-center">
                    <input id="passwordChange" placeholder="************" type="password"
                        class="w-full bg-[#151516] rounded-sm text-white outline-none focus:ring-0">
                    <div>
                        <button id="switch">
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
            </div>
        </div>
    </div>

    <div>
        <button id="open-modal" type="button"
            class="fixed bottom-8 right-8 w-14 h-14 bg-[#991a18] hover:scale-110 transition-transform rounded-full flex items-center justify-center shadow-lg cursor-pointer z-50">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                stroke="currentColor" class="w-7 h-7 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </button>


        <div id="modal-backdrop" class="hidden fixed inset-0 bg-black/60 z-50"></div>


        <div id="vault-item" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="bg-[#0c0c0c] border border-neutral-800 rounded-xl shadow-xl w-full max-w-md p-6 text-white">

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold">New Vault Entry</h3>
                    <button id="close-modal" class="text-gray-500 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="" class="flex flex-col gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1.5">Site name</label>
                        <input type="text" name="site_name" placeholder="e.g. Github"
                            class="w-full bg-[#151516] border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors placeholder:text-neutral-600">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1.5">Username</label>
                        <div class="flex gap-2">
                            <input type="text" name="username" id="generated-username" placeholder="e.g. shadowwolf482"
                                class="w-full bg-[#151516] border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors placeholder:text-neutral-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1.5">Password</label>
                        <div class="flex gap-2">
                            <input type="password" name="password" id="generated-password"
                                placeholder="••••••••••••••••"
                                class="w-full bg-[#151516] border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors placeholder:text-neutral-600">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1.5">Confirm Master Passwor</label>
                        <div class="flex gap-2">
                            <input type="password" name="master_confirm" id="generated-password"
                                placeholder="••••••••••••••••"
                                class="w-full bg-[#151516] border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors placeholder:text-neutral-600">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#991a18] hover:bg-red-800 text-white font-semibold rounded-lg p-2.5 transition-colors mt-2">
                        Save Entry
                    </button>

                </form>
            </div>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
</body>

</html>