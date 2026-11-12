<?php

$pdo = new PDO("sqlite::memory:");

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT,
    password TEXT
)");

$pdo->exec("
    INSERT INTO users (username, password) VALUES
    ('ahmed', '12345'),
    ('mohammed', '123456'),
    ('ali', 'abc123')
");

$user = $_GET['username'] ?? '';
$mode = $_GET['mode'] ?? 'safe';

$result = [];
$error = '';

try {

    if ($mode == "unsafe") {

        $sql = "SELECT id, username FROM users WHERE username = '$user'";

        $result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    } else {

        $sql = "SELECT id, username FROM users WHERE username = :username";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':username' => $user
        ]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {

    $error = "Query failed";

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Injection Defense</title>
</head>
<body>

    <h2>SQL Injection Defense</h2>

    <form method="GET">

        <label>Username:</label>
        <input type="text" name="username"
               value="<?= htmlspecialchars($user, ENT_QUOTES, 'UTF-8') ?>">

        <br><br>

        <label>Mode:</label>

        <select name="mode">
            <option value="safe" <?= $mode == "safe" ? "selected" : "" ?>>
                Safe
            </option>

            <option value="unsafe" <?= $mode == "unsafe" ? "selected" : "" ?>>
                Unsafe
            </option>
        </select>

        <br><br>

        <button type="submit">Search</button>

    </form>

    <hr>

    <?php if ($error): ?>

        <p><?= htmlspecialchars($error) ?></p>

    <?php elseif (count($result) > 0): ?>

        <table border="1" cellpadding="8">

            <tr>
                <th>ID</th>
                <th>Username</th>
            </tr>

            <?php foreach ($result as $row): ?>

                <tr>
                    <td><?= htmlspecialchars((string)$row['id']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                </tr>

            <?php endforeach; ?>

        </table>

    <?php else: ?>

        <p>No users found.</p>

    <?php endif; ?>

</body>
</html>
