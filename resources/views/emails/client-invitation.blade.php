<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sembark Invitation</title>
</head>

<body style="margin:0;padding:0;background-color:#f5f4fb;font-family:Arial,Helvetica,sans-serif;color:#202637;">
    <div
        style="display:none;font-size:1px;color:#f5f4fb;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
        Your invitation to manage {{ $clientName }} on Sembark is ready.
    </div>

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
        style="background-color:#f5f4fb;">
        <tr>
            <td align="center" style="padding:32px 16px;">

                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                    style="max-width:600px;">

                    {{-- Brand --}}
                    <tr>
                        <td align="center" style="padding:0 0 24px;">
                            <div style="font-size:28px;font-weight:800;letter-spacing:-1px;color:#202637;">
                                Sembark<span style="color:#5924b9;">.</span>
                            </div>
                            <div
                                style="padding-top:3px;font-size:10px;font-weight:700;letter-spacing:2px;color:#687183;">
                                CLIENT PORTAL
                            </div>
                        </td>
                    </tr>

                    {{-- Main card --}}
                    <tr>
                        <td
                            style="background-color:#ffffff;border:1px solid #e9e5f3;border-radius:16px;overflow:hidden;">

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">

                                <tr>
                                    <td style="height:6px;background-color:#5924b9;font-size:1px;line-height:1px;">
                                        &nbsp;
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:38px 40px 34px;">

                                        <div
                                            style="display:inline-block;padding:7px 12px;border-radius:20px;background-color:#f1eafe;color:#5924b9;font-size:12px;font-weight:700;">
                                            {{ strtoupper($role) }} INVITATION
                                        </div>

                                        <h1
                                            style="margin:20px 0 12px;font-size:29px;line-height:1.25;letter-spacing:-0.6px;color:#202637;">
                                            You're invited to Sembark
                                        </h1>

                                        <p style="margin:0 0 20px;font-size:15px;line-height:1.8;color:#5e687a;">
                                            You have been invited to manage
                                            <strong style="color:#202637;">{{ $clientName }}</strong>
                                            as a {{ ucfirst($role) }} on Sembark.
                                        </p>

                                        <p style="margin:0 0 27px;font-size:15px;line-height:1.8;color:#5e687a;">
                                            Accept the invitation to set your password and activate your account.
                                        </p>

                                        {{-- Button --}}
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td align="center" bgcolor="#5924b9"
                                                    style="border-radius:9px;background-color:#5924b9;">
                                                    <a href="{{ $acceptUrl }}"
                                                        style="display:inline-block;padding:15px 26px;border-radius:9px;background-color:#5924b9;color:#ffffff;font-size:15px;font-weight:700;text-decoration:none;">
                                                        Accept Invitation
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                        <p
                                            style="margin:26px 0 0;padding:16px 18px;border-radius:9px;background-color:#f7f5fc;font-size:13px;line-height:1.7;color:#687183;">
                                            <strong style="color:#202637;">For your security:</strong>
                                            This link expires in 7 days and can only be used once.
                                        </p>

                                        <p style="margin:25px 0 8px;font-size:13px;line-height:1.6;color:#687183;">
                                            Button not opening? Copy and paste this link into your browser:
                                        </p>

                                        <p style="margin:0;font-size:12px;line-height:1.7;word-break:break-all;">
                                            <a href="{{ $acceptUrl }}"
                                                style="color:#5924b9;text-decoration:underline;">
                                                {{ $acceptUrl }}
                                            </a>
                                        </p>

                                        <div style="margin-top:28px;border-top:1px solid #eceaf2;"></div>

                                        <p style="margin:22px 0 0;font-size:13px;line-height:1.7;color:#687183;">
                                            If you weren't expecting this invitation, you can safely ignore this email.
                                        </p>

                                        <p style="margin:20px 0 0;font-size:14px;line-height:1.7;color:#202637;">
                                            Thanks,<br>
                                            <strong>Sembark Team</strong>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td align="center" style="padding:22px 14px 0;font-size:12px;line-height:1.7;color:#8a91a0;">
                            © {{ date('Y') }} Sembark. All rights reserved.
                            <br>
                            This is an automated invitation email.
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>

</html>
