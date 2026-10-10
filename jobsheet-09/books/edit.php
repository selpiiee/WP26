<?php
$page_title = "Edit Book";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/connection.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
$stmt->execute(['id' => $id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$book) {
    header('Location: list.php');
    exit;
}
?>
<section>
    <h2>Edit Book</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></p>
    <?php endif; ?>

    <form method="post" action="process_edit.php">
        <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
        <p>
            <label for="title">Title</label><br>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        </p>
        <p>
            <label for="author">Author</label><br>
            <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
        </p>
        <p>
            <label for="year">Year</label><br>
            <input type="number" id="year" name="year" value="<?php echo $book['year']; ?>" required>
        </p>
        <p>
            <label for="isbn">ISBN</label><br>
            <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book['isbn'] ?? ''); ?>">
        </p>
        <p>
            <label for="stock">Stock</label><br>
            <input type="number" id="stock" name="stock" min="0" value="<?php echo $book['stock']; ?>" required>
        </p>
        <p>
            <label for="category">Category</label><br>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($book['category'] ?? ''); ?>">
        </p>
        <button type="submit">Save Changes</button>
        <a href="list.php">Cancel</a>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>