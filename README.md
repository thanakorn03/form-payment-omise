# Omise Payment Integration

ระบบรับชำระเงินด้วย Omise รองรับบัตรเครดิตและ PromptPay

## ความต้องการเบื้องต้น

- PHP 7.0 ขึ้นไป
- Omise Account (https://dashboard.omise.co)

## การตั้งค่า

### 1. สร้างไฟล์ config.php

สร้างไฟล์ `form-example/config.php` และใส่ข้อมูล:

```php
<?php
// Omise Keys
$OMISE_PUBLIC_KEY = 'pkey_test_xxxxxxxxxxxxx';  // ใส่ Public Key
$OMISE_SECRET_KEY = 'skey_test_xxxxxxxxxxxxx';  // ใส่ Secret Key

// จำนวนเงิน (สตางค์)
$PAYMENT_AMOUNT = 19900;  // 199.00 บาท
?>
```

### 2. ขอ Keys จาก Omise Dashboard

1. เข้าไปที่ https://dashboard.omise.co
2. ไปที่ Settings → Keys
3. คัดลอก Public Key และ Secret Key (ใช้ Test Keys สำหรับทดสอบ)

### 3. เปิดใช้งาน PromptPay

1. ใน Omise Dashboard ไปที่ Settings → Payment Methods
2. เปิดใช้งาน "PromptPay"

## การรันระบบ

### วิธีที่ 1: ใช้ PHP Built-in Server

```bash
cd omise.js-example
php -S localhost:8000
```

เปิดเบราว์เซอร์ที่: http://localhost:8000/form-example/

### วิธีที่ 2: ใช้ http-server (Node.js)

```bash
npm install -g http-server
cd omise.js-example
http-server -p 8000
```

## โครงสร้างไฟล์

```
omise.js-example/
├── form-example/
│   ├── index.html          # หน้าเลือกวิธีชำระเงิน
│   ├── creditcard.html     # ฟอร์มบัตรเครดิต
│   ├── promptpay.php       # สร้าง QR Code
│   ├── checkout.php        # ประมวลผลบัตรเครดิต
│   ├── check-status.php    # ตรวจสอบสถานะ PromptPay
│   └── config.php          # ตั้งค่า Keys (สร้างเอง)
├── checkout.json           # การตั้งค่า checkout
└── README.md
```

## การทดสอบ

### ทดสอบบัตรเครดิต

ใช้หมายเลขบัตรทดสอบ:
- **Visa**: `4242 4242 4242 4242`
- **Mastercard**: `5555 5555 5555 4444`
- ใส่เดือน/ปี เป็นอนาคต
- ใส่ CVV อะไรก็ได้ เช่น `123`

### ทดสอบ PromptPay

1. กดเลือก "ชำระด้วยพร้อมเพย์"
2. สแกน QR Code ด้วยแอพธนาคารจริง
3. ชำระเงิน (เงินจริงจะไม่ถูกหักเพราะใช้ Test Mode)

## หมายเหตุ

- ใช้ Test Keys สำหรับการทดสอบเท่านั้น
- อย่า push Secret Key ขึ้น GitHub
- ใน production ควรใช้ HTTPS
