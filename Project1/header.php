<?php
// header.php
if (session_status() === PHP_SESSION_NONE) session_start();

// KHỞI TẠO biến dùng trong header để tránh "Undefined variable"
$current_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$current_cat_id  = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

// Lấy danh mục sản phẩm
$cat_sql = "SELECT id, name FROM categories ORDER BY name ASC";
$cat_result = $mysqli->query($cat_sql);
?>
<header>
    <h1>💍 Cửa Hàng Trang Sức</h1>
    <p>Nơi tôn vinh vẻ đẹp và đẳng cấp của bạn</p>
</header>

<nav>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Trang chủ</a>
    <a href="products.php" class="<?= basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : '' ?>">Sản phẩm</a>

    <div class="dropdown">
        <button class="dropbtn">📚 Danh mục <span class="arrow">▾</span></button>
        <div class="dropdown-content">
            <a href="products.php<?= $current_keyword ? '?keyword=' . urlencode($current_keyword) : '' ?>"
                class="<?= $current_cat_id === 0 ? 'active' : '' ?>">Tất cả</a>
            <?php if ($cat_result && $cat_result->num_rows): while ($cat = $cat_result->fetch_assoc()):
                    $link = 'products.php?category_id=' . $cat['id'];
                    if ($current_keyword) $link .= '&keyword=' . urlencode($current_keyword);
            ?>
                    <a href="<?= $link ?>"
                        class="<?= ($current_cat_id === (int)$cat['id']) ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['name']); ?>
                    </a>
            <?php endwhile;
            endif; ?>
        </div>
    </div>
    <!-- Form tìm kiếm -->
    <form action="products.php" method="get" class="search-form">
        <input type="text" name="keyword" placeholder="🔍 Tìm sản phẩm..."
            value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
        <button type="submit">Tìm</button>
    </form>

    <a href="user/cart.php">🛒 Giỏ (<span id="cart-count">
            <?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0 ?>
        </span>)</a>


    <?php if (isset($_SESSION['username'])): ?>
        <a href="user/orders.php">Đơn hàng</a>
        <?php if ($_SESSION['role'] === 'admin'): ?><a href="admin.php">Quản trị</a><?php endif; ?>
        <span class="user-info">Xin chào, <?= htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php" class="logout-btn">Đăng xuất</a>
    <?php else: ?>
        <a href="login.php">Đăng nhập</a>

        <!-- <a href="user/register.php">Đăng ký</a> -->
    <?php endif; ?>
</nav>

<style>
    nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    /* Dropdown container */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Button chính */
    .dropbtn {
        background-color: #b8860b;
        color: white;
        padding: 10px 16px;
        font-size: 14px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Hover nút */
    .dropbtn:hover {
        background-color: #a9740b;
    }

    /* Nội dung dropdown */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 160px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
        border-radius: 6px;
        z-index: 999;
    }

    /* Item trong dropdown */
    .dropdown-content a {
        color: #333;
        padding: 10px 14px;
        text-decoration: none;
        display: block;
        transition: 0.2s;
        border-radius: 6px;
    }

    /* Hover item */
    .dropdown-content a:hover {
        background-color: #f5f5f5;
        color: #b8860b;
    }

    /* Hiện dropdown khi hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }

    .search-form {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .search-form input {
        padding: 5px 10px;
        border-radius: 20px;
        border: 1px solid #ccc;
    }

    .search-form button {
        padding: 6px 12px;
        border: none;
        border-radius: 20px;
        background: #b8860b;
        color: white;
        cursor: pointer;
    }

    .search-form button:hover {
        background: #a9740b;
    }
</style><?php
        // header.php
        if (session_status() === PHP_SESSION_NONE) session_start();

        // KHỞI TẠO biến dùng trong header để tránh "Undefined variable"
        $current_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $current_cat_id  = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

        // Lấy danh mục sản phẩm
        $cat_sql = "SELECT id, name FROM categories ORDER BY name ASC";
        $cat_result = $mysqli->query($cat_sql);
        ?>
<header>
    <h1>💍 Cửa Hàng Trang Sức</h1>
    <p>Nơi tôn vinh vẻ đẹp và đẳng cấp của bạn</p>
</header>

<nav>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Trang chủ</a>
    <a href="products.php" class="<?= basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : '' ?>">Sản phẩm</a>

    <div class="dropdown">
        <button class="dropbtn">📚 Danh mục <span class="arrow">▾</span></button>
        <div class="dropdown-content">
            <a href="products.php<?= $current_keyword ? '?keyword=' . urlencode($current_keyword) : '' ?>"
                class="<?= $current_cat_id === 0 ? 'active' : '' ?>">Tất cả</a>
            <?php if ($cat_result && $cat_result->num_rows): while ($cat = $cat_result->fetch_assoc()):
                    $link = 'products.php?category_id=' . $cat['id'];
                    if ($current_keyword) $link .= '&keyword=' . urlencode($current_keyword);
            ?>
                    <a href="<?= $link ?>"
                        class="<?= ($current_cat_id === (int)$cat['id']) ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['name']); ?>
                    </a>
            <?php endwhile;
            endif; ?>
        </div>
    </div>
    <!-- Form tìm kiếm -->
    <form action="products.php" method="get" class="search-form">
        <input type="text" name="keyword" placeholder="🔍 Tìm sản phẩm..."
            value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
        <button type="submit">Tìm</button>
    </form>

    <a href="user/cart.php">🛒 Giỏ (<span id="cart-count">
            <?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0 ?>
        </span>)</a>


    <?php if (isset($_SESSION['username'])): ?>
        <a href="user/orders.php">Đơn hàng</a>
        <?php if ($_SESSION['role'] === 'admin'): ?><a href="admin.php">Quản trị</a><?php endif; ?>
        <span class="user-info">Xin chào, <?= htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php" class="logout-btn">Đăng xuất</a>
    <?php else: ?>
        <a href="login.php">Đăng nhập</a>

        <!-- <a href="user/register.php">Đăng ký</a> -->
    <?php endif; ?>
</nav>

<style>
    nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    /* Dropdown container */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Button chính */
    .dropbtn {
        background-color: #b8860b;
        color: white;
        padding: 10px 16px;
        font-size: 14px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Hover nút */
    .dropbtn:hover {
        background-color: #a9740b;
    }

    /* Nội dung dropdown */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 160px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
        border-radius: 6px;
        z-index: 999;
    }

    /* Item trong dropdown */
    .dropdown-content a {
        color: #333;
        padding: 10px 14px;
        text-decoration: none;
        display: block;
        transition: 0.2s;
        border-radius: 6px;
    }

    /* Hover item */
    .dropdown-content a:hover {
        background-color: #f5f5f5;
        color: #b8860b;
    }

    /* Hiện dropdown khi hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }

    .search-form {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .search-form input {
        padding: 5px 10px;
        border-radius: 20px;
        border: 1px solid #ccc;
    }

    .search-form button {
        padding: 6px 12px;
        border: none;
        border-radius: 20px;
        background: #b8860b;
        color: white;
        cursor: pointer;
    }

    .search-form button:hover {
        background: #a9740b;
    }
</style><?php
        // header.php
        if (session_status() === PHP_SESSION_NONE) session_start();

        // KHỞI TẠO biến dùng trong header để tránh "Undefined variable"
        $current_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $current_cat_id  = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

        // Lấy danh mục sản phẩm
        $cat_sql = "SELECT id, name FROM categories ORDER BY name ASC";
        $cat_result = $mysqli->query($cat_sql);
        ?>
<header>
    <h1>💍 Cửa Hàng Trang Sức</h1>
    <p>Nơi tôn vinh vẻ đẹp và đẳng cấp của bạn</p>
</header>

<nav>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Trang chủ</a>
    <a href="products.php" class="<?= basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : '' ?>">Sản phẩm</a>

    <div class="dropdown">
        <button class="dropbtn">📚 Danh mục <span class="arrow">▾</span></button>
        <div class="dropdown-content">
            <a href="products.php<?= $current_keyword ? '?keyword=' . urlencode($current_keyword) : '' ?>"
                class="<?= $current_cat_id === 0 ? 'active' : '' ?>">Tất cả</a>
            <?php if ($cat_result && $cat_result->num_rows): while ($cat = $cat_result->fetch_assoc()):
                    $link = 'products.php?category_id=' . $cat['id'];
                    if ($current_keyword) $link .= '&keyword=' . urlencode($current_keyword);
            ?>
                    <a href="<?= $link ?>"
                        class="<?= ($current_cat_id === (int)$cat['id']) ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['name']); ?>
                    </a>
            <?php endwhile;
            endif; ?>
        </div>
    </div>
    <!-- Form tìm kiếm -->
    <form action="products.php" method="get" class="search-form">
        <input type="text" name="keyword" placeholder="🔍 Tìm sản phẩm..."
            value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
        <button type="submit">Tìm</button>
    </form>

    <a href="user/cart.php">🛒 Giỏ (<span id="cart-count">
            <?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0 ?>
        </span>)</a>


    <?php if (isset($_SESSION['username'])): ?>
        <a href="user/orders.php">Đơn hàng</a>
        <?php if ($_SESSION['role'] === 'admin'): ?><a href="admin.php">Quản trị</a><?php endif; ?>
        <span class="user-info">Xin chào, <?= htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php" class="logout-btn">Đăng xuất</a>
    <?php else: ?>
        <a href="login.php">Đăng nhập</a>

        <!-- <a href="user/register.php">Đăng ký</a> -->
    <?php endif; ?>
</nav>

<style>
    nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    /* Dropdown container */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Button chính */
    .dropbtn {
        background-color: #b8860b;
        color: white;
        padding: 10px 16px;
        font-size: 14px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Hover nút */
    .dropbtn:hover {
        background-color: #a9740b;
    }

    /* Nội dung dropdown */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 160px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
        border-radius: 6px;
        z-index: 999;
    }

    /* Item trong dropdown */
    .dropdown-content a {
        color: #333;
        padding: 10px 14px;
        text-decoration: none;
        display: block;
        transition: 0.2s;
        border-radius: 6px;
    }

    /* Hover item */
    .dropdown-content a:hover {
        background-color: #f5f5f5;
        color: #b8860b;
    }

    /* Hiện dropdown khi hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }

    .search-form {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .search-form input {
        padding: 5px 10px;
        border-radius: 20px;
        border: 1px solid #ccc;
    }

    .search-form button {
        padding: 6px 12px;
        border: none;
        border-radius: 20px;
        background: #b8860b;
        color: white;
        cursor: pointer;
    }

    .search-form button:hover {
        background: #a9740b;
    }
</style><?php
        // header.php
        if (session_status() === PHP_SESSION_NONE) session_start();

        // KHỞI TẠO biến dùng trong header để tránh "Undefined variable"
        $current_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $current_cat_id  = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

        // Lấy danh mục sản phẩm
        $cat_sql = "SELECT id, name FROM categories ORDER BY name ASC";
        $cat_result = $mysqli->query($cat_sql);
        ?>
<header>
    <h1>💍 Cửa Hàng Trang Sức</h1>
    <p>Nơi tôn vinh vẻ đẹp và đẳng cấp của bạn</p>
</header>

<nav>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Trang chủ</a>
    <a href="products.php" class="<?= basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : '' ?>">Sản phẩm</a>

    <div class="dropdown">
        <button class="dropbtn">📚 Danh mục <span class="arrow">▾</span></button>
        <div class="dropdown-content">
            <a href="products.php<?= $current_keyword ? '?keyword=' . urlencode($current_keyword) : '' ?>"
                class="<?= $current_cat_id === 0 ? 'active' : '' ?>">Tất cả</a>
            <?php if ($cat_result && $cat_result->num_rows): while ($cat = $cat_result->fetch_assoc()):
                    $link = 'products.php?category_id=' . $cat['id'];
                    if ($current_keyword) $link .= '&keyword=' . urlencode($current_keyword);
            ?>
                    <a href="<?= $link ?>"
                        class="<?= ($current_cat_id === (int)$cat['id']) ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['name']); ?>
                    </a>
            <?php endwhile;
            endif; ?>
        </div>
    </div>
    <!-- Form tìm kiếm -->
    <form action="products.php" method="get" class="search-form">
        <input type="text" name="keyword" placeholder="🔍 Tìm sản phẩm..."
            value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
        <button type="submit">Tìm</button>
    </form>

    <a href="user/cart.php">🛒 Giỏ (<span id="cart-count">
            <?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0 ?>
        </span>)</a>


    <?php if (isset($_SESSION['username'])): ?>
        <a href="user/orders.php">Đơn hàng</a>
        <?php if ($_SESSION['role'] === 'admin'): ?><a href="admin.php">Quản trị</a><?php endif; ?>
        <span class="user-info">Xin chào, <?= htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php" class="logout-btn">Đăng xuất</a>
    <?php else: ?>
        <a href="login.php">Đăng nhập</a>

        <!-- <a href="user/register.php">Đăng ký</a> -->
    <?php endif; ?>
</nav>

<style>
    nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    /* Dropdown container */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Button chính */
    .dropbtn {
        background-color: #b8860b;
        color: white;
        padding: 10px 16px;
        font-size: 14px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Hover nút */
    .dropbtn:hover {
        background-color: #a9740b;
    }

    /* Nội dung dropdown */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 160px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
        border-radius: 6px;
        z-index: 999;
    }

    /* Item trong dropdown */
    .dropdown-content a {
        color: #333;
        padding: 10px 14px;
        text-decoration: none;
        display: block;
        transition: 0.2s;
        border-radius: 6px;
    }

    /* Hover item */
    .dropdown-content a:hover {
        background-color: #f5f5f5;
        color: #b8860b;
    }

    /* Hiện dropdown khi hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }

    .search-form {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .search-form input {
        padding: 5px 10px;
        border-radius: 20px;
        border: 1px solid #ccc;
    }

    .search-form button {
        padding: 6px 12px;
        border: none;
        border-radius: 20px;
        background: #b8860b;
        color: white;
        cursor: pointer;
    }

    .search-form button:hover {
        background: #a9740b;
    }
</style>