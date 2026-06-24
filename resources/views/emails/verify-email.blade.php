<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
</head>
<body style="font-family: Arial, sans-serif;">

<h2>Подтверждение подписки</h2>

<p>Вы подписались на уведомления об изменении цены объявления.</p>

<p><strong>Email:</strong> {{ $email }}</p>

<p>Для активации подписки нажмите кнопку ниже:</p>

<p>
    <a href="{{ $url }}"
       style="display:inline-block;padding:10px 20px;background:#4CAF50;color:#fff;text-decoration:none;border-radius:5px;">
        Подтвердить email
    </a>
</p>

<p style="color:#888;">
    Ссылка действительна до: {{ $expiresAt }}
</p>

<hr>

<p style="font-size:12px;color:#aaa;">
    Если вы не создавали подписку — просто игнорируйте это письмо.
</p>

</body>
</html>
