<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Log in</title>
        <link href="assets/css/app.css" rel="stylesheet" />
    </head>

    <body class="text-white bg-black">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-[420px] text-center">
                <div class="mb-12 flex justify-center">
                    <div
                        class="w-[100px] h-[100px] bg-red-950/30 rounded-3xl border border-red-900/50 flex items-center justify-center shadow-xl"
                    >
                        <svg
                            class="w-10 h-10 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.745 3.745 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.745 3.745 0 013.296-1.043A3.745 3.745 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.745 3.745 0 013.296 1.043 3.745 3.745 0 011.043 3.296A3.745 3.745 0 0121 12z"
                            ></path>
                        </svg>
                    </div>
                </div>

                <div class="mb-14">
                    <h1 class="text-4xl font-semibold mb-3 tracking-tight">
                        Valor
                    </h1>
                    <p class="text-gray-500 text-lg">
                        Enter your master password
                    </p>
                </div>

                <form action="handle_login.php" method="POST" class="text-left">
                    <div class="mb-4 relative">
                        <label
                            for="password"
                            class="block mb-2.5 text-base font-bold text-white tracking-wide"
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
                            <svg
                                class="w-7 h-7"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                                ></path>
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                ></path>
                            </svg>
                        </button>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-[#751C1A] text-gray-200 text-lg font-bold rounded-xl p-3 hover:bg-red-800 active:bg-red-950 transition-colors shadow-lg shadow-black/30"
                    >
                        Unlock Vault
                    </button>
                </form>
            </div>
        </div>
    </body>
</html>
