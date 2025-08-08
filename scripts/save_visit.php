<?php
require_once('../config/db.php');

// Get pet
$unique_id = $_POST['unique_id'];
$stmt = $pdo->prepare("SELECT id FROM pets WHERE unique_id = ?");
$stmt->execute([$unique_id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$pet) { die("Pet not found."); }

$pet_id = $pet['id'];
$remarks = $_POST['remarks'];
$next_visit = $_POST['next_visit'] ?? null;

// Upload prescription image
$prescription_path = null;
if (!empty($_FILES['prescription_image']['name'])) {
    $ext = pathinfo($_FILES['prescription_image']['name'], PATHINFO_EXTENSION);
    $prescription_path = "../assets/uploads/visit_{$unique_id}." . $ext;
    move_uploaded_file($_FILES['prescription_image']['tmp_name'], $prescription_path);
}

// Save visit
$stmt = $pdo->prepare("INSERT INTO visits (pet_id, remarks, prescription, next_visit) VALUES (?, ?, ?, ?)");
$stmt->execute([$pet_id, $remarks, $prescription_path, $next_visit]);

echo "Visit recorded successfully.";
?>