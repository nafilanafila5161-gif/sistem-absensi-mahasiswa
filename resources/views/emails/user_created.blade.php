<!DOCTYPE html>
<html>
<head>
    <title>Akun Baru Anda</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .header { background: #0d6efd; color: white; padding: 10px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
        .footer { font-size: 0.8em; color: #777; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Sistem Absensi Kampus</h2>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $user->name }}</strong>!</p>
            <p>Akun Anda telah berhasil didaftarkan oleh Admin. Berikut adalah detail login Anda:</p>
            <table style="width: 100%; background: #f8f9fa; padding: 15px; border-radius: 5px;">
                <tr>
                    <td width="100"><strong>Email</strong></td>
                    <td>: {{ $user->email }}</td>
                </tr>
                <tr>
                    <td><strong>Password</strong></td>
                    <td>: <span style="color: #d63384; font-weight: bold;">{{ $password }}</span></td>
                </tr>
                <tr>
                    <td><strong>Role</strong></td>
                    <td>: {{ ucfirst($user->role) }}</td>
                </tr>
            </table>
            <p>Silakan gunakan informasi di atas untuk login ke dalam sistem. Kami sarankan Anda segera mengubah password setelah login pertama kali.</p>
            <p>Terima kasih!</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Sistem Absensi - All Rights Reserved.
        </div>
    </div>
</body>
</html>