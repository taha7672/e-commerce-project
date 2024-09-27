<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .email-container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #004085;
            padding: 20px;
            text-align: center;
        }

        .email-header img {
            max-width: 150px;
            height: auto;
        }

        .email-body {
            padding: 20px;
        }

        .email-body h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .email-body p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .email-body .verification-code {
            font-size: 20px;
            font-weight: bold;
            color: #004085;
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .email-footer {
            padding: 20px;
            background-color: #004085;
            color: #ffffff;
            text-align: center;
            font-size: 14px;
        }

        .email-footer a {
            color: #ffffff;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="email-container">


        <!-- Body Content -->
        <div class="email-body">
            <h1>Email Verification</h1>
            <p>Dear user,</p>
            <p>Thank you for registering with us. Please use the verification code below to complete your registration:
            </p>
            <div class="verification-code">{{ $code }}</div>
            <p>If you did not create an account, no further action is required.</p>
        </div>


    </div>
</body>

</html>
