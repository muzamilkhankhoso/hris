<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        table, td, div, h1, p {font-family: Arial, sans-serif;}
    </style>
</head>
<body style="margin:0;padding:0;background-color:#D1D0CE">
<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#D1D0CE;">
    <tr>
        <td align="center" style="padding:0;">
            <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                <tr>
                    <td width="50%" style="background:white;color:black">
                        <img src="{{asset('public/logo/nasr.png')}}" alt=""  style="display:block;" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <table role="presentation" style="background:#ffffff;width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                            <tr>
                                <td  style="padding:36px 30px 42px 30px;">

                                    <h1 style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Application leave for approval </h1>
                                    <span style="font-family: Arial sans-serif;font-size: 15px;">Applicant Name:</span><span style="color: darkblue;font-family: Arial sans-serif;font-size: 16px;margin-left: 10px;">{{ $details['title'] }}</span>
                                    <p>{{ $details['body'] }}</p>
                                    <span style="margin:20px;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href="http://demo.smrhr.com/dc/userDashboard?m=12#Innovative" style="color:#483D8B;text-decoration:underline;">http://demo.smrhr.com/dc/userDashboard?m=12#Innovative</a></span>

                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px;background:#483D8B">
                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                            <tr>
                                <td style="padding:0;width:50%;" align="left">
                                    <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                        &reg; Innovative Net Company <?php echo date('Y-m-d H:i:s')?><br/>
                                    </p>
                                </td>
                                <td style="padding:0;width:50%;" align="right">
                                    <table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
                                        <tr>
                                            <td style="padding:0 0 0 10px;width:38px;">
                                                <a href="http://www.twitter.com/" style="color:#ffffff;"><img src="https://assets.codepen.io/210284/tw_1.png" alt="Twitter" width="38" style="height:auto;display:block;border:0;" /></a>
                                            </td>
                                            <td style="padding:0 0 0 10px;width:38px;">
                                                <a href="http://www.facebook.com/" style="color:#ffffff;"><img src="https://assets.codepen.io/210284/fb_1.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;" /></a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>