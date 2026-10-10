<?php
session_start();
require __DIR__ . '/../includes/connection.php';

$name = trim($_POST['name'] ?? '');
$memberId = trim($_POST['member_id'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');

$errors = [];
if ($name === '') {
    $errors[] = "Name is required.";
}
if ($memberId === '') {
    $errors[] = "Member ID is required.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $errors)];
    header('Location: add.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO members (name, member_id, address, phone)
         VALUES (:name, :member_id, :address, :phone)
         RETURNING id"
    );
    $stmt->execute([
        'name' => $name,
        'member_id' => $memberId,
        'address' => $address,
        'phone' => $phone,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Member added successfully.'];
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    // 23505 = PostgreSQL SQLSTATE for unique_violation
    if ($e->getCode() === '23505') {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Member ID is already in use, please use another number.'];
        header('Location: add.php');
        exit;
    }
    throw $e; // other errors should not be hidden
}