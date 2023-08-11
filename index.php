<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_management";

// สร้างการเชื่อมต่อกับฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// เพิ่มสินค้าเข้าสต็อก
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO products (name, price, quantity) VALUES ('$name', '$price', '$quantity')";
    if ($conn->query($sql) === false) {
        die("Insert failed: " . $conn->error);
    }
}

// แก้ไขข้อมูลสินค้า
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $sql = "UPDATE products SET name='$name', price='$price', quantity='$quantity' WHERE id=$id";
    if ($conn->query($sql) === false) {
        die("Update failed: " . $conn->error);
    }
}


// ลบข้อมูลสินค้า
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM products WHERE id=$id";
    if ($conn->query($sql) === false) {
        die("Delete failed: " . $conn->error);
    }
}

// ดึงข้อมูลสินค้าทั้งหมดจากฐานข้อมูล
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!-- ... ส่วนของ HTML ... -->


<!DOCTYPE html>
<html>
<head>
    <title>ระบบการจัดการสินค้า</title>
    <link rel="stylesheet" href="ProJ/style.css">
    
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="background-image">


    <h1>ระบบการจัดการสินค้า</h1>
    <div class="container">
    <!-- <div class="color-palette">
            <div class="color-box primary-color"></div>
            <div class="color-box secondary-color"></div>
            <div class="color-box warning-color"></div>
        </div> -->
    <!-- แบบฟอร์มเพิ่มสินค้า -->
    <h2 class="primary-color">เพิ่มสินค้าใหม่</h2>
        <form method="post">
        <input type="text" name="name" placeholder="ชื่อสินค้า" required>
        <input type="number" name="price" placeholder="ราคา" step="0.01" required>
        <input type="number" name="quantity" placeholder="จำนวน" required>
        <button type="submit" name="add">เพิ่มสินค้า</button>
    </form>

    <!-- แสดงรายการสินค้าในสต็อก -->
    <h2 class="secondary-color">รายการสินค้าในสต็อก</h2>
        <table border="1">
        <tr>
            <th>ชื่อสินค้า</th>
            <th>ราคา</th>
            <th>จำนวน</th>
            <th>ดำเนินการ</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product['name']; ?></td>
                <td><?php echo $product['price']; ?></td>
                <td><?php echo $product['quantity']; ?></td>
                <td>
                    <a href="?edit=<?php echo $product['id']; ?>">แก้ไข</a>
                    <a href="?delete=<?php echo $product['id']; ?>">ลบ</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php if (isset($_GET['edit'])): ?>
        <?php
        $edit_id = $_GET['edit'];
        $sql = "SELECT * FROM products WHERE id=$edit_id";
        $result = $conn->query($sql);
        $edit_product = $result->fetch_assoc();
        ?>

        <!-- แบบฟอร์มแก้ไขสินค้า -->
        <h2>แก้ไขข้อมูลสินค้า</h2>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
            <input type="text" name="name" placeholder="ชื่อสินค้า" value="<?php echo $edit_product['name']; ?>" required>
            <input type="number" name="price" placeholder="ราคา" step="0.01" value="<?php echo $edit_product['price']; ?>" required>
            <input type="number" name="quantity" placeholder="จำนวน" value="<?php echo $edit_product['quantity']; ?>" required>
            <button type="submit" name="edit">บันทึกการแก้ไข</button>
        </form>
    <?php endif; ?>
</body>
</html>
