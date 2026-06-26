<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добро пожаловать в ONEONE</title>
</head>
<body style="margin: 0; padding: 0; background: #f8f8f8; font-family: 'Montserrat', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background: #f8f8f8; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: #111; padding: 40px; text-align: center;">
                            <h1 style="color: #fff; font-size: 28px; font-weight: 300; margin: 0; letter-spacing: 2px;">ONEONE</h1>
                            <p style="color: rgba(255,255,255,0.6); font-size: 14px; margin: 10px 0 0;">Минималистичная женская одежда</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="font-size: 22px; font-weight: 300; color: #111; margin: 0 0 15px;">
                                Добро пожаловать, {{ $subscriber->email }}! ✨
                            </h2>
                            
                            <p style="font-size: 15px; color: #555; line-height: 1.6; margin: 0 0 20px;">
                                Спасибо, что подписались на рассылку ONEONE. Теперь вы первыми будете узнавать о новых коллекциях, эксклюзивных акциях и закрытых мероприятиях.
                            </p>
                            
                            <table cellpadding="0" cellspacing="0" style="margin: 0 0 30px;">
                                <tr><td style="padding: 8px 0; font-size: 14px; color: #555;">
                                    ✓ Ранний доступ к новым коллекциям
                                </td></tr>
                                <tr><td style="padding: 8px 0; font-size: 14px; color: #555;">
                                    ✓ Приглашения на закрытые мероприятия
                                </td></tr>
                                <tr><td style="padding: 8px 0; font-size: 14px; color: #555;">
                                    ✓ Персональные рекомендации стилиста
                                </td></tr>
                                <tr><td style="padding: 8px 0; font-size: 14px; color: #555;">
                                    ✓ Эксклюзивные промокоды и скидки
                                </td></tr>
                            </table>
                            
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('catalog') }}" 
                                           style="display: inline-block; background: #111; color: #fff; text-decoration: none; padding: 14px 40px; font-size: 14px; font-weight: 500; letter-spacing: 1px; text-transform: uppercase; border-radius: 4px;">
                                            Перейти в каталог
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: #f5f5f5; padding: 25px 40px; text-align: center;">
                            <p style="font-size: 12px; color: #999; margin: 0 0 5px;">
                                © 2026 ONEONE. Все права защищены.
                            </p>
                            <p style="font-size: 11px; color: #bbb; margin: 0;">
                                Вы получили это письмо, потому что подписались на рассылку на сайте 
                                <a href="{{ url('/') }}" style="color: #888;">oneone-fashion.ru</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>