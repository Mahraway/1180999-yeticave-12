<?php

/**
 * Функция отправки письма на e-mail пользователя
 * @param array $mailer массив с параметрами SMTP
 * @param string $user_email e-mail пользователя
 * @param string $text тело e-mail сообщения
 */
function notify_winner(array $mailer, string $user_email, string $text): void
{
    $transport = new Swift_SmtpTransport($mailer['host'], $mailer['port'], $mailer['encryption']);
    $transport->setUsername($mailer['username']);
    $transport->setPassword($mailer['password']);

    $message = new Swift_Message('YetiCave');
    $message->setTo($user_email);
    $message->setBody($text, 'text/html');
    $message->setFrom($mailer['username'], "YetiCave");

    $mailer = new Swift_Mailer($transport);
    $mailer->send($message);
}
