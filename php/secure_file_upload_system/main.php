<?php

$maxSize = 2 * 1024 * 1024;

$allowed = [
    "jpg" => "image/jpeg",
    "png" => "image/png",
    "pdf" => "application/pdf"
];

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_FILES["uploaded_file"])) {

        $message = "No file selected.";

    } else {

        $file = $_FILES["uploaded_file"];

        if ($file["error"] !== UPLOAD_ERR_OK) {

            $message = "File upload failed.";

        } elseif ($file["size"] > $maxSize) {

            $message = "File size is too large.";

        } else {

            $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

            if (!array_key_exists($ext, $allowed)) {

                $message = "File extension is not allowed.";

            } else {

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $file["tmp_name"]);
                finfo_close($finfo);

                if ($mime !== $allowed[$ext]) {

                    $message = "File content does not match its extension.";

                } else {

                    $name = bin2hex(random_bytes(16)) . "." . $ext;

                    $dir = __DIR__ . "/uploads/";

                    if (!is_dir($dir)) {
                        mkdir($dir, 0750, true);
                    }

                    if (move_uploaded_file($file["tmp_name"], $dir . $name)) {

                        chmod($dir . $name, 0640);

                        $message = "File uploaded successfully.";

                    } else {

                        $message = "Unable to save the file.";

                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Secure File Upload</title>
</head>

<body>

    <h2>Secure File Upload</h2>

    <form method="POST" enctype="multipart/form-data">

        <input type="file" name="uploaded_file" required>

        <br><br>

        <button type="submit">Upload File</button>

    </form>

    <br>

    <?php if ($message != ""): ?>

        <p><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>

    <?php endif; ?>

</body>

</html>
