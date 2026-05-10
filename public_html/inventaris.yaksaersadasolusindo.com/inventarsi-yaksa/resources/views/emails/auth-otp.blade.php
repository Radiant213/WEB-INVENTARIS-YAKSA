<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP Inventaris Yaksa</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #c53030; text-align: center; margin-bottom: 20px;">Inventaris Yaksa</h2>
        <p style="font-size: 16px; color: #333;">Halo,</p>
        
        @if($type === 'register')
            <p style="font-size: 16px; color: #333;">Terima kasih telah mendaftar di sistem Inventaris Yaksa. Berikut adalah kode OTP (One Time Password) untuk memverifikasi email Anda:</p>
        @else
            <p style="font-size: 16px; color: #333;">Anda telah meminta untuk mereset password akun Inventaris Yaksa Anda. Berikut adalah kode OTP untuk melakukan reset password:</p>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <span style="display: inline-block; padding: 15px 30px; font-size: 24px; font-weight: bold; background-color: #f7fafc; color: #2d3748; border: 2px dashed #cbd5e0; border-radius: 4px; letter-spacing: 5px;">
                {{ $otp }}
            </span>
        </div>

        <p style="font-size: 14px; color: #718096; text-align: center;">Kode ini akan kadaluarsa dalam 10 menit. Jangan berikan kode ini kepada siapapun.</p>
        
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;">
        <p style="font-size: 12px; color: #a0aec0; text-align: center;">&copy; {{ date('Y') }} PT Yaksa Ersada Solusindo. All rights reserved.</p>
    </div>
</body>
</html>
