<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Mail;
use View;

class sendgridEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->baseUrl = config('app.baseurl');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'alfonsoayo7@gmail.com';
        $subject = 'This is a demo!';
        $name = 'Jane Doe';
        
        return $this->view('emails.templateUserRegister')
                    ->from($address, $name)
                    ->cc($address, $name)
                    ->bcc($address, $name)
                    ->replyTo($address, $name)
                    ->subject($subject)
                    ->with('message', "fddfb");
    }

    public function sendVerificationEmailDefault($user, $resendToken,$verificationUrl = null){
        
        $msgInfo= ['email' => $user->email , 'firstname' => $user->firstname, 'fromTitle'=> config('mail.from.address')];
        if($resendToken != NULL) {
         $verificationLink = route('candidate.verify-acc').'/'.$resendToken;;
        }
        if($user->remember_token) {
            $verificationLink = route('candidate.verify-acc').'/'.$user->remember_token;
        }
        if(!empty($verificationUrl)){
            $verificationLink = $verificationUrl;
        }
        Mail::send('emails.templateUserRegister',['username'=>$msgInfo['firstname'], 'verificationLink'=>$verificationLink],
            function($message) use ($msgInfo){
                $message->to(trim($msgInfo['email']), $msgInfo['firstname'])
                    ->from($msgInfo['fromTitle'], 'Allday HR')
                    ->subject('Allday HR | Welcome');
            }
        );
    }
    public function sendTaoLogin($user, $password, $login ) {

        $msgInfo = ['login'=> $login, 
        'password'=> $password, 'subject'=> 'Your AlldayHr Login' ,'fromTitle'=> 'AlldayHr Recruit', 'from' => "help@alldayhr.com" ];
        
        Mail::send('emails.taoInvitation', $msgInfo,
            function($message) use ($msgInfo, $user){
                $message->to(trim($user['email']), $user['fullname'])
                    ->from($msgInfo['from'], 'Allday HR')
                    ->subject($msgInfo['subject']);
            }
        );
    }

    public function sendResetPassword($token, $user, $notoken){
        $msgInfo= ['email' => $user->email , 'firstname' => $user->firstname, 'fromTitle'=> 'Hey', 'from' => "help@alldayhr.com" ];
        $changePasswordLink = route('candidate.recover-pass-notoken').'/'.$token;
        if ($notoken) {
            $changePasswordLink = route('candidate.recover-pass-notoken').'/'.$token;
        }
        Mail::send('emails.resetpass',['username'=>$msgInfo['firstname'], 'changePasswordLink'=>$changePasswordLink],
            function($message) use ($msgInfo){
                $message->to(trim($msgInfo['email']), $msgInfo['firstname'])
                    ->from($msgInfo['from'], 'Allday HR')
                    ->subject('Allday HR | Reset Password');
            }
        );
    }

    public function sendPoolCandidateEmail($user, $subject, $message){
    
        $msgInfo= ['email' => $user->email , 'firstname' => $user->firstname, 'fromTitle'=> 'Hey', 'from' => "help@alldayhr.com" ];
        Mail::send('emails.jobinvitation',['username'=>$msgInfo['firstname'], 'subject'=> $subject, 'messages'=> $message ],
            function($message) use ($msgInfo, $subject){
                $message->to(trim($msgInfo['email']), $msgInfo['firstname'])
                    ->from($msgInfo['from'], 'Allday HR')
                    ->subject($subject);
            }
        );
    }


    public function sendCandidateEmailOnApplication($user, $subject, $message){

        $msgInfo= ['email' => $user->email , 'firstname' => $user->firstname, 'fromTitle'=> 'Hey', 'from' => "help@alldayhr.com" ];
        Mail::send('emails.jobapplicationcomplete',['username'=>$msgInfo['firstname'], 'subject'=> $subject, 'messages'=> $message ],
            function($message) use ($msgInfo, $subject){
                $message->to(trim($msgInfo['email']), $msgInfo['firstname'])
                    ->from($msgInfo['from'], 'Allday HR')
                    ->subject('Your Allday HR Job Application');
            }
        );
    }

    public function testMessage(){
        $this->template_id = "b7f8caaa-4f38-4e75-acd4-ea5b7fcf80ea";
        $this->to = "vovwe@gmail.com";
        
        $this->subs = [
            "-yoursubcode-" => "Hello",
            "-verificationLink-" => "Hello"
        ];
        $this->subject = "Your Subject";
        $this->sendResetPassword();
    }

}
