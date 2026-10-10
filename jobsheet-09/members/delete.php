<?php
session_start();
require __DIR__ . '/../includes/connection.php';

// POST only, so a link or crawler can't delete data
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM members WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Member deleted successfully.'];
}

header('Location: list.php');
exit;