<?php
session_start();
require 'connect.php';

$sql = "SELECT p.id, p.name, p.price, p.image, c.name AS category
        FROM products p
        JOIN categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC
        LIMIT 6";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Trang chủ - Project1</title>

<style>
/* ===== Reset ===== */
* {margin:0; padding:0; box-sizing:border-box;}
body {font-family:'Poppins', Arial, sans-serif; background:#fefefe; color:#333; line-height:1.6;}
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

/* ===== Header ===== */
header{
    background: linear-gradient(135deg,#f9f1e7,#fff8f0);
    color:#333;
    text-align:center;
    padding:60px 20px;
    border-bottom: 2px solid #f1c40f;
}
header h1 { font-size:3rem; color:#b8860b; margin-bottom:10px; }
header p { font-size:1.2rem; color:#555; }

/* ===== Navbar ===== */
nav {
    background:#fff;
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    align-items:center;
    gap:15px;
    padding:14px 10px;
    border-bottom:1px solid #eee;
    box-shadow:0 2px 5px rgba(0,0,0,0.05);
}
nav a {
    color:#333;
    text-decoration:none;
    font-weight:500;
    padding:6px 12px;
    border-radius:6px;
    transition: all .3s;
}
nav a:hover, nav a.active { background:#b8860b; color:#fff; }
nav .user-info { font-weight:500; color:#444; margin-left:10px; }

/* ===== Intro (Giới thiệu cửa hàng) ===== */
.intro{
    max-width:900px;
    margin:50px auto 40px;
    padding:40px 30px;
    text-align:center;
    background: linear-gradient(135deg,#fffaf0,#fff5e6);
    border-radius:15px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
    line-height:1.8;
    font-size:1.1rem;
    color:#444;
}
.intro h2{
    font-size:2.2rem;
    margin-bottom:20px;
    color:#b8860b;
    font-weight:700;
    letter-spacing:1px;
}
.intro p{
    margin-bottom:15px;
}
.intro strong{
    color:#b8860b;
    font-weight:600;
}

/* ===== Products ===== */
.container{ max-width:1200px; margin:50px auto; padding:0 20px; }
.container h2{ font-size:2rem; margin-bottom:25px; text-align:center; color:#333; font-weight:600; }

.products {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(240px,1fr));
    gap:25px;
}
.product {
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    transition:transform .2s ease, box-shadow .2s ease;
    text-align:center;
    padding-bottom:15px;
}
.product:hover { transform:translateY(-5px); box-shadow:0 10px 20px rgba(0,0,0,0.12); }

.product img { width:100%; height:200px; object-fit:cover; border-bottom:1px solid #eee; }
.product h3 { font-size:1.2rem; margin:12px 0 5px; color:#444; }
.product p.price { color:#b8860b; font-weight:bold; margin-bottom:6px; }
.product p.category { font-size:.9rem; color:#888; margin-bottom:10px; }

.product button {
    padding:10px 20px;
    background:#b8860b;
    border:none;
    border-radius:25px;
    color:#fff;
    font-weight:600;
    cursor:pointer;
    transition:all .2s;
}
.product button:hover { background:#a9740b; transform:scale(1.05); }

/* ===== Footer ===== */
footer{
    background:#2c2c3d;
    color:#eee;
    text-align:center;
    padding:25px;
    margin-top:60px;
    border-top:3px solid #b8860b;
}

/* ===== Responsive ===== */
@media(max-width:768px){
    .products { grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:20px; }
    header h1 { font-size:2rem; }
    .intro h2 { font-size:1.5rem; }
}
</style>
</head>
<body>

<header>
    <h1>💍 Cửa Hàng Trang Sức</h1>
    <p>Nơi tôn vinh vẻ đẹp và đẳng cấp của bạn</p>
</header>

<nav>
    <a href="index.php" class="active">Trang chủ</a>
    <a href="products.php">Sản phẩm</a>
    <a href="user/cart.php">🛒 Giỏ (<span id="cart-count">
        <?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'],'quantity')) : 0 ?>
    </span>)</a>

    <?php if(isset($_SESSION['username'])): ?>
        <a href="user/orders.php">Đơn hàng</a>
        <?php if($_SESSION['role']==='admin'): ?><a href="admin.php">Quản trị</a><?php endif; ?>
        <span class="user-info">Xin chào, <?= htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php" style="background:#ff6b6b;padding:5px 10px;border-radius:5px;color:#fff;text-decoration:none;">Đăng xuất</a>
    <?php else: ?>
        <a href="login.php">Đăng nhập</a>
        <a href="user/register.php">Đăng ký</a>
    <?php endif; ?>
</nav>

<section class="intro">
    <h2>✨ Về Cửa Hàng</h2>
    <p>
        Chào mừng bạn đến với <strong>Cửa hàng trang sức</strong> – điểm đến lý tưởng dành cho những tín đồ trang sức tinh tế.  
        Chúng tôi mang đến các thiết kế độc quyền, từ nhẫn, vòng tay, dây chuyền đến bông tai,  
        được chế tác từ chất liệu chọn lọc và tỉ mỉ trong từng chi tiết.  
        Mỗi sản phẩm không chỉ là món trang sức mà còn là một biểu tượng phong cách và cá tính.
    </p>
    <p>
        Với <strong>Cửa hàng trang sức</strong>, bạn sẽ trải nghiệm dịch vụ chuyên nghiệp, tư vấn tận tâm và chất lượng đảm bảo.  
        Hãy để chúng tôi tôn vinh vẻ đẹp, sự tự tin và đẳng cấp của bạn trong từng khoảnh khắc đặc biệt.
    </p>
</section>

<div class="container">
    <h2>Sản phẩm mới nhất</h2>
    <div class="products">
        <?php if($result && $result->num_rows): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="product">
                    <img src="images/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                    <h3><?= htmlspecialchars($row['name']); ?></h3>
                    <p class="price"><?= number_format($row['price']); ?> VNĐ</p>
                    <p class="category"><?= htmlspecialchars($row['category']); ?></p>

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
            <p style="grid-column:1/-1;text-align:center;">Hiện chưa có sản phẩm nào!</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    &copy; <?= date('Y'); ?> Project1 – Tôn vinh vẻ đẹp của bạn
</footer>

<script>
document.querySelectorAll('.add-cart-btn').forEach(btn=>{
    btn.addEventListener('click', async ()=>{
        const form = btn.closest('.add-to-cart-form');
        const res = await fetch('user/add_to_cart.php', {
            method:'POST',
            body:new FormData(form)
        });
        const data = await res.json();
        if(data.success){
            document.getElementById('cart-count').textContent = data.totalQty;
            alert('✅ Đã thêm vào giỏ!');
        }else{
            alert('❌ Lỗi thêm sản phẩm');
        }
    });
});
</script>

</body>
</html>
<?php $mysqli->close(); ?>
