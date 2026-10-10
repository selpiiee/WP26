<?php
$page_title = "Edit Member";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/connection.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM members WHERE id = :id");
$stmt->execute(['id' => $id]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$member) {
    header('Location: list.php');
    exit;
}
?>
<section>
    <h2>Edit Member</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></p>
    <?php endif; ?>

    <form method="post" action="process_edit.php">
        <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
        <p>
            <label for="name">Name</label><br>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" required>
        </p>
        <p>
            <label for="member_id">Member ID</label><br>
            <input type="text" id="member_id" name="member_id" value="<?php echo htmlspecialchars($member['member_id']); ?>" required>
        </p>
        <p>
            <label for="address">Address</label><br>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($member['address'] ?? ''); ?>">
        </p>
        <p>
            <label for="phone">Phone</label><br>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($member['phone'] ?? ''); ?>">
        </p>
        <button type="submit">Save Changes</button>
        <a href="list.php">Cancel</a>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>