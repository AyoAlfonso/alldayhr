<?php

namespace App\Notifications;

use App\InterviewSchedule;
use App\JobApplication;
use App\Traits\SmtpSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmployeeResponse extends Notification
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(InterviewSchedule $schedule, $type, $userData)
    {
        $this->schedule = $schedule;
        $this->type = $type;
        $this->userData = $userData;
        $this->setMailConfigs();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('email.interviewSchedule.scheduleResponse'))
            ->greeting(__('email.hello').' ' . ucwords($notifiable->name) . '!')
            ->line(ucwords($this->userData->name).' '.__('email.interviewSchedule.employeeResponse' , ['type' => ucfirst($this->type),'job' => ucwords($this->schedule->jobApplication->job->title)]).' ' .ucwords($this->schedule->jobApplication->full_name))
            ->line(__('email.interviewOn').' - ' . $this->schedule->schedule_date->format('M d, Y h:i a'))
            ->line(__('email.thankyouNote'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'data' => $notifiable->toArray()
        ];
    }
}
