<?php
// ============================================
// ใส่ Omise Secret Key ตรงนี้
// ============================================
$OMISE_SECRET_KEY = 'skey_test_66znrg4ehnlfrlfnc9d';

$amount = 19900; // จำนวนเงิน (สตางค์)

// ตรวจสอบ Secret Key
if (strpos($OMISE_SECRET_KEY, 'xxxxxxxx') !== false) {
    die('กรุณาใส่ Omise Secret Key ในไฟล์ promptpay.php');
}

// สร้าง Charge ด้วย PromptPay
$chargeData = [
    'amount' => $amount,
    'currency' => 'thb',
    'description' => 'PromptPay Payment',
    'source[type]' => 'promptpay'
];

$result = createPromptPayCharge($OMISE_SECRET_KEY, $chargeData);

if (!$result['success']) {
    die('Error: ' . $result['error']);
}

$charge = $result['data'];
$qrCodeUrl = $charge['source']['scannable_code']['image']['download_uri'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>ชำระเงินด้วยพร้อมเพย์</title>
  <meta name="viewport" content="width=device-width, user-scalable=no" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <style>
    body { background: #f5f5f5; }
    .qr-container { max-width: 400px; margin: 50px auto; text-align: center; }
    .qr-box { background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .qr-code { margin: 20px 0; }
    .qr-code img { max-width: 250px; border: 1px solid #ddd; }
    .amount { font-size: 28px; color: #337ab7; font-weight: bold; margin: 20px 0; }
    .instructions { color: #666; margin: 20px 0; }
    .timer { font-size: 18px; color: #d9534f; margin: 15px 0; }
  </style>
</head>
<body>
  <div class="container qr-container">
    <div class="qr-box">
      <h2>สแกนเพื่อชำระเงิน</h2>
      <div class="amount"><?php echo number_format($amount / 100, 2); ?> THB</div>
      
      <?php if ($qrCodeUrl): ?>
        <div class="qr-code">
          <img src="<?php echo htmlspecialchars($qrCodeUrl); ?>" alt="PromptPay QR Code">
        </div>
      <?php else: ?>
        <div class="alert alert-warning">ไม่สามารถสร้าง QR Code ได้</div>
      <?php endif; ?>
      
      <div class="instructions">
        <p>1. เปิดแอพธนาคารบนมือถือ</p>
        <p>2. เลือกสแกน QR / พร้อมเพย์</p>
        <p>3. สแกน QR Code นี้เพื่อชำระเงิน</p>
      </div>
      
      <div class="timer">หมดอายุใน: <span id="countdown">10:00</span></div>
      
      <hr>
      <a href="index.html" class="btn btn-default">ยกเลิก</a>
      <a href="check-status.php?charge_id=<?php echo htmlspecialchars($charge['id']); ?>" class="btn btn-primary">ตรวจสอบสถานะ</a>
    </div>
  </div>

  <script>
    // นับถอยหลัง 10 นาที
    var timeLeft = 600;
    var countdownEl = document.getElementById('countdown');
    
    setInterval(function() {
      timeLeft--;
      var minutes = Math.floor(timeLeft / 60);
      var seconds = timeLeft % 60;
      countdownEl.textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
      
      if (timeLeft <= 0) {
        location.reload();
      }
    }, 1000);
  </script>
</body>
</html>
<?php
// ============================================
// ฟังก์ชันสร้าง Charge PromptPay
// ============================================
function createPromptPayCharge($secretKey, $data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.omise.co/charges');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_USERPWD, $secretKey . ':');
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($httpCode === 200 && isset($result['id'])) {
        return ['success' => true, 'data' => $result];
    } else {
        return ['success' => false, 'error' => $result['message'] ?? 'Unknown error'];
    }
}
?>
