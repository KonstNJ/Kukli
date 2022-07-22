<?php
use Phalcon\Incubator\Mailer\Manager as MailManager;
class Mail extends \Helper
{
    private $mailer;
    private $mailItem;
    public function __construct(MailManager $mailer)
    {
        $this->mailer = $mailer;
    }

    public function initContent(string $tempDir, array $param)
    {
        $_t = clone $this;
        $_t->mailItem = $_t->mailer
            ->createMessageFromView($tempDir, $param);
        return $_t;
    }

    public function contentSend(string $meil, string $subject, string $tempDir, array $param)
    {
        return $this->mailer
            ->createMessageFromView($tempDir, $param)
            ->to($meil)
            ->subject($subject)
            ->send();
    }

    public function initMessage($content)
    {
        $_t = clone $this;
        $_t->mailItem = $_t->mailer
            ->createMessage()
            ->content($content);
        return $_t;
    }

    public function send($email, $subject)
    {
        return $this->mailer
            ->createMessage()
            ->to($email)
            ->subject($subject)
            ->send();
    }
}