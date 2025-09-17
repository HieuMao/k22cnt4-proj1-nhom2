<?php
session_start();
require '../connect.php';

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$message = "";
$prefillName = '';

if (isset($_SESSION['user_id'])) {
    $uid = intval($_SESSION['user_id']);
    $res = $mysqli->query("SELECT username FROM users WHERE id = $uid");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $prefillName = $row['username'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['customer_name']);
    $phone   = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if ($name && $phone && $address) {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

        $stmt = $mysqli->prepare(
            "INSERT INTO orders (user_id, customer_name, phone, address, total)
             VALUES (?,?,?,?,?)"
        );
        $stmt->bind_param("isssd", $user_id, $name, $phone, $address, $total);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        $stmtItem = $mysqli->prepare(
            "INSERT INTO order_items (order_id, product_id, quantity, price)
             VALUES (?,?,?,?)"
        );
        foreach ($_SESSION['cart'] as $id => $item) {
            $stmtItem->bind_param("iiid", $order_id, $id, $item['quantity'], $item['price']);
            $stmtItem->execute();
        }
        $stmtItem->close();

        unset($_SESSION['cart']);
        $message = "✅ Đặt hàng thành công! Cảm ơn bạn đã mua sắm.";
    } else {
        $message = "⚠️ Vui lòng nhập đầy đủ thông tin.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thanh toán</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
/* ---------- RESET ---------- */
*{box-sizing:border-box;margin:0;padding:0;}
body{
    font-family:'Poppins', sans-serif;
    background:#f7f7f7;
    color:#333;
    line-height:1.6;
}

/* ---------- HEADER ---------- */
header{
    background: linear-gradient(135deg, #ffecd2, #fcb69f);
    color: #b85c00;
    padding:25px 20px;
    text-align:center;
    font-size:1.8rem;
    font-weight:600;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

/* ---------- CONTAINER ---------- */
.container{
    max-width:600px;
    margin:40px auto;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
}

/* ---------- FORM ---------- */
h2{
    margin-bottom:25px;
    font-size:24px;
    color:#333;
    text-align:center;
}

label{
    display:block;
    margin-top:15px;
    font-weight:600;
    font-size:15px;
}

input[type="text"],
textarea{
    width:100%;
    padding:12px 14px;
    margin-top:6px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:15px;
    transition:border .2s, box-shadow .2s;
}
input[type="text"]:focus,
textarea:focus{
    border-color:#ff7f50;
    box-shadow:0 0 0 3px rgba(255,127,80,0.2);
    outline:none;
}

button{
    margin-top:25px;
    width:100%;
    padding:14px;
    background:#ff7f50;
    color:#fff;
    font-size:16px;
    font-weight:600;
    border:none;
    border-radius:8px;
    cursor:pointer;
    transition:background .25s, transform .2s;
}
button:hover{
    background:#e76b3a;
    transform:scale(1.02);
}

/* ---------- MESSAGE ---------- */
.msg{
    margin-bottom:20px;
    padding:14px;
    border-radius:8px;
    font-weight:600;
    text-align:center;
    font-size:15px;
}
.msg.success{background:#e6ffed;color:#1b7a34;border:1px solid #1b7a34;}
.msg.error{background:#ffeaea;color:#b91c1c;border:1px solid #b91c1c;}

/* ---------- BACK ---------- */
.back-home{
    display:inline-block;
    margin-top:20px;
    padding:10px 20px;
    background:#ff7f50;
    color:#fff;
    border-radius:8px;
    text-decoration:none;
    font-weight:500;
    transition:background .2s, transform .2s;
}
.back-home:hover{
    background:#e76b3a;
    transform:scale(1.02);
}

/* ---------- RESPONSIVE ---------- */
@media(max-width:600px){
    .container { padding:20px; margin:20px; }
    header { font-size:1.5rem; padding:20px 10px; }
}
</style>
</head>
<body>

<header>💳 Thanh toán</header>

<div class="container">
<?php if ($message): ?>
    <p class="msg <?php echo strpos($message,'thành công')!==false ? 'success':'error'; ?>">
        <?php echo $message; ?>
    </p>
    <div style="text-align:center;">
        <a href="../index.php" class="back-home">⬅ Quay lại mua sắm</a>
    </div>
<?php else: ?>
    <h2>Thông tin giao hàng</h2>
    <form method="post" action="">
        <label>Họ và tên
            <input type="text" name="customer_name"
                   value="<?php echo htmlspecialchars($prefillName); ?>" required>
        </label>

        <label>Số điện thoại
            <input type="text" name="phone" required>
        </label>

        <label>Địa chỉ
            <textarea name="address" rows="3" required></textarea>
        </label>

        <button type="submit">Đặt hàng</button>
    </form>
<?php endif; ?>
</div>

</body>
</html>
