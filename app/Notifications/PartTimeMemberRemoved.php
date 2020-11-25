<?php

namespace App\Notifications;

use App\Channels\Messages\DiscordMessage;
use App\Channels\WebhookChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PartTimeMemberRemoved extends Notification
{
    use Queueable;

    /**
     * @var
     */
    private $user;
    /**
     * @var
     */
    private $member;

    /**
     * Create a new notification instance.
     *
     * @param $member
     * @param $partTimeDivision
     */
    public function __construct($member)
    {
        $this->member = $member;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WebhookChannel::class];
    }

    /**
     * @param $notifiable
     * @return array
     * @throws \Exception
     */
    public function toWebhook($notifiable)
    {
        $channel = $notifiable->settings()->get('slack_channel');
        $primaryDivision = $notifiable;

        return (new DiscordMessage())
            ->info()
            ->to($channel)
            ->fields([
                [
                    'name' => "**PART TIME MEMBER REMOVED** [TESTING - PLEASE IGNORE]",
                    'value' => addslashes(":door: {$this->member->name} [{$this->member->clan_id}] was removed from {$primaryDivision->name}, and they were a part-time member in your division")
                ],
                [
                    'name' => 'View Member Profile',
                    'value' => route('member', $this->member->getUrlParams())
                ]
            ])->send();
    }
}