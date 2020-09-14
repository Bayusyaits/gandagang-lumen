<?php

namespace App\Notifications;

/**
 * queue
*/
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * mail
*/
use App\Mail\InvoicePaid as Mailable;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class SentNotification
{
    // ...

    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('test@example.com', 'Example')
            ->subject('Notification Subject')
            ->line('...');
    }


    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed $notifiable
     * @return SlackMessage
     */
    public function toSlack($message)
    {
        $logger = new Logger($this->notification);
        $slackHandler = new \Monolog\Handler\SlackHandler(
            env('LOG_SLACK_TOKEN'),
            '#' . env('LOG_SLACK_CHANNEL', 'gandagang_be_lumen_throw_error'),
            'notification_errors'
        );
        $slackHandler->setLevel(\Monolog\Logger::ERROR);
        $logger->pushHandler($slackHandler);
        $logger->error($message);
    }

    public function toLog($message)
    {
        $logger = new Logger($this->notification);
        $logger->pushHandler(new StreamHandler(
            '../storage/logs_notification/logs.log',
            Logger::DEBUG
        ));
        $logger->error($message);
    }
}
