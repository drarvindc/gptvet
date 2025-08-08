<?php
require_once('../config/db.php');
$species_id = $_GET['species_id'];
$stmt = $pdo->prepare("SELECT * FROM breeds WHERE species_id = ?");
$stmt->execute([$species_id]);
$breeds = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($breeds as $b) {
    echo "<option value='{$b['name']}'>{$b['name']}</option>";
}
?>