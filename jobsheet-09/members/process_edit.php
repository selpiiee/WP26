<?php
session_start();
require __DIR__ . '/../includes/connection.php';

$id = (int) ($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$memberId = trim($_POST['member_id'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if ($id <= 0) {
    header('Location: list.php');
    exit;
}

$errors = [];
if ($name === '') {
    $errors[] = "Name is required.";
}
if ($memberId === '') {
    $errors[] = "Member ID is required.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

try {
    $stmt = $pdo->prepare(
        "UPDATE members
         SET name = :name, member_id = :member_id, address = :address, phone = :phone
         WHERE id = :id"
    );
    $stmt->execute([
        'name' => $name,
        'member_id' => $memberId,
        'address' => $address,
        'phone' => $phone,
        'id' => $id,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Member updated successfully.'];
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    // 23505 = unique_violation (member_id already used by another member)
    if ($e->getCode() === '23505') {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Member ID is already in use, please use another number.'];
        header('Location: edit.php?id=' . urlencode($id));
        exit;
    }
    throw $e;
}