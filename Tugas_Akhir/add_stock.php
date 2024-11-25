<?php
include 'config.php';

class PlantManager {
    private $conn;

    // inisialisasi koneksi database
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    
    public function getAllPlants() {
        $sql = "SELECT id, name FROM plants";
        $result = $this->conn->query($sql);
        $plants = []; 

        while ($plant = $result->fetch_assoc()) {
            $plants[] = $plant;
        }

        return $plants;
    }

    // Mendapatkan stok tanaman berdasarkan ID
    public function getPlantStockById($plant_id) {
        $sql = "SELECT stock FROM plants WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $plant_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc(); 
    }

    // Menambahkan stok tanaman
    public function addPlantStock($plant_id, $additional_stock) {
        $plant = $this->getPlantStockById($plant_id);

        if ($plant) {
            $new_stock = $plant['stock'] + $additional_stock;
            $sql = "UPDATE plants SET stock = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ii', $new_stock, $plant_id);

            return $stmt->execute();
        }

        return false;
    }
}


$plantManager = new PlantManager($conn);

// Cek jika form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plant_id = (int) $_POST['plant_id']; 
    $additional_stock = (int) $_POST['additional_stock']; 

    // Menambah stok tanaman
    if ($plantManager->addPlantStock($plant_id, $additional_stock)) {
        echo "<p>Stok tanaman berhasil ditambahkan!</p>";
    } else {
        echo "<p>Gagal menambahkan stok tanaman atau tanaman tidak ditemukan.</p>";
    }
}

// Tampilan dropdown
$plants = $plantManager->getAllPlants();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Stok Tanaman</title>
    <link rel="stylesheet" href="styleindex.css">
</head>
<body>
    <div class="add-stock-container">
        <h1>Tambah Stok Tanaman</h1>
        <form action="add_stock.php" method="POST">
            <label for="plant_id">Pilih Tanaman:</label>
            <select name="plant_id" id="plant_id" required>
                <?php foreach ($plants as $plant): ?>
                    <option value="<?= $plant['id']; ?>"><?= $plant['name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="additional_stock">Jumlah Stok yang Ditambahkan:</label>
            <input type="number" name="additional_stock" id="additional_stock" required min="1">

            <button type="submit">Tambah Stok</button>
        </form>
        <a href="dashboard.php" class="button">Kembali ke Dashboard</a>
    </div>
</body>
</html>
