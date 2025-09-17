<?php
session_start();
require '../connect.php';

// Bắt buộc đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Lấy danh sách đơn của user
$sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$orders = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đơn hàng của tôi</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* ---------- RESET & BASE ---------- */
* { box-sizing: border-box; margin:0; padding:0; }
body { font-family:'Poppins', sans-serif; background:#f7f7f7; color:#333; line-height:1.6; }

/* ---------- HEADER ---------- */
header{
    background: linear-gradient(135deg,#ffecd2,#fcb69f);
    color:#b85c00;
    padding:25px 20px;
    text-align:center;
    font-size:1.8rem;
    font-weight:600;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
    border-radius:0 0 12px 12px;
}

/* ---------- CONTAINER ---------- */
.container{
    width:90%;
    max-width:1000px;
    margin:30px auto;
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

/* ---------- BACK LINK ---------- */
.back-home{
    display:inline-block;
    margin-bottom:20px;
    padding:10px 18px;
    background:#ff7f50;
    color:#fff;
    border-radius:8px;
    text-decoration:none;
    font-weight:500;
    transition:background .2s, transform .2s;
}
.back-home:hover{
    background:#e76b3a;
    transform:scale(1.03);
}

/* ---------- TABLE ---------- */
table{
    width:100%;
    border-collapse:collapse;
    font-size:15px;
}
th, td{
    border-bottom:1px solid #eee;
    padding:14px 10px;
    text-align:center;
}
th{
    background:#ff7f50;
    color:#fff;
    font-weight:600;
    text-transform:uppercase;
    font-size:14px;
}
tr:nth-child(even) td{ background:#fafafa; }
tr:hover td{ background:#fff3e0; transition:0.2s; }

a.detail-link{
    color:#ff7f50;
    text-decoration:none;
    font-weight:600;
    transition: color .2s;
}
a.detail-link:hover{ color:#e76b3a; text-decoration:underline; }

/* ---------- EMPTY MESSAGE ---------- */
.empty{
    text-align:center;
    padding:30px 0;
    font-size:16px;
    color:#555;
}

/* ---------- RESPONSIVE ---------- */
@media(max-width:768px){
    th, td{ font-size:13px; padding:10px 5px; }
    .back-home{ padding:8px 14px; font-size:14px; }
}
</style>
</head>
<body>

<header>
    📦 Đơn hàng của tôi
</header>

<div class="container">
    <a href="../index.php" class="back-home">⬅ Quay lại trang chủ</a>

    <?php if ($orders->num_rows > 0): ?>
        <table>
            <tr>
                <th>Mã đơn</th>
                <th>Tên người nhận</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Tổng tiền</th>
                <th>Ngày đặt</th>
                <th>Chi tiết</th>
            </tr>
            <?php while($o = $orders->fetch_assoc()): ?>
            <tr>
                <td>#<?php echo $o['id']; ?></td>
                <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($o['phone']); ?></td>
                <td><?php echo htmlspecialchars($o['address']); ?></td>
                <td><?php echo number_format($o['total']); ?> VNĐ</td>
                <td><?php echo $o['created_at']; ?></td>
                <td><a class="detail-link" href="order_detail.php?id=<?php echo $o['id']; ?>">Xem</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="empty">Bạn chưa có đơn hàng nào.</p>
    <?php endif; ?>
</div>

</body>
</html>
