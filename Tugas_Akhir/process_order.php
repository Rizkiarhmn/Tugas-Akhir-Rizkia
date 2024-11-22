<?php
include 'config.php';

class OrderManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Mendapatkan harga tanaman berdasarkan ID
    public function getPlantPrice($plant_id) {
        $sql = "SELECT price FROM plants WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $plant_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $plant = $result->fetch_assoc();
        return $plant ? $plant['price'] : null;
    }

    // Mengurangi stok tanaman
    public function reducePlantStock($plant_id, $quantity) {
        $sql = "UPDATE plants SET stock = stock - ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $quantity, $plant_id);
        return $stmt->execute();
    }

    // Menyimpan data pesanan
    public function saveOrder($plant_id, $buyer_name, $buyer_address, $buyer_phone, $quantity) {
        $sql = "INSERT INTO orders (plant_id, buyer_name, buyer_address, buyer_phone, quantity)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('isssi', $plant_id, $buyer_name, $buyer_address, $buyer_phone, $quantity);
        return $stmt->execute();
    }
}

$orderManager = new OrderManager($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plant_id = (int)$_POST['plant_id']; 
    $quantity = (int)$_POST['quantity']; 
    $buyer_name = htmlspecialchars($_POST['buyer_name']);
    $buyer_address = htmlspecialchars($_POST['buyer_address']);
    $buyer_phone = htmlspecialchars($_POST['buyer_phone']);

    // Mendapatkan harga per unit tanaman
    $price_per_unit = $orderManager->getPlantPrice($plant_id);

    if ($price_per_unit !== null) {
        $total_price = $price_per_unit * $quantity;

        // Kurangi stok tanaman dan simpan pesanan
        if ($orderManager->reducePlantStock($plant_id, $quantity) && 
            $orderManager->saveOrder($plant_id, $buyer_name, $buyer_address, $buyer_phone, $quantity)) {

            echo "<div class='order-confirmation'>";
            echo "<h1>Pesanan Berhasil Dibuat!</h1>";
            echo "<p>Terima kasih, <strong>$buyer_name</strong>, pesanan Anda telah tercatat.</p>";
            echo "<p>Total yang harus dibayar adalah: <strong>Rp " . number_format($total_price, 0, ',', '.') . "</strong></p>";
            echo "<p>Transfer ke BRI <strong>6794 0100 6594 501 (RIZKIA RAHMAN)</strong></p>";
            echo "<p>Silakan kirim bukti pembayaran ke nomor admin berikut:</p>";
            echo "<p><strong>0858-0216-8934</strong></p>";
            echo "<p>Pastikan menyertakan nama dan nomor HP Anda dalam pesan konfirmasi.</p>";
            echo "<a href='index.php'>Kembali ke Halaman Utama</a>";
            echo "</div>";
        } else {
            echo "<p>Gagal memproses pesanan. Silakan coba lagi.</p>";
        }
    } else {
        echo "<p>Tanaman tidak ditemukan.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Pesanan</title>
    <link rel="stylesheet" href="styleindex.css">
</head>
<body>
    <div class="container">
        <!-- Output -->
    </div>
</body>
</html>
