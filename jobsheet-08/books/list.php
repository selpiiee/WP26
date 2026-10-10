<?php
$page_title = "Book List";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/connection.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title ILIKE :keyword ORDER BY id DESC");
    $stmt->execute(['keyword' => '%' . $keyword . '%']);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $books = $pdo->query("SELECT * FROM books ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}

?>
<section>
    <h2>Book List</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></p>
    <?php endif; ?>

    <div class="search-box">
        <label for="search-input">Search Book Title</label>
        <form method="GET" action="list.php">
            <input type="text" id="search-input" name="q" placeholder="Search title..."
                value="<?php echo htmlspecialchars($keyword); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year</th>
                    <th>Stock</th>
                    <th>Date Added</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($books)): ?>
                    <tr>
                        <td colspan="6">No book data found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><?php echo $book['year']; ?></td>
                            <td><?php echo $book['stock']; ?></td>
                            <td><?php echo $book['tanggal_ditambahkan']
                                    ? date('d M Y H:i', strtotime($book['tanggal_ditambahkan']))
                                    : '-'; ?></td>
                            <td>
                                <button type="button">Edit</button>
                                <button type="button" class="btn-delete">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>