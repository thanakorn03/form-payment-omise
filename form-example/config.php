<?php
// ============================================
// Omise Configuration
// ============================================

// ใส่ Public Key จาก Omise Dashboard (ขึ้นต้นด้วย pkey_)
$OMISE_PUBLIC_KEY = 'pkey_test_66znrg3tfasdyeah7cs';

// ใส่ Secret Key จาก Omise Dashboard (ขึ้นต้นด้วย skey_)
// คำเตือน: อย่าแชร์ Secret Key นี้ให้ใครเห็น!
$OMISE_SECRET_KEY = 'skey_test_66znrg4ehnlfrlfnc9d';

// จำนวนเงินที่ต้องชำระ (หน่วยเป็นสตางค์)
// ตัวอย่าง: 19900 = 199.00 บาท
$PAYMENT_AMOUNT = 19900;

// สกุลเงิน
$PAYMENT_CURRENCY = 'thb';

// คำอธิบายการชำระเงิน
$PAYMENT_DESCRIPTION = 'Order payment';

// ============================================
// ตรวจสอบว่า config ถูกตั้งค่าหรือยัง
// ============================================
function isConfigValid() {
    global $OMISE_PUBLIC_KEY, $OMISE_SECRET_KEY;
    return strpos($OMISE_PUBLIC_KEY, 'xxxxxxxx') === false 
        && strpos($OMISE_SECRET_KEY, 'xxxxxxxx') === false;
}
?>
