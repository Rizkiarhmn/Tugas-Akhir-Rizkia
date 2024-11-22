<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian Toko Anggrek</title>
    <link rel="stylesheet" href="styleindex.css">
</head>
<body>
    <div class="top-right">
        <a href="login.php" class="login-button">Login sebagai Admin</a>
    </div>

    <div class="container">
        <h1>Pembelian Tanaman Anggrek</h1>
        <form action="process_order.php" method="POST" enctype="multipart/form-data">
            <label for="plant">Pilih Tanaman:</label>
            <select name="plant_id" id="plant" required>
            <?php
                // Data tanaman beserta stok dan harga
                $plants = $conn->query("SELECT id, name, stock, price FROM plants WHERE stock > 0");
                while ($plant = $plants->fetch_assoc()) {
                    echo "<option value='{$plant['id']}'>
                        {$plant['name']} - Stok: {$plant['stock']} - Harga: Rp " . number_format($plant['price'], 0, ',', '.') . "
                    </option>";
                }
                ?>
            </select>

            <label for="quantity">Jumlah:</label>
            <input type="number" name="quantity" id="quantity" required min="1">

            <label for="buyer_name">Nama Pembeli:</label>
            <input type="text" name="buyer_name" id="buyer_name" required>

            <label for="buyer_address">Alamat Pembeli:</label>
            <textarea name="buyer_address" id="buyer_address" required></textarea>

            <label for="buyer_phone">Nomor HP:</label>
            <input type="text" name="buyer_phone" id="buyer_phone" required>

            <button type="submit">Pesan</button>
        </form>
    </div>
</body>
</html>
