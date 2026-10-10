<?php
session_start();
require __DIR__ . '/../includes/connection.php';

$id       = (int) ($_POST['id'] ?? 0);
$title    = trim($_POST['title'] ?? '');
$author   = trim($_POST['author'] ?? '');
$year     = trim($_POST['year'] ?? '');
$isbn     = trim($_POST['isbn'] ?? '');
$stock    = trim($_POST['stock'] ?? '');
$category = trim($_POST['category'] ?? '');

if ($id <= 0) {
    header('Location: list.php');
    exit;
}

$errors = [];
if ($title === '')  { $errors[] = "Title is required."; }
if ($author === '') { $errors[] = "Author is required."; }
if (!is_numeric($year)) { $errors[] = "Year must be a number."; }
if (!is_numeric($stock) || (int) $stock < 0) { $errors[] = "Stock must be 0 or more."; }

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

$stmt = $pdo->prepare(
    "UPDATE books
     SET title = :title, author = :author, year = :year,
         isbn = :isbn, stock = :stock, category = :category
     WHERE id = :id"
);
$stmt->execute([
    'title'    => $title,
    'author'   => $author,
    'year'     => (int) $year,
    'isbn'     => $isbn === '' ? null : $isbn,
    'stock'    => (int) $stock,
    'category' => $category === '' ? null : $category,
    'id'       => $id,
]);

$_SESSION['flash'] = ['type' => 'success', 'message' => 'Book updated successfully.'];
header('Location: list.php');
exit;