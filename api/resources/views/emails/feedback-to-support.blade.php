<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 0 auto;">
    <tr>
        <td style="padding: 20px; background-color: #f4f4f4; border-radius: 8px;">
            <h1 style="font-family: Arial, sans-serif; color: #333;">
                {{ $data->userName }} sends you a feedback message.
            </h1>
            <p style="font-family: Arial, sans-serif; color: #666; line-height: 1.5;">
                Subject:
            </p>
            <p style="font-family: Arial, sans-serif; color: #666; line-height: 1.5;">
                {{ $data->subject }}
            </p>
            <br>
            <p style="font-family: Arial, sans-serif; color: #666; line-height: 1.5;">
                Message:
            </p>
            <p style="font-family: Arial, sans-serif; color: #666; line-height: 1.5;">
                {{ $data->message }}
            </p>
            <table role="presentation" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
                <tr>
                    <td style="background-color: #007bff; border-radius: 5px; text-align: center;">
                        <a href="{{ $resetLink }}"
                            style="display: inline-block; padding: 10px 20px; color: #fff; text-decoration: none; font-family: Arial, sans-serif;">Reset
                            Password</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
