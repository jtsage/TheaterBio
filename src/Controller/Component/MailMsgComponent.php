<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;

class MailMsgComponent extends Component
{
    public function sendIntNotify($from, $to, $content) {
        $this->Users = TableRegistry::get('Users');

        // Don't Sent Message From Self to Self
        if ( $from == $to ) { return false; }

        $toUser = $this->Users->findById($to)->first();
        $fromUser = $this->Users->findById($from)->first();

        // Unable to load either user.  This is bad.
        if ( empty($toUser) ) { return false; }
        if ( empty($fromUser) ) { return false; }

        $this->Messages = TableRegistry::get('Messages');

        $message = $this->Messages->newEntity();

        $message->user_id = $toUser->id;
        $message->note = $fromUser->first . " " . $fromUser->last . " sent you a message: " . $content;

        return $this->Messages->save($message);
    }

    public function sendExtNotify($from, $to, $template = "default", $subject = "", $content) {
        $this->Users = TableRegistry::get('Users');

        // Don't Sent Message From Self to Self
        if ( $from == $to ) { return false; }

        $toUser = $this->Users->findById($to)->first();
        $fromUser = $this->Users->findById($from)->first();

        // Unable to load either user.  This is bad.
        if ( empty($toUser) ) { return false; }
        if ( empty($fromUser) ) { return false; }

        // Don't send to a non-notified user
        if ( ! $toUser->is_notified ) { return false; }

        $email = new Email('default');
        $email
            ->emailFormat('both')
            ->template($template)
            ->to($toUser->username)
            ->cc($fromUser->username)
            ->subject($subject)
            ->viewVars($content)
            ->send();

        return true;
    }
}
?>
