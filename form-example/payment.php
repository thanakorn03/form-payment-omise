<?php
// ============================================
// หน้าเลือกวิธีชำระเงิน
// ============================================
$amount = 19900; // จำนวนเงิน (สตางค์) = 199.00 บาท
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>เลือกวิธีชำระเงิน</title>
  <meta name="viewport" content="width=device-width, user-scalable=no" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <style>
    body { background: #f5f5f5; }
    .payment-container { max-width: 500px; margin: 50px auto; }
    .payment-box { background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .payment-option { 
      display: block; 
      padding: 20px; 
      margin: 15px 0; 
      border: 2px solid #ddd; 
      border-radius: 8px; 
      text-decoration: none; 
      color: #333;
      transition: all 0.3s;
    }
    .payment-option:hover { 
      border-color: #337ab7; 
      background: #f8f9fa;
      text-decoration: none;
    }
    .payment-option i { font-size: 24px; margin-right: 15px; }
    .amount-display { 
      text-align: center; 
      font-size: 32px; 
      color: #337ab7; 
      margin-bottom: 30px;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container payment-container">
    <div class="payment-box">
      <h2 class="text-center">เลือกวิธีชำระเงิน</h2>
      <div class="amount-display">
        <?php echo number_format($amount / 100, 2); ?> THB
      </div>
      
      <a href="index.html" class="payment-option">
        <span class="glyphicon glyphicon-credit-card" style="font-size: 24px; margin-right: 15px;"></span>
        <strong>บัตรเครดิต / เดบิต</strong>
        <p class="text-muted" style="margin: 10px 0 0 40px;">ชำระด้วย Visa, Mastercard, JCB</p>
      </a>
      
      <a href="promptpay.php" class="payment-option">
        <span class="glyphicon glyphicon-qrcode" style="font-size: 24px; margin-right: 15px;"></span>
        <strong>พร้อมเพย์ (PromptPay)</strong>
        <p class="text-muted" style="margin: 10px 0 0 40px;">สแกน QR Code ด้วยแอพธนาคาร</p>
      </a>
    </div>
  </div>
</body>
</html>
