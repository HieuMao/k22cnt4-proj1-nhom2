<?php include 'admin_header.php'; ?>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background: #f5f6fa;
        margin: 0;
        padding: 20px;
    }
    h2 {
        color: #333;
        margin-bottom: 30px;
        font-size: 32px;
    }
    .dashboard {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .card {
        background: #fff;
        flex: 1 1 250px;
        padding: 25px 20px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
        text-align: center;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .card h3 {
        margin-bottom: 15px;
        color: #667eea;
        font-size: 20px;
    }
    .card p {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin: 0;
    }
</style>

<h2>Dashboard</h2>
<div class="dashboard">
    <div class="card">
        <h3>Tổng sản phẩm</h3>
        <p>100 sản phẩm</p>
    </div>
    <div class="card">
        <h3>Tổng đơn hàng</h3>
        <p>50 đơn hàng</p>
    </div>
    <div class="card">
        <h3>Tổng người dùng</h3>
        <p>200 người dùng</p>
    </div>
</div>
