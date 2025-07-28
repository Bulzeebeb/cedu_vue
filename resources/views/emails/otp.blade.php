<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your OTP Code</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      padding: 20px;
      color: #333;
    }

    .email-container {
      max-width: 600px;
      margin: auto;
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
    }

    .otp-code {
      font-size: 32px;
      font-weight: bold;
      color: #650000;
      text-align: center;
      margin: 20px 0;
      letter-spacing: 4px;
    }

    .footer {
      text-align: center;
      margin-top: 30px;
      font-size: 12px;
      color: #888;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="header">
      <h2>Cedu iCentral - Email Verification</h2>
    </div>

    <p>Hello,</p>

    <p>To complete your registration, please use the following One-Time Password (OTP):</p>

    <div class="otp-code">
      {{ $otp }}
    </div>

    <p>This code will expire in 5 minutes. Please do not share this code with anyone.</p>

    <p>If you did not request this verification, you can ignore this email.</p>

    <div class="footer">
      &copy; {{ date('Y') }} Cedu 2024. All rights reserved.
    </div>
  </div>
</body>
</html>
