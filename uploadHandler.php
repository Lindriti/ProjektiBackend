<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
    $fileName = basename($_FILES['uploadedFile']['name']);
    
    // Për të shmangur probleme me emra të file-ve, mund ta "sanitize" emrin:
    $fileName = preg_replace("/[^a-zA-Z0-9\.\-\_]/", "_", $fileName);

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $dest_path = $uploadDir . $fileName;

    // Kontrollo nëse file me të njëjtin emër ekziston dhe nëse po, ndrysho emrin për të shmangur mbishkrimin
    $i = 1;
    $fileParts = pathinfo($fileName);
    while (file_exists($dest_path)) {
        $fileName = $fileParts['filename'] . "_$i." . $fileParts['extension'];
        $dest_path = $uploadDir . $fileName;
        $i++;
    }

    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $uploadedBy = $_SESSION['id'];

        // Shtojmë edhe file_path në databazë
        $sql = "INSERT INTO uploads (file_name, file_path, user_id) VALUES (:file_name, :file_path, :user_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':file_name', $fileName);
        $stmt->bindParam(':file_path', $dest_path);
        $stmt->bindParam(':user_id', $uploadedBy);
        $stmt->execute();

        header("Location: dashboard.php?page=uploads");
        exit;
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "No file uploaded or upload error.";
}
