<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 0 auto;">
    <tr>
        <td style="padding: 20px; background-color: #f4f4f4; border-radius: 8px;">
            <h1 style="font-family: Arial, sans-serif; color: #333;">Wizmeek</h1>
            <p style="font-family: Arial, sans-serif; color: #666; line-height: 1.5;">
                Your Password Has Been Successfully Reset
            </p>
            <p style="font-family: Arial, sans-serif; color: #666; line-height: 1.5;">
                Dear {{ $name }},
            </p>
            <p style="font-family: Arial, sans-serif; color: #666; line-height: 1.5;">
                Your password has been successfully reset. You can now log in with your new password.
            </p>
            <p style="font-family: Arial, sans-serif; color: #666; line-height: 1.5;">
                If you did not request this change, please contact our support team immediately.
            </p>
            <table role="presentation" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
                <tr>
                    <td style="background-color: #007bff; border-radius: 5px; text-align: center;">
                        <a href="{{ $loginLink }}" style="display: inline-block; padding: 10px 20px; color: #fff; text-decoration: none; font-family: Arial, sans-serif;">Sign In</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>