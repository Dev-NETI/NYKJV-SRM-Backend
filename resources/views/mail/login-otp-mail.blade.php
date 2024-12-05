@include('mail.EmailHeadFormat')
            <h1>Hello,</h1>
            <p>Your one-time password (OTP) for login is:</p>
            <h1>{{ $otp }}</h1>
            <p>This OTP is valid for 5 minutes.</p>
            <p>If you did not request this OTP, please ignore this email.</p>
@include('mail.EmailFooterFormat') 