<?php
require 'connect.php';

// Lấy danh mục để hiển thị filter
$cat_sql = "SELECT id, name FROM categories ORDER BY name ASC";
$cat_result = $mysqli->query($cat_sql);

// Lấy điều kiện lọc & tìm kiếm
$where = "WHERE 1=1"; // an toàn hơn
if (!empty($_GET['category_id'])) {
    $cat_id = (int)$_GET['category_id']; // ép int
    $where .= " AND p.category_id = $cat_id";
}
if (!empty($_GET['keyword'])) {
    $keyword = $mysqli->real_escape_string($_GET['keyword']);
    $where .= " AND p.name LIKE '%$keyword%'";
}

// Lấy danh sách sản phẩm theo filter
$sql = "SELECT p.id, p.name, p.price, p.image, c.name AS category
        FROM products p
        JOIN categories c ON p.category_id = c.id
        $where
        ORDER BY p.created_at DESC";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sản phẩm – Project1</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .container h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-bar form {
            display: flex;
            gap: 10px;
        }

        .filter-bar select,
        .filter-bar input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .filter-bar button {
            padding: 8px 18px;
            background: #b8860b;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .filter-bar button:hover {
            background: #a9740b;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
        }

        .product {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            padding: 18px 15px 25px;
            transition: .2s;
        }

        .product:hover {
            transform: translateY(-5px);
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
            border-radius: 10px;
        }

        .product h3 {
            font-size: 1.2rem;
            margin: 12px 0 5px;
        }

        .product p.price {
            color: #b8860b;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .product p.category {
            font-size: .9rem;
            color: #777;
            margin-bottom: 10px;
        }

        .product button {
            padding: 8px 18px;
            background: #b8860b;
            border: none;
            border-radius: 25px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .product button:hover {
            background: #a9740b;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Tất cả sản phẩm</h2>

        <!-- Thanh lọc và tìm kiếm -->
        <div class="filter-bar">
            <form method="GET">
                <select name="category_id">
                    <option value="">-- Tất cả danh mục --</option>
                    <?php if ($cat_result): while ($cat = $cat_result->fetch_assoc()): ?>
                            <option value="<?= $cat['id']; ?>"
                                <?= (!empty($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($cat['name']); ?>
                            </option>
                    <?php endwhile;
                    endif; ?>
                </select>
                <input type="text" name="keyword" placeholder="Tìm sản phẩm..."
                    value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                <button type="submit">Lọc</button>
            </form>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="products">
            <?php if ($result && $result->num_rows): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product">
                        <img src="images/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                        <h3><?= htmlspecialchars($row['name']); ?></h3>
                        <p class="price"><?= number_format($row['price']); ?> VNĐ</p>
                        <p class="category">Danh mục: <?= htmlspecialchars($row['category']); ?></p>
                        <form class="add-to-cart-form" onsubmit="return false;">
                            <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']); ?>">
                            <input type="hidden" name="product_price" value="<?= $row['price']; ?>">
                            <input type="hidden" name="product_image" value="<?= htmlspecialchars($row['image']); ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="button" class="add-cart-btn">Thêm vào giỏ</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column:1/-1;text-align:center;">❌ Không tìm thấy sản phẩm phù hợp!</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>&copy; <?= date('Y'); ?> Project1 – Tôn vinh vẻ đẹp của bạn</footer>

    <script>
        document.querySelectorAll('.add-cart-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const form = btn.closest('.add-to-cart-form');
                const res = await fetch('user/add_to_cart.php', {
                    method: 'POST',
                    body: new FormData(form)
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('cart-count').textContent = data.totalQty;
                    alert('✅ Đã thêm vào giỏ!');
                } else alert('❌ Lỗi thêm sản phẩm');
            });
        });
    </script>
</body>

</html>
<?php $mysqli->close(); ?>
<?php
require 'connect.php';

// Lấy danh mục để hiển thị filter
$cat_sql = "SELECT id, name FROM categories ORDER BY name ASC";
$cat_result = $mysqli->query($cat_sql);

// Lấy điều kiện lọc & tìm kiếm
$where = "WHERE 1=1"; // an toàn hơn
if (!empty($_GET['category_id'])) {
    $cat_id = (int)$_GET['category_id']; // ép int
    $where .= " AND p.category_id = $cat_id";
}
if (!empty($_GET['keyword'])) {
    $keyword = $mysqli->real_escape_string($_GET['keyword']);
    $where .= " AND p.name LIKE '%$keyword%'";
}

// Lấy danh sách sản phẩm theo filter
$sql = "SELECT p.id, p.name, p.price, p.image, c.name AS category
        FROM products p
        JOIN categories c ON p.category_id = c.id
        $where
        ORDER BY p.created_at DESC";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sản phẩm – Project1</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .container h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-bar form {
            display: flex;
            gap: 10px;
        }

        .filter-bar select,
        .filter-bar input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .filter-bar button {
            padding: 8px 18px;
            background: #b8860b;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .filter-bar button:hover {
            background: #a9740b;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
        }

        .product {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            padding: 18px 15px 25px;
            transition: .2s;
        }

        .product:hover {
            transform: translateY(-5px);
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
            border-radius: 10px;
        }

        .product h3 {
            font-size: 1.2rem;
            margin: 12px 0 5px;
        }

        .product p.price {
            color: #b8860b;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .product p.category {
            font-size: .9rem;
            color: #777;
            margin-bottom: 10px;
        }

        .product button {
            padding: 8px 18px;
            background: #b8860b;
            border: none;
            border-radius: 25px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .product button:hover {
            background: #a9740b;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Tất cả sản phẩm</h2>

        <!-- Thanh lọc và tìm kiếm -->
        <div class="filter-bar">
            <form method="GET">
                <select name="category_id">
                    <option value="">-- Tất cả danh mục --</option>
                    <?php if ($cat_result): while ($cat = $cat_result->fetch_assoc()): ?>
                            <option value="<?= $cat['id']; ?>"
                                <?= (!empty($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($cat['name']); ?>
                            </option>
                    <?php endwhile;
                    endif; ?>
                </select>
                <input type="text" name="keyword" placeholder="Tìm sản phẩm..."
                    value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                <button type="submit">Lọc</button>
            </form>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="products">
            <?php if ($result && $result->num_rows): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product">
                        <img src="images/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                        <h3><?= htmlspecialchars($row['name']); ?></h3>
                        <p class="price"><?= number_format($row['price']); ?> VNĐ</p>
                        <p class="category">Danh mục: <?= htmlspecialchars($row['category']); ?></p>
                        <form class="add-to-cart-form" onsubmit="return false;">
                            <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']); ?>">
                            <input type="hidden" name="product_price" value="<?= $row['price']; ?>">
                            <input type="hidden" name="product_image" value="<?= htmlspecialchars($row['image']); ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="button" class="add-cart-btn">Thêm vào giỏ</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column:1/-1;text-align:center;">❌ Không tìm thấy sản phẩm phù hợp!</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>&copy; <?= date('Y'); ?> Project1 – Tôn vinh vẻ đẹp của bạn</footer>

    <script>
        document.querySelectorAll('.add-cart-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const form = btn.closest('.add-to-cart-form');
                const res = await fetch('user/add_to_cart.php', {
                    method: 'POST',
                    body: new FormData(form)
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('cart-count').textContent = data.totalQty;
                    alert('✅ Đã thêm vào giỏ!');
                } else alert('❌ Lỗi thêm sản phẩm');
            });
        });
    </script>
</body>

</html>
<?php $mysqli->close(); ?><?php
                            require 'connect.php';

                            // Lấy danh mục để hiển thị filter
                            $cat_sql = "SELECT id, name FROM categories ORDER BY name ASC";
                            $cat_result = $mysqli->query($cat_sql);

                            // Lấy điều kiện lọc & tìm kiếm
                            $where = "WHERE 1=1"; // an toàn hơn
                            if (!empty($_GET['category_id'])) {
                                $cat_id = (int)$_GET['category_id']; // ép int
                                $where .= " AND p.category_id = $cat_id";
                            }
                            if (!empty($_GET['keyword'])) {
                                $keyword = $mysqli->real_escape_string($_GET['keyword']);
                                $where .= " AND p.name LIKE '%$keyword%'";
                            }

                            // Lấy danh sách sản phẩm theo filter
                            $sql = "SELECT p.id, p.name, p.price, p.image, c.name AS category
        FROM products p
        JOIN categories c ON p.category_id = c.id
        $where
        ORDER BY p.created_at DESC";
                            $result = $mysqli->query($sql);
                            ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sản phẩm – Project1</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .container h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-bar form {
            display: flex;
            gap: 10px;
        }

        .filter-bar select,
        .filter-bar input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .filter-bar button {
            padding: 8px 18px;
            background: #b8860b;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .filter-bar button:hover {
            background: #a9740b;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
        }

        .product {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            padding: 18px 15px 25px;
            transition: .2s;
        }

        .product:hover {
            transform: translateY(-5px);
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
            border-radius: 10px;
        }

        .product h3 {
            font-size: 1.2rem;
            margin: 12px 0 5px;
        }

        .product p.price {
            color: #b8860b;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .product p.category {
            font-size: .9rem;
            color: #777;
            margin-bottom: 10px;
        }

        .product button {
            padding: 8px 18px;
            background: #b8860b;
            border: none;
            border-radius: 25px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .product button:hover {
            background: #a9740b;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Tất cả sản phẩm</h2>

        <!-- Thanh lọc và tìm kiếm -->
        <div class="filter-bar">
            <form method="GET">
                <select name="category_id">
                    <option value="">-- Tất cả danh mục --</option>
                    <?php if ($cat_result): while ($cat = $cat_result->fetch_assoc()): ?>
                            <option value="<?= $cat['id']; ?>"
                                <?= (!empty($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($cat['name']); ?>
                            </option>
                    <?php endwhile;
                    endif; ?>
                </select>
                <input type="text" name="keyword" placeholder="Tìm sản phẩm..."
                    value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                <button type="submit">Lọc</button>
            </form>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="products">
            <?php if ($result && $result->num_rows): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product">
                        <img src="images/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                        <h3><?= htmlspecialchars($row['name']); ?></h3>
                        <p class="price"><?= number_format($row['price']); ?> VNĐ</p>
                        <p class="category">Danh mục: <?= htmlspecialchars($row['category']); ?></p>
                        <form class="add-to-cart-form" onsubmit="return false;">
                            <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']); ?>">
                            <input type="hidden" name="product_price" value="<?= $row['price']; ?>">
                            <input type="hidden" name="product_image" value="<?= htmlspecialchars($row['image']); ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="button" class="add-cart-btn">Thêm vào giỏ</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column:1/-1;text-align:center;">❌ Không tìm thấy sản phẩm phù hợp!</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>&copy; <?= date('Y'); ?> Project1 – Tôn vinh vẻ đẹp của bạn</footer>

    <script>
        document.querySelectorAll('.add-cart-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const form = btn.closest('.add-to-cart-form');
                const res = await fetch('user/add_to_cart.php', {
                    method: 'POST',
                    body: new FormData(form)
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('cart-count').textContent = data.totalQty;
                    alert('✅ Đã thêm vào giỏ!');
                } else alert('❌ Lỗi thêm sản phẩm');
            });
        });
    </script>
</body>

</html>
<?php $mysqli->close(); ?><?php
                            require 'connect.php';

                            // Lấy danh mục để hiển thị filter
                            $cat_sql = "SELECT id, name FROM categories ORDER BY name ASC";
                            $cat_result = $mysqli->query($cat_sql);

                            // Lấy điều kiện lọc & tìm kiếm
                            $where = "WHERE 1=1"; // an toàn hơn
                            if (!empty($_GET['category_id'])) {
                                $cat_id = (int)$_GET['category_id']; // ép int
                                $where .= " AND p.category_id = $cat_id";
                            }
                            if (!empty($_GET['keyword'])) {
                                $keyword = $mysqli->real_escape_string($_GET['keyword']);
                                $where .= " AND p.name LIKE '%$keyword%'";
                            }

                            // Lấy danh sách sản phẩm theo filter
                            $sql = "SELECT p.id, p.name, p.price, p.image, c.name AS category
        FROM products p
        JOIN categories c ON p.category_id = c.id
        $where
        ORDER BY p.created_at DESC";
                            $result = $mysqli->query($sql);
                            ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sản phẩm – Project1</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .container h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-bar form {
            display: flex;
            gap: 10px;
        }

        .filter-bar select,
        .filter-bar input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .filter-bar button {
            padding: 8px 18px;
            background: #b8860b;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .filter-bar button:hover {
            background: #a9740b;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
        }

        .product {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            padding: 18px 15px 25px;
            transition: .2s;
        }

        .product:hover {
            transform: translateY(-5px);
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
            border-radius: 10px;
        }

        .product h3 {
            font-size: 1.2rem;
            margin: 12px 0 5px;
        }

        .product p.price {
            color: #b8860b;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .product p.category {
            font-size: .9rem;
            color: #777;
            margin-bottom: 10px;
        }

        .product button {
            padding: 8px 18px;
            background: #b8860b;
            border: none;
            border-radius: 25px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .product button:hover {
            background: #a9740b;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Tất cả sản phẩm</h2>

        <!-- Thanh lọc và tìm kiếm -->
        <div class="filter-bar">
            <form method="GET">
                <select name="category_id">
                    <option value="">-- Tất cả danh mục --</option>
                    <?php if ($cat_result): while ($cat = $cat_result->fetch_assoc()): ?>
                            <option value="<?= $cat['id']; ?>"
                                <?= (!empty($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($cat['name']); ?>
                            </option>
                    <?php endwhile;
                    endif; ?>
                </select>
                <input type="text" name="keyword" placeholder="Tìm sản phẩm..."
                    value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                <button type="submit">Lọc</button>
            </form>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="products">
            <?php if ($result && $result->num_rows): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product">
                        <img src="images/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                        <h3><?= htmlspecialchars($row['name']); ?></h3>
                        <p class="price"><?= number_format($row['price']); ?> VNĐ</p>
                        <p class="category">Danh mục: <?= htmlspecialchars($row['category']); ?></p>
                        <form class="add-to-cart-form" onsubmit="return false;">
                            <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']); ?>">
                            <input type="hidden" name="product_price" value="<?= $row['price']; ?>">
                            <input type="hidden" name="product_image" value="<?= htmlspecialchars($row['image']); ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="button" class="add-cart-btn">Thêm vào giỏ</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column:1/-1;text-align:center;">❌ Không tìm thấy sản phẩm phù hợp!</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>&copy; <?= date('Y'); ?> Project1 – Tôn vinh vẻ đẹp của bạn</footer>

    <script>
        document.querySelectorAll('.add-cart-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const form = btn.closest('.add-to-cart-form');
                const res = await fetch('user/add_to_cart.php', {
                    method: 'POST',
                    body: new FormData(form)
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('cart-count').textContent = data.totalQty;
                    alert('✅ Đã thêm vào giỏ!');
                } else alert('❌ Lỗi thêm sản phẩm');
            });
        });
    </script>
</body>

</html>
<?php $mysqli->close(); ?><?php
                            require 'connect.php';

                            // Lấy danh mục để hiển thị filter
                            $cat_sql = "SELECT id, name FROM categories ORDER BY name ASC";
                            $cat_result = $mysqli->query($cat_sql);

                            // Lấy điều kiện lọc & tìm kiếm
                            $where = "WHERE 1=1"; // an toàn hơn
                            if (!empty($_GET['category_id'])) {
                                $cat_id = (int)$_GET['category_id']; // ép int
                                $where .= " AND p.category_id = $cat_id";
                            }
                            if (!empty($_GET['keyword'])) {
                                $keyword = $mysqli->real_escape_string($_GET['keyword']);
                                $where .= " AND p.name LIKE '%$keyword%'";
                            }

                            // Lấy danh sách sản phẩm theo filter
                            $sql = "SELECT p.id, p.name, p.price, p.image, c.name AS category
        FROM products p
        JOIN categories c ON p.category_id = c.id
        $where
        ORDER BY p.created_at DESC";
                            $result = $mysqli->query($sql);
                            ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sản phẩm – Project1</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .container h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-bar form {
            display: flex;
            gap: 10px;
        }

        .filter-bar select,
        .filter-bar input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .filter-bar button {
            padding: 8px 18px;
            background: #b8860b;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .filter-bar button:hover {
            background: #a9740b;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
        }

        .product {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            padding: 18px 15px 25px;
            transition: .2s;
        }

        .product:hover {
            transform: translateY(-5px);
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
            border-radius: 10px;
        }

        .product h3 {
            font-size: 1.2rem;
            margin: 12px 0 5px;
        }

        .product p.price {
            color: #b8860b;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .product p.category {
            font-size: .9rem;
            color: #777;
            margin-bottom: 10px;
        }

        .product button {
            padding: 8px 18px;
            background: #b8860b;
            border: none;
            border-radius: 25px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .product button:hover {
            background: #a9740b;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Tất cả sản phẩm</h2>

        <!-- Thanh lọc và tìm kiếm -->
        <div class="filter-bar">
            <form method="GET">
                <select name="category_id">
                    <option value="">-- Tất cả danh mục --</option>
                    <?php if ($cat_result): while ($cat = $cat_result->fetch_assoc()): ?>
                            <option value="<?= $cat['id']; ?>"
                                <?= (!empty($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($cat['name']); ?>
                            </option>
                    <?php endwhile;
                    endif; ?>
                </select>
                <input type="text" name="keyword" placeholder="Tìm sản phẩm..."
                    value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                <button type="submit">Lọc</button>
            </form>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="products">
            <?php if ($result && $result->num_rows): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product">
                        <img src="images/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                        <h3><?= htmlspecialchars($row['name']); ?></h3>
                        <p class="price"><?= number_format($row['price']); ?> VNĐ</p>
                        <p class="category">Danh mục: <?= htmlspecialchars($row['category']); ?></p>
                        <form class="add-to-cart-form" onsubmit="return false;">
                            <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']); ?>">
                            <input type="hidden" name="product_price" value="<?= $row['price']; ?>">
                            <input type="hidden" name="product_image" value="<?= htmlspecialchars($row['image']); ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="button" class="add-cart-btn">Thêm vào giỏ</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column:1/-1;text-align:center;">❌ Không tìm thấy sản phẩm phù hợp!</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>&copy; <?= date('Y'); ?> Project1 – Tôn vinh vẻ đẹp của bạn</footer>

    <script>
        document.querySelectorAll('.add-cart-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const form = btn.closest('.add-to-cart-form');
                const res = await fetch('user/add_to_cart.php', {
                    method: 'POST',
                    body: new FormData(form)
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('cart-count').textContent = data.totalQty;
                    alert('✅ Đã thêm vào giỏ!');
                } else alert('❌ Lỗi thêm sản phẩm');
            });
        });
    </script>
</body>

</html>
<?php $mysqli->close(); ?><?php
                            require 'connect.php';

                            // Lấy danh mục để hiển thị filter
                            $cat_sql = "SELECT id, name FROM categories ORDER BY name ASC";
                            $cat_result = $mysqli->query($cat_sql);

                            // Lấy điều kiện lọc & tìm kiếm
                            $where = "WHERE 1=1"; // an toàn hơn
                            if (!empty($_GET['category_id'])) {
                                $cat_id = (int)$_GET['category_id']; // ép int
                                $where .= " AND p.category_id = $cat_id";
                            }
                            if (!empty($_GET['keyword'])) {
                                $keyword = $mysqli->real_escape_string($_GET['keyword']);
                                $where .= " AND p.name LIKE '%$keyword%'";
                            }

                            // Lấy danh sách sản phẩm theo filter
                            $sql = "SELECT p.id, p.name, p.price, p.image, c.name AS category
        FROM products p
        JOIN categories c ON p.category_id = c.id
        $where
        ORDER BY p.created_at DESC";
                            $result = $mysqli->query($sql);
                            ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sản phẩm – Project1</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .container h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-bar form {
            display: flex;
            gap: 10px;
        }

        .filter-bar select,
        .filter-bar input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .filter-bar button {
            padding: 8px 18px;
            background: #b8860b;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .filter-bar button:hover {
            background: #a9740b;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
        }

        .product {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            padding: 18px 15px 25px;
            transition: .2s;
        }

        .product:hover {
            transform: translateY(-5px);
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
            border-radius: 10px;
        }

        .product h3 {
            font-size: 1.2rem;
            margin: 12px 0 5px;
        }

        .product p.price {
            color: #b8860b;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .product p.category {
            font-size: .9rem;
            color: #777;
            margin-bottom: 10px;
        }

        .product button {
            padding: 8px 18px;
            background: #b8860b;
            border: none;
            border-radius: 25px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .product button:hover {
            background: #a9740b;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Tất cả sản phẩm</h2>

        <!-- Thanh lọc và tìm kiếm -->
        <div class="filter-bar">
            <form method="GET">
                <select name="category_id">
                    <option value="">-- Tất cả danh mục --</option>
                    <?php if ($cat_result): while ($cat = $cat_result->fetch_assoc()): ?>
                            <option value="<?= $cat['id']; ?>"
                                <?= (!empty($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($cat['name']); ?>
                            </option>
                    <?php endwhile;
                    endif; ?>
                </select>
                <input type="text" name="keyword" placeholder="Tìm sản phẩm..."
                    value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                <button type="submit">Lọc</button>
            </form>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="products">
            <?php if ($result && $result->num_rows): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product">
                        <img src="images/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                        <h3><?= htmlspecialchars($row['name']); ?></h3>
                        <p class="price"><?= number_format($row['price']); ?> VNĐ</p>
                        <p class="category">Danh mục: <?= htmlspecialchars($row['category']); ?></p>
                        <form class="add-to-cart-form" onsubmit="return false;">
                            <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']); ?>">
                            <input type="hidden" name="product_price" value="<?= $row['price']; ?>">
                            <input type="hidden" name="product_image" value="<?= htmlspecialchars($row['image']); ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="button" class="add-cart-btn">Thêm vào giỏ</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column:1/-1;text-align:center;">❌ Không tìm thấy sản phẩm phù hợp!</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>&copy; <?= date('Y'); ?> Project1 – Tôn vinh vẻ đẹp của bạn</footer>

    <script>
        document.querySelectorAll('.add-cart-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const form = btn.closest('.add-to-cart-form');
                const res = await fetch('user/add_to_cart.php', {
                    method: 'POST',
                    body: new FormData(form)
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('cart-count').textContent = data.totalQty;
                    alert('✅ Đã thêm vào giỏ!');
                } else alert('❌ Lỗi thêm sản phẩm');
            });
        });
    </script>
</body>

</html>
<?php $mysqli->close(); ?>