<?php
include_once('../src/loggedIn.php');
include_once('../src/db.php');
include_once('../src/functions.php');

$user_id = $_SESSION['user_id'];
$key = $_SESSION['key'];

$categoryStmt = $pdo->prepare("SELECT * FROM categories WHERE user_id = ?");
$categoryStmt->execute([$user_id]);
$categories = $categoryStmt->fetchAll();



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $categoryId = $_POST['category_id'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ? AND user_id = ?");
    $stmt->execute([$categoryId, $user_id]);
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['new_entry'])) {
    $site_name = $_POST['site_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $category_id = Category($pdo, $user_id);

    $result = encryptedPassword($password, $key);

    $stmt = $pdo->prepare("
        INSERT INTO vault_entries (user_id, site_name, username, encrypted_password, iv, auth_tag, category_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $user_id,
        $site_name,
        $username,
        $result['encrypted_password'],
        $result['iv'],
        $result['auth_tag'],
        $category_id
    ]);
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['update_entry'])) {
    $site_name = $_POST['site_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $category_id = Category($pdo, $user_id);
    $id = $_POST['entry_id'];

    $result = encryptedPassword($password, $key);

    $stmt = $pdo->prepare("
        UPDATE vault_entries 
        SET site_name = ?, username = ?, encrypted_password = ?, iv = ?, auth_tag = ?, category_id = ?
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([
        $site_name,
        $username,
        $result['encrypted_password'],
        $result['iv'],
        $result['auth_tag'],
        $category_id,
        $id,
        $user_id
    ]);
    $deleteEmptyCategories = $pdo->prepare("
    DELETE FROM categories
    WHERE user_id = ?
    AND id NOT IN (
        SELECT DISTINCT category_id
        FROM vault_entries
        WHERE category_id IS NOT NULL
    )
");

    $deleteEmptyCategories->execute([$user_id]);
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['delete_entry'])) {
    $id = $_POST['entry_id'];
    $stmt = $pdo->prepare("DELETE FROM vault_entries WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    $deleteEmptyCategories = $pdo->prepare("
    DELETE FROM categories
    WHERE user_id = ?
    AND id NOT IN (
        SELECT DISTINCT category_id
        FROM vault_entries
        WHERE category_id IS NOT NULL
    )
");

    $deleteEmptyCategories->execute([$user_id]);
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("
    SELECT vault_entries.*, categories.name as category_name
    FROM vault_entries
    LEFT JOIN categories ON categories.id = vault_entries.category_id
    WHERE vault_entries.user_id = ?
");
$stmt->execute([$user_id]);
$rows = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Valor</title>
    <link href="assets/css/app.css" rel="stylesheet" />
</head>

<body class="bg-black">

    <nav class="bg-body flex flex-row items-center justify-between px-6 py-3 border-b border-mist-800">
        <div class="flex flex-row items-center gap-3">
            <div class="w-12 h-12 bg-red-950/30 rounded-xl border border-red-900/50 flex items-center justify-center">
                <svg class="text-red-text" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <h1 class="text-white font-semibold text-md"><a class="text-red-text font-bold">V</a>alor</h1>
        </div>

        <div class="w-1/2 mx-8">
            <form class="bg-text-body border border-neutral-800 rounded-md">
                <label class="flex flex-row items-center m-3 gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input id="search" class="w-full bg-transparent text-white outline-none focus:ring-0 placeholder:text-neutral-600 text-sm" placeholder="Search" />
                </label>
            </form>
        </div>

        <div class="flex items-center text-white cursor-pointer hover:text-red-text transition-colors">
            <a href="../src/logout.php" class="flex flex-row items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                </svg>
                <p class="font-semibold text-md">Logout</p>
            </a>
        </div>
    </nav>

    <div class="notification flex justify-center"></div>

    <div class="flex">
        <!--sidebar -->
        <div class="flex flex-col w-[20%] border-r border-mist-800 bg-body min-h-screen text-white">
            <div class="p-4">
                <h2 class="text-lg font-semibold mb-4">Categories</h2>
                <ul class="flex flex-col gap-1">
                    <li>
                        <button class="categoryBtn w-full text-left px-3 py-2 rounded-lg hover:bg-text-body transition-colors text-gray-400 hover:text-white" data-category="all">
                            All
                        </button>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                        <li class="flex items-center gap-1">
                            <button class="categoryBtn flex-1 text-left px-3 py-2 rounded-lg hover:bg-text-body transition-colors text-gray-400 hover:text-white" data-category="<?= $cat['id'] ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </button>
                            <form method="POST" action="">
                                <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
                                <button type="submit" name="delete_category" class="text-gray-600 hover:text-red-500 transition-colors px-2 py-1 text-xs">✕</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- cards -->
        <div class="flex w-[80%] content-start flex-wrap text-white">
            <?php foreach ($rows as $row):
                $decrypted = decryptingPassword($row['encrypted_password'], $key, $row['iv'], $row['auth_tag']);
            ?>
                <div class="entryCard bg-body border rounded-md hover:border-red-text h-[200px] w-[400px] p-4 m-6 cursor-pointer"
                    data-id="<?= $row['id'] ?>"
                    data-site="<?= htmlspecialchars($row['site_name']) ?>"
                    data-username="<?= htmlspecialchars($row['username']) ?>"
                    data-password="<?= htmlspecialchars($decrypted) ?>"
                    data-category="<?= $row['category_id'] ?? '' ?>">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-white font-bold"><?= htmlspecialchars($row['site_name']) ?></p>
                        <?php if ($row['category_name']): ?>
                            <span class="text-xs text-gray-500 border border-neutral-800 rounded-full px-2 py-0.5">
                                <?= htmlspecialchars($row['category_name']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <p><?= htmlspecialchars($row['username']) ?></p>
                    <div class="passwordContainer flex flex-row mt-2 items-center">
                        <input value="<?= htmlspecialchars($decrypted) ?>" type="password"
                            class="passwordInput w-full bg-text-body rounded-sm text-white outline-none focus:ring-0" disabled>
                        <div class="flex items-center ml-2">
                            <button class="passBtn" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                            <button class="copyBtn" type="button" data-password="<?= htmlspecialchars($decrypted) ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- edit modal -->
    <div class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" id="pass-modal">
        <div class="bg-body border border-neutral-800 rounded-xl shadow-xl w-full max-w-md p-6 text-white">
            <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-500">
                <h3 class="text-lg font-semibold">Edit Entry</h3>
                <button type="button" id="close-edit-modal" class="text-gray-500 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="" class="flex flex-col gap-4">
                <input type="hidden" name="entry_id" id="edit-entry-id">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Site name</label>
                    <input required type="text" name="site_name" id="edit-site-name"
                        class="w-full bg-text-body border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Username</label>
                    <input required type="text" name="username" id="edit-username"
                        class="w-full bg-text-body border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Password</label>
                    <div class="flex items-center gap-2">
                        <input required type="text" name="password" id="edit-password"
                            class="w-full bg-text-body border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors">
                        <button type="button" class="generatePass bg-text-body border border-neutral-800 p-2.5 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Category</label>
                    <select name="category_id" id="edit-category"
                        class="w-full bg-text-body border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors mb-2">
                        <option value="">No category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="new_category_name" placeholder="Create new category"
                        class="w-full bg-text-body border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors placeholder:text-neutral-600 text-sm">
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" name="update_entry" class="w-full bg-red-text text-white font-semibold rounded-lg p-2.5 transition-colors mt-2">
                        Save Changes
                    </button>
                    <button type="submit" name="delete_entry" class="w-full bg-text-body text-white font-semibold rounded-lg p-2.5 transition-colors mt-2">
                        Delete Entry
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- modal button -->
    <button id="open-modal" type="button"
        class="fixed bottom-8 right-8 w-14 h-14 bg-red-text hover:scale-110 transition-transform rounded-full flex items-center justify-center shadow-lg cursor-pointer z-50">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-7 h-7 text-white">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </button>

    <div id="modal-backdrop" class="hidden fixed inset-0 bg-black/60 z-40"></div>

    <!-- new entry modal -->
    <div id="vault-item" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-body border border-neutral-800 rounded-xl shadow-xl w-full max-w-md p-6 text-white">
            <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-500">
                <h3 class="text-lg font-semibold">New Vault Entry</h3>
                <button id="close-modal" type="button" class="text-gray-500 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="" class="flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Site name</label>
                    <input required type="text" name="site_name"
                        class="w-full bg-text-body border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors placeholder:text-neutral-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Username</label>
                    <div class="flex gap-2">
                        <input required type="text" name="username" id="generated-username"
                            class="w-full bg-text-body border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors placeholder:text-neutral-600">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Password</label>
                    <div class="flex items-center gap-2">
                        <input required type="text" name="password" id="generated-password"
                            class="w-full bg-text-body border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors placeholder:text-neutral-600">
                        <button type="button" class="generatePass bg-text-body border border-neutral-800 p-2.5 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Category</label>
                    <select name="category_id" id="new-category"
                        class="w-full bg-text-body border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors mb-2">
                        <option value="">No category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="new_category_name" placeholder="Create new category"
                        class="w-full bg-text-body border border-neutral-800 text-white rounded-lg p-2.5 outline-none focus:border-red-800 transition-colors placeholder:text-neutral-600 text-sm">
                </div>
                <button type="submit" name="new_entry"
                    class="w-full bg-red-text hover:bg-red-800 text-white font-semibold rounded-lg p-2.5 transition-colors mt-2">
                    Save Entry
                </button>
            </form>
        </div>
    </div>

    <script type="module" src="assets/js/main.js"></script>
</body>

</html>