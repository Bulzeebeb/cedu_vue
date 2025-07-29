<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>OTP Code</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      color: #333;
    }
    .container {
      max-width: 600px;
      margin: auto;
      padding: 30px;
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    .otp {
      font-size: 32px;
      font-weight: bold;
      color: #d97706;
      text-align: center;
      margin: 20px 0;
    }
    .footer {
      text-align: center;
      font-size: 12px;
      color: #aaa;
      margin-top: 30px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Hello,</h2>
    <p>You requested to change your password. Please use the OTP code below to verify this action:</p>

    <div class="otp">{{ $otp }}</div>

    <p>This OTP is valid for a short time only. If you did not request this change, please ignore this email.</p>

    <p>Thank you,<br><strong>CEDU Onsite Team</strong></p>

    <div class="footer">
      &copy; {{ date('Y') }} CEDU Onsite. All rights reserved.
    </div>
  </div>
</body>
</html>
