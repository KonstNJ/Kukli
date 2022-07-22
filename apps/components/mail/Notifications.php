<?php

class Notifications extends \Helper
{
    /**
     * @param string $mail
     * @return mixed
     */
    public function subscribe(string $mail)
    {
        try {
            $local = $this->getDI()->getShared('local')::getContent('content');
            $header = $local->_('mail_subscribe_header');
            $param = [
                'title' => $header,
                'btn_title' => $local->_('mail_subscribe_btn'),
                'link' => 'https://everigin.com/service/unsubscribe/?key='.$mail,
                'content' => $local->_('mail_confirm_messages', [
                    'link' => 'https://everigin.com/service/unsubscribe/?key='.$mail,
                ])
            ];
            $mailer = $this->getDI()->getShared('mail');
            return $mailer->contentSend($mail, $header, 'emailTemplates/subscribe', $param);
        } catch (Exception $e)
        {
            error_log($e->getMessage() . PHP_EOL . $e->getTraceAsString());
            return false;
        }
    }
    /**
     * @param string $mail
     * @param string $confirm
     * @param string $name
     * @return mixed
     */
    public function registration(string $mail, string $confirm, string $name)
    {
        try {
            $local = $this->getDI()->getShared('local')::getContent('users');
            $header = $local->_('mail_confirm_header');
            $param = [
                'confirm' => $confirm,
                'title' => $header,
                'btn_title' => $local->_('mail_confirm_btn'),
                'link' => 'https://everigin.com/auth/confirm/?key='.$confirm,
                'content' => $local->_('mail_confirm_messages', [
                    'link' => 'https://everigin.com/auth/confirm/?key='.$confirm,
                    'name' => $name
                ])
            ];
            $mailer = $this->getDI()->getShared('mail');
            return $mailer->contentSend($mail, $header, 'emailTemplates/confirm', $param);
        } catch (Exception $e)
        {
            error_log($e->getMessage() . PHP_EOL . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * @param string $mail
     * @param string $confirm
     * @param string $name
     * @return mixed
     */
    public function forgot(string $mail, string $confirm)
    {
        try {
            $local = $this->getDI()->getShared('local')::getContent('users');
            $header = $local->_('mail_forgot_header');
            //$forgot_link = 'https://everigin.com/auth/forgot/'.$confirm;
            $forgot_link = 'https://everigin.com/auth/code/?key='.$confirm;
            $param = [
                'confirm' => $confirm,
                'title' => $header,
                'btn_title' => $local->_('mail_forgot_btn'),
                'link' => $forgot_link,
                'content' => $local->_('mail_forgot_messages', [
                    'link' => $forgot_link,
                ])
            ];
            $mailer = $this->getDI()->getShared('mail');
            return $mailer->contentSend($mail, $header, 'emailTemplates/forgot', $param);
        } catch (Exception $e)
        {
            error_log($e->getMessage() . PHP_EOL . $e->getTraceAsString());
            return false;
        }
    }

    public function notice()
    {
    }

    public function order()
    {
    }

}