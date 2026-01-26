<!doctype html>
<html>

<body style="font-family: Arial, Helvetica, sans-serif; color:#111; background:#f9fafb; padding:24px;">
  <table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px; margin:0 auto; background:#ffffff; border:1px solid #e5e7eb; border-radius:12px;">
    <tr>
      <td style="padding:20px 24px; border-bottom:1px solid #e5e7eb;">
        <h2 style="margin:0; font-size:20px; color:#111827;">Your Admin Account</h2>
        <p style="margin:6px 0 0; color:#6b7280; font-size:13px;">This email contains your initial credentials.</p>
      </td>
    </tr>
    <tr>
      <td style="padding:24px;">
        <p style="margin:0 0 12px;">Hi <strong><?= html_escape($fullname) ?></strong>,</p>
        <p style="margin:0 0 12px;">An administrator account has been created for you. Use the credentials below to sign in.</p>

        <table cellpadding="0" cellspacing="0" style="width:100%; background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; margin:12px 0;">
          <tr>
            <td style="padding:12px 14px; font-size:14px; color:#374151; border-bottom:1px solid #e5e7eb;">
              <strong>Username / Email:</strong> <?= html_escape($username) ?>
            </td>
          </tr>
          <tr>
            <td style="padding:12px 14px; font-size:14px; color:#374151;">
              <strong>Password:</strong> <?= html_escape($plainPassword) ?>
            </td>
          </tr>
        </table>

        <?php if (!empty($loginUrl)): ?>
          <p style="margin:12px 0 0;">
            <a href="<?= html_escape($loginUrl) ?>" style="display:inline-block; background:#2563eb; color:#fff; text-decoration:none; padding:10px 16px; border-radius:10px; font-weight:700;">
              Go to Login
            </a>
          </p>
        <?php endif; ?>

        <p style="margin:16px 0 0; color:#374151;">For security, please log in and <strong>change your password immediately</strong>.</p>

        <p style="margin:24px 0 0; color:#6b7280; font-size:13px;">
          If you didn't expect this email, you can ignore it.
        </p>
      </td>
    </tr>
    <tr>
      <td style="padding:16px 24px; border-top:1px solid #e5e7eb; color:#6b7280; font-size:12px;">
        Sent by the JobMatch DavOr system.
      </td>
    </tr>
  </table>
</body>

</html>