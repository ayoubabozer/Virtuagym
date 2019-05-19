<?php
/**
 * Created by PhpStorm.
 * User: itay
 * Date: 15/05/19
 * Time: 14:07
 */
namespace classes\Mail;

use classes\Mail\PHPMailer\src\PHPMailer;

class Mail
{
    private $to;
    private $subject;
    private $message;
    private $mailer;


    public function __construct()
    {
        $this->to = "";
        $this->subject = "";
        $this->message = "";

        $this->mailer = new PHPMailer();



    }

    public function send()
    {
        $this->mailer->IsSMTP();
        $this->mailer->Host       = "smtp.gmail.com";
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Host       = "smtp.gmail.com";
        $this->mailer->Port       = 587;
        $this->mailer->Username   = "virtuagymayoub@gmail.com";
        $this->mailer->Password   = "v123v123";
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->SetFrom('virtuagymayoub@gmail.com', 'Virtuagym');
        $this->mailer->AddReplyTo("virtuagymayoub@gmail.com","Virtuagym");
        $this->mailer->Subject    = $this->subject;
        $this->mailer->AltBody    = "To view the message, please use an HTML compatible email viewer!";
        $this->mailer->MsgHTML($this->message);
        $address = $this->to;
        $this->mailer->AddAddress($address, $address);
        if(!$this->mailer->Send()) {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }



}