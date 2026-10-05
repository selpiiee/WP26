<?php
session_start();

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

// ===== EXERCISE 2: Added Validations for Phone and Address =====
if ($phone !== '' && !preg_match('/^[0-9+\s-]+$/', $phone)) {
    $errors[] = "Phone number can only contain digits, spaces, hyphens, and the plus sign (+).";
}
if ($address !== '' && strlen($address) < 5) {
    $errors[] = "Address must be at least 5 characters long.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $errors)];
    header('Location: add.php');
    exit;
}

if (!isset($_SESSION['members'])) {
    $_SESSION['members'] = [];
}

$_SESSION['members'][] = [
    'name' => $name,
    'member_id' => $memberId,
    'address' => $address,
    'phone' => $phone,
];

$_SESSION['flash'] = ['type' => 'success', 'message' => 'Member added successfully.'];
header('Location: list.php');
exit;