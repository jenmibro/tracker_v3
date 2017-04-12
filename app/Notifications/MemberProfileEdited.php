<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class MemberProfileEdited extends Notification
{
    use Queueable;
    /**
     * @var
     */
    private $division;

    /**
     * Create a new notification instance.
     *
     * @param $division
     * @param $member
     */
    public function __construct($division, $member)
    {
        $this->member = $member;
        $this->division = $division;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack()
    {
        $to = ($this->division->settings()->get('slack_channel'))
            ?: '@' . auth()->user()->name;

        return (new SlackMessage())
            ->success()
            ->to($to)
            ->content($this->member->name . " was updated by " . auth()->user()->name);
    }
}
