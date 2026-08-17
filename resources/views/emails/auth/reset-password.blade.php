<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>استعادة كلمة المرور | إحسان</title>
</head>

<body style="margin:0;background:#f3eee5;font-family:Tahoma,Arial,sans-serif;color:#334155">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3eee5;padding:32px 12px">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:600px;background:#ffffff;border-radius:20px;overflow:hidden;border:1px solid #dfe6d5">
                    <tr>
                        <td style="background:#31421e;padding:30px;text-align:center;color:#ffffff">
                            <div style="font-size:30px;font-weight:800">إحسان</div>
                            <div style="margin-top:8px;color:#cdd9bd;font-size:14px">العطاء أقرب، والرعاية أسهل.</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 30px;text-align:right">
                            <h1 style="margin:0;color:#31421e;font-size:24px">استعادة كلمة المرور</h1>
                            <p style="margin:20px 0 0;line-height:1.9">مرحبًا {{ $user->name }}،</p>
                            <p style="margin:10px 0 0;line-height:1.9;color:#64748b">وصلنا طلب لتغيير كلمة المرور الخاصة
                                بحسابك في منصة إحسان. اضغط على الزر التالي لإنشاء كلمة مرور جديدة.</p>
                            <div style="text-align:center;margin:30px 0">
                                <a href="{{ $resetUrl }}"
                                    style="display:inline-block;background:#31421e;color:#ffffff;text-decoration:none;padding:14px 28px;border-radius:10px;font-weight:700">إنشاء
                                    كلمة مرور جديدة</a>
                            </div>
                            <p style="margin:0;line-height:1.9;color:#64748b;font-size:14px">إذا لم تطلب تغيير كلمة
                                المرور، تجاهل هذه الرسالة وسيبقى حسابك آمنًا.</p>
                            <p style="margin:24px 0 0;color:#31421e;font-weight:700">فريق إحسان</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#eef2e8;padding:18px 30px;text-align:center;color:#718256;font-size:12px">
                            هذه رسالة آلية من منصة إحسان، يرجى عدم الرد عليها.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
