<?php include '../includes/header.php'; ?>
<?php
require_once('../config/db.php');
$mode = $_GET['mode'] ?? 'admin';

$speciesStmt = $pdo->query("SELECT * FROM species");
$species = $speciesStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<form method="POST" action="../scripts/save_owner_pet.php" enctype="multipart/form-data">
    <label>Owner First Name: <input type="text" name="first_name" required></label><br>
    <label>Owner Middle Name: <input type="text" name="middle_name"></label><br>
    <label>Owner Last Name: <input type="text" name="last_name" required></label><br>
    <label>Mobile Number: <input type="text" name="mobile_number" required></label><br>
    <label>Locality: <input type="text" name="locality"></label><br>
    <hr>
    <label>Pet Name: <input type="text" name="pet_name" required></label><br>
    <label>Species:
        <select name="species" id="species" required>
            <option value="">--Select--</option>
            <?php foreach($species as $s): ?>
                <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Breed:
        <select name="breed" id="breed" required>
            <option value="">--Select--</option>
        </select>
    </label><br>
    <label>Gender:
        <select name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </label><br>
    <label>DOB: <input type="date" name="dob"></label><br>
    <label>Age in Years: <input type="number" name="age_years" min="0" max="20"></label>
    <label>Age in Months: <input type="number" step="0.5" name="age_months" min="0" max="12"></label><br>
    <label>Pet Image: <input type="file" name="pet_image"></label><br>
    <input type="submit" value="Save">
</form>
<script>
document.getElementById("species").addEventListener("change", function() {
    var speciesId = this.value;
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../scripts/get_breeds.php?species_id=" + speciesId, true);
    xhr.onload = function() {
        if (this.status == 200) {
            document.getElementById("breed").innerHTML = this.responseText;
        }
    };
    xhr.send();
});
</script>

<?php include '../includes/footer.php'; ?>