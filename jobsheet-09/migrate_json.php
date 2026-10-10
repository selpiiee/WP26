<?php
require __DIR__ . '/includes/connection.php';

// 1. Read the JSON file
$file = __DIR__ . '/data/books.json';
if (!file_exists($file)) {
    die("File data/books.json not found.\n");
}

$json = file_get_contents($file);
$json = preg_replace('/^\xEF\xBB\xBF/', '', $json); // remove BOM if present
$data = json_decode($json, true);

if (!is_array($data)) {
    die("books.json is invalid: " . json_last_error_msg() . "\n");
}

// 2. Prepare the INSERT query
$stmt = $pdo->prepare(
    "INSERT INTO books (title, author, year, isbn, stock, category)
     VALUES (:title, :author, :year, :isbn, :stock, :category)"
);

// 3. Insert everything inside one transaction
$pdo->beginTransaction();
try {
    $count = 0;
    foreach ($data as $b) {
        $stmt->execute([
            'title'    => $b['title'],
            'author'   => $b['author'],
            'year'     => (int) $b['year'],
            'isbn'     => $b['isbn'] ?? null,
            'stock'    => (int) ($b['stock'] ?? $b['stok'] ?? 0),
            'category' => $b['category'] ?? $b['kategori'] ?? null,
        ]);
        $count++;
    }
    $pdo->commit();
    echo "Successfully migrated $count books.\n";
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Migration failed, everything was cancelled: " . $e->getMessage() . "\n");
}