<?php
session_start();
include 'config.php';

// Ambil data tanaman dan pesanan
$plants = $conn->query("SELECT * FROM plants");
$orders = $conn->query("SELECT orders.id, plants.name AS plant_name, orders.quantity, orders.buyer_name, orders.buyer_address, orders.buyer_phone, orders.created_at 
                        FROM orders 
                        JOIN plants ON orders.plant_id = plants.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Dashboard Admin</h1>
        <h2>Kelola Stok Tanaman</h2>
        <table>
            <tr>
                <th>Nama Tanaman</th>
                <th>Stok</th>
                <th>Harga (Rp)</th>
            </tr>
            <?php while ($plant = $plants->fetch_assoc()) : ?>
            <tr>
                <td><?= $plant['name']; ?></td>
                <td><?= $plant['stock']; ?></td>
                <td><?= number_format($plant['price'], 0, ',', '.'); ?></td>
              
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="add_stock.php" class="button">Tambah Tanaman</a>

        <h2>Data Pembelian</h2>
        <table>
            <tr>
                <th>Tanaman</th>
                <th>Jumlah</th>
                <th>Pembeli</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Tanggal</th>
            </tr>
            <?php while ($order = $orders->fetch_assoc()) : ?>
            <tr>
                <td><?= $order['plant_name']; ?></td>
                <td><?= $order['quantity']; ?></td>
                <td><?= $order['buyer_name']; ?></td>
                <td><?= $order['buyer_address']; ?></td>
                <td><?= $order['buyer_phone']; ?></td>
                <td><?= $order['created_at']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
