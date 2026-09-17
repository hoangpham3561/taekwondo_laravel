<!-- BEGIN:main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('client/images/favicon.png') }}">
    <title>TAEKWONDO ĐỒNG PHÚ - Xác Thực Tài Khoản</title>
</head>

<body style="margin:0px; ">
    <div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #333;">
        <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
            <!-- Header với Logo -->
            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px;background-color: #161c33;">
                <tbody>
                    <tr>
                        <td style="vertical-align: top;padding:30px 20px;" align="center">
                            <a href="{{ url('/') }}" style="text-decoration: none;">
                                <img src="{{ asset('client/images/favicon.png') }}" alt="Logo" style="border:none;height: 60px; max-width: 200px;">
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
                            Chào Mừng Đến TAEKWONDO ĐỒNG PHÚ
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
                                    Xin chào {{ isset($data['UserName']) ? $data['UserName'] : (isset($data['username']) ? $data['username'] : 'Bạn') }},
                                </h1>
                                <p style="margin-top:0px; color:#bbbbbb; font-size: 14px;">
                                    Cảm ơn bạn đã đăng ký tài khoản tại TAEKWONDO ĐỒNG PHÚ!
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:20px 0 30px 0;">
                                <center>
                                    <p style="font-size: 22px;text-transform:uppercase;font-weight: bold;color:#fdcd00; margin: 20px 0;">Chúc Mừng!</p>
                                    <p style="color:#fff; font-size: 15px; line-height: 1.6;">
                                        Bạn đã đăng ký thành công tài khoản tại TAEKWONDO ĐỒNG PHÚ.<br>
                                        Để kích hoạt tài khoản, vui lòng xác thực email của bạn bằng cách nhấp vào nút bên dưới.
                                    </p>
                                    <a target="_blank" href="{{ str_replace('http://', 'https://', route('.kyc', ['token' => $data['verify_token']])) }}" style="
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
                                ">Xác Thực Email</a>
                                    <p style="color:#bbbbbb; font-size: 13px; margin-top: 20px; line-height: 1.6;">
                                        Nếu nút trên không hoạt động, vui lòng sao chép và dán đường link sau vào trình duyệt:<br>
                                        @php
                                        $kycUrl = route('.kyc', ['token' => $data['verify_token']]);
                                        $kycUrl = str_replace('http://', 'https://', $kycUrl);
                                        @endphp
                                        <a href="{{ $kycUrl }}" style="color:#fdcd00; word-break: break-all;">
                                            {{ $kycUrl }}
                                        </a>
                                    </p>
                                </center>
                            </td>
                        </tr>
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
<!-- END:main -->