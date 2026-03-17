<?php
// ============================================
// ใส่ Omise Secret Key ตรงนี้ (ขึ้นต้นด้วย skey_)
// ============================================
$OMISE_SECRET_KEY = 'skey_test_66znrg4ehnlfrlfnc9d';

// รับค่า Omise Token จากฟอร์ม
$omiseToken = isset($_POST['omiseToken']) ? $_POST['omiseToken'] : null;

// ตรวจสอบว่ามี Token หรือไม่
if (!$omiseToken) {
    showError('No token received', 'กรุณากรอกข้อมูลบัตรและลองใหม่อีกครั้ง');
    exit;
}

// ตรวจสอบ Secret Key
if (strpos($OMISE_SECRET_KEY, 'xxxxxxxx') !== false) {
    showError('Secret Key not configured', 'กรุณาใส่ Omise Secret Key ในไฟล์ checkout.php');
    exit;
}

// ============================================
// Charge ด้วย Omise API
// ============================================
$chargeData = [
    'amount' => 19900, // จำนวนเงิน (สตางค์) = 199.00 บาท
    'currency' => 'thb',
    'card' => $omiseToken,
    'description' => 'Order payment'
];

$result = chargeWithOmise($OMISE_SECRET_KEY, $chargeData);

if ($result['success']) {
    showSuccess($result['data']);
} else {
    showError('Charge failed', $result['error']);
}

// ============================================
// ฟังก์ชัน Charge ผ่าน Omise API
// ============================================
function chargeWithOmise($secretKey, $data) {
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

// ============================================
// ฟังก์ชันแสดงผล
// ============================================
function showSuccess($charge) {
    ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Payment Successful</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3" style="margin-top: 50px;">
        <div class="panel panel-success">
          <div class="panel-heading">
            <h3 class="panel-title">ชำระเงินสำเร็จ</h3>
          </div>
          <div class="panel-body">
            <p><strong>Charge ID:</strong> <?php echo htmlspecialchars($charge['id']); ?></p>
            <p><strong>Amount:</strong> <?php echo number_format($charge['amount'] / 100, 2); ?> <?php echo strtoupper($charge['currency']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($charge['status']); ?></p>
            <hr>
            <a href="index.html" class="btn btn-primary">กลับไปหน้าชำระเงิน</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
    <?php
}

function showError($title, $message) {
    ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Error</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3" style="margin-top: 50px;">
        <div class="panel panel-danger">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo htmlspecialchars($title); ?></h3>
          </div>
          <div class="panel-body">
            <p><?php echo htmlspecialchars($message); ?></p>
            <hr>
            <a href="index.html" class="btn btn-primary">กลับไปหน้าชำระเงิน</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
    <?php
}
?>
