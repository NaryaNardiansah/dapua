<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Dapur Sakura</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .header {
            background: linear-gradient(135deg, #ec4899, #f472b6);
            padding: 40px 20px;
            text-align: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: 1px;
        }

        .content {
            padding: 40px 30px;
            color: #4b5563;
            line-height: 1.6;
        }

        .greeting {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
        }

        .text {
            margin-bottom: 30px;
            font-size: 16px;
        }

        .button-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .button {
            background: linear-gradient(135deg, #ec4899, #f472b6);
            color: #ffffff !important;
            padding: 14px 35px;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
            transition: all 0.3s ease;
        }

        .footer {
            background-color: #fdf2f8;
            padding: 30px;
            text-align: center;
            font-size: 13px;
            color: #9ca3af;
        }

        .social-text {
            color: #ec4899;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .divider {
            height: 1px;
            background-color: #f3f4f6;
            margin: 20px 0;
        }

        .note {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 20px;
        }

        .raw-link {
            color: #ec4899;
            word-break: break-all;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">Dapur Sakura 🍜</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Halo, {{ $user->name }}! 👋</div>
            <div class="text">
                Terima kasih telah bergabung dengan <strong>Dapur Sakura</strong>. Kami sangat senang Anda hadir untuk
                menikmati hidangan Jepang dan lokal terbaik kami.
                <br><br>
                Satu langkah lagi! Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda dan
                mengaktifkan akun Anda.
            </div>

            <!-- Action Button -->
            <div class="button-container">
                <a href="{{ $url }}" class="button">Verifikasi Email Sekarang</a>
            </div>

            <div class="text">
                Tombol verifikasi ini akan kedaluwarsa dalam 60 menit demi keamanan akun Anda.
            </div>

            <div class="divider"></div>

            <div class="note">
                Jika Anda tidak merasa mendaftar di akun ini, abaikan saja email ini.
                <br><br>
                Jika tombol di atas tidak berfungsi, Anda dapat menyalin dan menempelkan tautan berikut ke browser Anda:
                <br>
                <a href="{{ $url }}" class="raw-link">{{ $url }}</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="social-text">Ikuti perjalanan kuliner kami di Instagram @DapurSakura</div>
            &copy; {{ date('Y') }} Dapur Sakura Platform. All rights reserved.<br>
            Padang, Sumatera Barat, Indonesia
        </div>
    </div>
</body>

</html>