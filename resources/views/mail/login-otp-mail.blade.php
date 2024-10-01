<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login OTP</title>
    <style>
        /* Email Body */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Container */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Logo */
        .logo {
            width: 100px;
            height: auto;
        }

        /* Content */
        .content {
            margin-bottom: 20px;
        }

        /* Button */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <img src="https://example.com/logo.png" alt="Logo" class="logo">
            <h2>Login OTP</h2>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>Your one-time password (OTP) for login is:</p>
            <h3>{{ $otp }}</h3>
            <p>This OTP is valid for 5 minutes.</p>
            <p>If you did not request this OTP, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>Best regards,<br>NETI</p>
        </div>
    </div>

</body>

</html>