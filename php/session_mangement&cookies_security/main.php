<?php

$secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

if (isset($_POST['login'])) {
    session_regenerate_id(true);
    $_SESSION['user'] = $_POST['username'] ?? 'User';
    $_SESSION['login'] = true;
}

if (isset($_POST['logout'])) {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 3600,
            'path' => $params['path'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite']
        ]);
    }

    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['test'])) {
    setcookie('safe_cookie', 'Protected', [
        'expires' => time() + 3600,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    setcookie('normal_cookie', 'Normal', [
        'expires' => time() + 3600,
        'path' => '/',
        'secure' => $secure,
        'httponly' => false,
        'samesite' => 'Lax'
    ]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$safe = isset($_COOKIE['safe_cookie']);
$normal = isset($_COOKIE['normal_cookie']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session & Cookie Security</title>
</head>
<body>

    <h2>Session & Cookie Security</h2>

    <?php if (!empty($_SESSION['login'])): ?>

        <h3>Welcome <?= htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p>Session ID: <?= htmlspecialchars(session_id()) ?></p>

        <form method="POST">
            <button type="submit" name="logout">Logout</button>
        </form>

    <?php else: ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <button type="submit" name="login">Login</button>
        </form>

    <?php endif; ?>

    <hr>

    <form method="POST">
        <button type="submit" name="test">Test Cookies</button>
    </form>

    <h3>Cookie Test</h3>

    <p>Protected Cookie: <?= $safe ? "Found" : "Not Found" ?></p>
    <p>Normal Cookie: <?= $normal ? "Found" : "Not Found" ?></p>

    <h3>JavaScript Test</h3>

    <button onclick="checkCookies()">Check Cookies</button>

    <p id="result"></p>

    <script>
        function checkCookies() {
            let cookies = document.cookie;
            document.getElementById("result").innerText = cookies ? cookies : "لا يوجد كوكيز مرئية لـ JavaScript";
        }
    </script>

</body>
</html>
