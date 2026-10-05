<?php
session_start();

$title = trim($_POST['title'] ?? '');
$author = trim($_POST['author'] ?? '');
$year = $_POST['year'] ?? '';
$isbn = trim($_POST['isbn'] ?? '');
$stock = $_POST['stock'] ?? '';
$category = trim($_POST['category'] ?? '');

// Server-side validation
$errors = [];
if ($title === '') {
    $errors[] = "Title is required.";
}
if ($author === '') {
    $errors[] = "Author is required.";
}

// ===== EXERCISE 1: Tambahkan Validasi ISBN di sini =====
if ($isbn !== '' && !preg_match('/^[0-9-]+$/', $isbn)) {
    $errors[] = "ISBN can only contain digits and hyphens (-).";
}

if (!is_numeric($year) || $year < 1900 || $year > 2026) {
    $errors[] = "Year must be between 1900-2026.";
}
if (!is_numeric($stock) || $stock < 0) {
    $errors[] = "Stock cannot be negative.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $errors)];
    header('Location: add.php');
    exit;
}

if (!isset($_SESSION['books'])) {
    $_SESSION['books'] = [];
}

$_SESSION['books'][] = [
    'title' => $title,
    'author' => $author,
    'year' => (int) $year,
    'isbn' => $isbn,
    'stock' => (int) $stock,
    'category' => $category,
];

$_SESSION['flash'] = ['type' => 'success', 'message' => 'Book added successfully.'];
header('Location: list.php');
exit;