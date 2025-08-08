<?php
require_once('../config/db.php');

// Get pet by ID from QR/barcode
$unique_id = $_GET['id'] ?? null;
if (!$unique_id) { die("Missing pet ID."); }

$stmt = $pdo->prepare("SELECT p.*, o.first_name, o.last_name FROM pets p JOIN owners o ON p.owner_id = o.id WHERE p.unique_id = ?");
$stmt->execute([$unique_id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    die("Pet not found.");
}
?>
<h3>Visit Entry - <?= htmlspecialchars($pet['pet_name']) ?> (<?= $unique_id ?>)</h3>
<form action="../scripts/save_visit.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="unique_id" value="<?= $unique_id ?>">
    <label>Remarks: <textarea name="remarks" required></textarea></label><br>
    <label>Prescription Image: <input type="file" name="prescription_image" required></label><br>
    <label>Next Visit Date: <input type="date" name="next_visit"></label><br>
    <input type="submit" value="Save Visit">
</form>
