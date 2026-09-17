<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('client/images/favicon2.png') }}">
    <title>TAEKWONDO ĐỒNG PHÚ - Xác Nhận Thay Đổi Email</title>
</head>

<body style="margin:0px;">
    <div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%; width: 100%; color: #333;">
        <div style="max-width: 700px; padding:50px 0; margin: 0px auto; font-size: 14px">
            
            <!-- Header với Logo -->
            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px;background-color: #161c33;">
                <tbody>
                    <tr>
                        <td style="vertical-align: top;padding:30px 20px;" align="center">
                            <a href="{{ url('/') }}" style="text-decoration: none;">
                                <img src="{{ asset('client/images/logo2.png') }}" alt="Logo" style="border:none;height: 60px; max-width: 200px;">
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Title Section -->
            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px;">
                <tbody>
                    <tr>
                        <td style="vertical-align: top; font-size: 24px;text-transform:uppercase;font-weight: bold;color:#fdcd00" align="center">
                            XÁC NHẬN THAY ĐỔI EMAIL
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Content -->
            <div style="padding: 40px; background: #161c33; color:#fff; border-radius: 8px;">
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                    <tbody>
                        <tr>
                            <td style="">
                                <h1 style="font-size:18px; font-family:arial; margin:0px 0px 10px 0px; font-weight:bold; color:#fdcd00;">
                                    Xin chào {{ $data['FullName'] ?? $data['UserName'] }},
                                </h1>
                                <p style="margin-top:0px; color:#bbbbbb; font-size: 14px;">
                                    Chúng tôi nhận được yêu cầu thay đổi địa chỉ email cho tài khoản <strong style="color:#fdcd00;">{{ $data['UserName'] }}</strong>.
                                </p>
                            </td>
                        </tr>
                        
                        <!-- Thông tin Email -->
                        <tr>
                            <td style="padding:20px 0;">
                                <div style="background: #1f2844; padding: 20px; border-radius: 5px; border-left: 4px solid #fdcd00;">
                                    <p style="margin: 0 0 10px 0; color:#fff; font-size: 14px;">
                                        <strong style="color:#fdcd00;">📧 Email hiện tại:</strong><br>
                                        <span style="color:#bbbbbb;">{{ $data['OldEmail'] }}</span>
                                    </p>
                                    <p style="margin: 10px 0 0 0; color:#fff; font-size: 14px;">
                                        <strong style="color:#fdcd00;">📬 Email mới:</strong><br>
                                        <span style="color:#bbbbbb;">{{ $data['NewEmail'] }}</span>
                                    </p>
                                </div>
                            </td>
                        </tr>

                        <!-- Warning Box -->
                        <tr>
                            <td style="padding:20px 0;">
                                <div style="background: #fff3cd; padding: 20px; border-radius: 5px; border: 1px solid #ffc107;">
                                    <p style="margin: 0 0 10px 0; color:#856404; font-weight: bold;">
                                        ⚠️ LƯU Ý QUAN TRỌNG:
                                    </p>
                                    <ul style="margin: 10px 0 0 0; padding-left: 20px; color:#856404; font-size: 13px; line-height: 1.8;">
                                        <li>Nếu ĐÚNG là bạn yêu cầu thay đổi, vui lòng nhấn nút bên dưới để xác nhận</li>
                                        <li>Nếu KHÔNG phải bạn yêu cầu, vui lòng BỎ QUA email này và liên hệ với chúng tôi ngay</li>
                                        <li>Link xác nhận có hiệu lực đến: <strong>{{ $data['ExpiredAt'] }}</strong></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <!-- Button -->
                        <tr>
                            <td style="padding:20px 0 30px 0;">
                                <center>
                                    <p style="font-size: 22px;text-transform:uppercase;font-weight: bold;color:#fdcd00; margin: 20px 0;">
                                        Xác Nhận Ngay!
                                    </p>
                                    <p style="color:#bbbbbb; font-size: 15px; line-height: 1.6;">
                                        Để hoàn tất việc thay đổi email, vui lòng nhấp vào nút bên dưới.
                                    </p>
                                    <a target="_blank" href="{{ $data['VerifyUrl'] }}" style="
                                        display: inline-block;
                                        padding: 12px 40px;
                                        margin: 30px 0px;
                                        font-size: 16px;
                                        font-weight: bold;
                                        color: #161c33;
                                        background: #fdcd00;
                                        border-radius: 30px;
                                        text-decoration:none;
                                        text-transform:uppercase;
                                    ">Xác Nhận Thay Đổi Email</a>
                                    
                                    <p style="color:#bbbbbb; font-size: 13px; margin-top: 20px; line-height: 1.6;">
                                        Nếu nút trên không hoạt động, vui lòng sao chép và dán đường link sau vào trình duyệt:<br>
                                        <a href="{{ $data['VerifyUrl'] }}" style="color:#fdcd00; word-break: break-all;">
                                            {{ $data['VerifyUrl'] }}
                                        </a>
                                    </p>
                                </center>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td style="padding-top: 20px; border-top:1px solid #333; color:#bbbbbb; font-size: 13px;">
                                <p style="margin: 0 0 10px 0;">
                                    <strong style="color:#fdcd00;">Trân trọng,</strong><br>
                                    Đội ngũ TAEKWONDO ĐỒNG PHÚ
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer Contact -->
            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-top: 30px;">
                <tbody>
                    <tr>
                        <td style="text-align: center; padding: 20px; background: #fff; border-radius: 8px;">
                            <p style="margin: 0 0 10px 0; color: #333; font-size: 14px; font-weight: bold;">Liên Hệ Với Chúng Tôi</p>
                            <p style="margin: 5px 0; color: #666; font-size: 13px;">
                                📧 Email: <a href="mailto:info@antruongtho.com" style="color: #fdcd00; text-decoration: none;">info@antruongtho.com</a>
                            </p>
                            <p style="margin: 5px 0; color: #666; font-size: 13px;">
                                📞 Hotline: <a href="tel:0906000000" style="color: #fdcd00; text-decoration: none;">0906000000</a>
                            </p>
                            <p style="margin: 15px 0 0 0; color: #999; font-size: 12px;">
                                © {{ date('Y') }} TAEKWONDO ĐỒNG PHÚ. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</body>

</html>