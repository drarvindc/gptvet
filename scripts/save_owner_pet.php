<?php
require_once('../config/db.php');
require_once('../phpqrcode/qrlib.php');
require_once('../barcode/vendor/autoload.php');
use Picqer\Barcode\BarcodeGeneratorPNG;

$first_name = $_POST['first_name'];
$middle_name = $_POST['middle_name'];
$last_name = $_POST['last_name'];
$mobile = $_POST['mobile_number'];
$locality = $_POST['locality'];
$pet_name = $_POST['pet_name'];
$species = $_POST['species'];
$breed = $_POST['breed'];
$gender = $_POST['gender'];
$dob = $_POST['dob'] ?? null;
$age_years = $_POST['age_years'] ?? null;
$age_months = $_POST['age_months'] ?? null;
$image_path = null;

// Save owner
$stmt = $pdo->prepare("INSERT INTO owners (first_name, middle_name, last_name, mobile_number, locality) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$first_name, $middle_name, $last_name, $mobile, $locality]);
$owner_id = $pdo->lastInsertId();

// Generate Unique ID
$year = date('y');
$stmt = $pdo->query("SELECT COUNT(*) as total FROM pets WHERE unique_id LIKE '{$year}%'");
$count = $stmt->fetch(PDO::FETCH_ASSOC)['total'] + 1;
$unique_id = $year . str_pad($count, 4, '0', STR_PAD_LEFT);

// Upload pet image
if (!empty($_FILES['pet_image']['name'])) {
    $ext = pathinfo($_FILES['pet_image']['name'], PATHINFO_EXTENSION);
    $image_path = "../assets/uploads/{$unique_id}." . $ext;
    move_uploaded_file($_FILES['pet_image']['tmp_name'], $image_path);
}

// Generate QR
$qr_file = "../assets/uploads/qr_{$unique_id}.png";
QRcode::png($unique_id, $qr_file);

// Generate Barcode
$barcode = new BarcodeGeneratorPNG();
$barcodeData = $barcode->getBarcode($unique_id, $barcode::TYPE_CODE_128);
$barcode_file = "../assets/uploads/barcode_{$unique_id}.png";
file_put_contents($barcode_file, $barcodeData);

// Save pet
$stmt = $pdo->prepare("INSERT INTO pets (owner_id, unique_id, pet_name, species, breed, gender, dob, age_years, age_months, pet_image, qr_code, barcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$owner_id, $unique_id, $pet_name, $species, $breed, $gender, $dob, $age_years, $age_months, $image_path, $qr_file, $barcode_file]);

echo "Saved Successfully with ID: $unique_id";
?>