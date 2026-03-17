<?php
// ============================================
// ตรวจสอบสถานะการชำระเงิน
// ============================================
$OMISE_SECRET_KEY = 'skey_test_66znrg4ehnlfrlfnc9d';

$chargeId = isset($_GET['charge_id']) ? $_GET['charge_id'] : null;

if (!$chargeId) {
    die('ไม่พบรหัสการชำระเงิน');
}

$result = checkChargeStatus($OMISE_SECRET_KEY, $chargeId);

if (!$result['success']) {
    die('Error: ' . $result['error']);
}

$charge = $result['data'];
$status = $charge['status']; // pending, successful, failed
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>สถานะการชำระเงิน</title>
  <meta name="viewport" content="width=device-width, user-scalable=no" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <style>
    body { background: #f5f5f5; }
    .status-container { max-width: 400px; margin: 50px auto; text-align: center; }
    .status-box { background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .status-icon { font-size: 64px; margin-bottom: 20px; }
    .status-success { color: #5cb85c; }
    .status-pending { color: #f0ad4e; }
    .status-failed { color: #d9534f; }
  </style>
</head>
<body>
  <div class="container status-container">
    <div class="status-box">
      <?php if ($status === 'successful'): ?>
        <div class="status-icon status-success">
          <span class="glyphicon glyphicon-ok-circle"></span>
        </div>
        <h2>ชำระเงินสำเร็จ</h2>
        <p>ขอบคุณสำหรับการชำระเงิน</p>
        <p><strong>Charge ID:</strong> <?php echo htmlspecialchars($charge['id']); ?></p>
        <p><strong>Amount:</strong> <?php echo number_format($charge['amount'] / 100, 2); ?> THB</p>
        <a href="payment.php" class="btn btn-success">เสร็จสิ้น</a>
        
      <?php elseif ($status === 'failed'): ?>
        <div class="status-icon status-failed">
          <span class="glyphicon glyphicon-remove-circle"></span>
        </div>
        <h2>ชำระเงินล้มเหลว</h2>
        <p>กรุณาลองใหม่อีกครั้ง</p>
        <a href="payment.php" class="btn btn-danger">ลองใหม่</a>
        
      <?php else: ?>
        <div class="status-icon status-pending">
          <span class="glyphicon glyphicon-time"></span>
        </div>
        <h2>รอการชำระเงิน</h2>
        <p>กรุณาสแกน QR Code และชำระเงิน</p>
        <p>ระบบจะตรวจสอบอัตโนมัติ...</p>
        <a href="promptpay.php" class="btn btn-warning">กลับไป QR Code</a>
        <script>
          // รีเฟรชทุก 5 วินาทีเพื่อตรวจสอบสถานะ
          setTimeout(function() {
            location.reload();
          }, 5000);
        </script>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
<?php
// ============================================
// ฟังก์ชันตรวจสอบสถานะ
// ============================================
function checkChargeStatus($secretKey, $chargeId) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.omise.co/charges/' . $chargeId);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
