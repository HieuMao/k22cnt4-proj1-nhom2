<?php
require 'connect.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Trang chủ - Project1</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<!-- ===== HERO ===== -->
<section class="hero">
  <div class="hero-content">
    <h1>Trang sức cho mọi khoảnh khắc</h1>
    <p>Khám phá những thiết kế tinh xảo, tôn vinh vẻ đẹp của bạn</p>
    <a href="products.php" class="btn-primary">Khám phá ngay</a>
  </div>
</section>

<!-- ===== GIỚI THIỆU ===== -->
<section class="intro">
  <h2>✨ Khám Phá Thế Giới Trang Sức Cao Cấp</h2>
  <p><strong>Cửa hàng của tôi</strong> mang đến những thiết kế tinh xảo,
     từ kim cương lấp lánh tới vàng 18K sang trọng – mỗi sản phẩm đều được
     chế tác tỉ mỉ bởi các nghệ nhân nhiều năm kinh nghiệm.</p>
  <p>Chúng tôi cam kết <strong>chất lượng chuẩn quốc tế</strong>, bảo hành uy tín
     và dịch vụ chăm sóc khách hàng tận tâm. Từ nhẫn cưới, dây chuyền,
     đến các mẫu trang sức cá nhân hoá, tất cả đều dành cho bạn.</p>
  <p><em>Hãy để trang sức tôn vinh phong cách và dấu ấn riêng của bạn.</em></p>
</section>

<!-- ===== SẢN PHẨM NỔI BẬT ===== -->
<section class="featured">
  <h2>Sản phẩm nổi bật</h2>
  <div class="product-grid">
    <div class="product-card">
      <img src="images/necklace.jpg" alt="Dây chuyền vàng">
      <p>Dây chuyền vàng 18K</p>
    </div>
    <div class="product-card">
      <img src="images/ring.jpg" alt="Nhẫn kim cương">
      <p>Nhẫn kim cương Classic</p>
    </div>
    <div class="product-card">
      <img src="images/earring.jpg" alt="Bông tai bạc">
      <p>Bông tai bạc Ý</p>
    </div>
  </div>
</section>

<!-- ===== ƯU ĐÃI / CAM KẾT ===== -->
<section class="benefits">
  <h2>Vì sao chọn chúng tôi?</h2>
  <ul>
    <li>🚚 Giao hàng nhanh 24h</li>
    <li>🛡️ Bảo hành 12 tháng</li>
    <li>💎 Thiết kế độc quyền</li>
    <li>💰 Đổi trả trong 7 ngày</li>
  </ul>
</section>

<!-- ===== NEWSLETTER ===== -->
<section class="newsletter">
  <h2>Đăng ký nhận ưu đãi</h2>
  <form>
    <input type="email" placeholder="Nhập email của bạn">
    <button type="submit">Đăng ký</button>
  </form>
  <div class="social">
    <a href="#"><i class="fa-brands fa-facebook"></i></a>
    <a href="#"><i class="fa-brands fa-instagram"></i></a>
    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
  </div>
</section>

<footer>
  &copy; <?= date('Y'); ?> Project1 – Tôn vinh vẻ đẹp của bạn
</footer>

</body>
</html>
<?php $mysqli->close(); ?>
