<?php declare(strict_types=1);

namespace Application\Service;

use Laminas\Mail\Message;
use Laminas\Mail\Transport\Sendmail;

// This class is used to deliver an E-mail message to recipient.
class MailSender
{
    // Sends the mail message.
    public function sendMail($sender, $recipient, $subject, $text)
    {
        $result = false;
        try {
            // Create E-mail message
            $mail = new Message();
            $mail->setFrom($sender);
            $mail->addTo($recipient);
            $mail->setSubject($subject);
            $mail->setBody($text);

            // Send E-mail message
            $transport = new Sendmail('-f' . $sender);
            $transport->send($mail);
            $result = true;
        } catch (\Exception $e) {
            $result = false;
        }

        // Return status
        return $result;
    }
}
