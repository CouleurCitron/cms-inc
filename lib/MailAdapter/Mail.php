<?php
namespace lib\MailAdapter;

/**
 * Utilitaire de mail qui utilise SwiftMailer.
 *
 * @author Raphael GONCALVES <raphael@couleur-citron.com>
 */
class Mail
{
    private $message;
    
    private $transport;
    
    function __construct($engine, $host='', $user='', $pwd='', $port = 25, $host_encryption_type = null)
    {
        
        switch($engine){
            case 'smtp':
                if($host === '') throw new Exception('You have to specify a smtp host');
                $transport = \Swift_SmtpTransport::newInstance($host, $port, $host_encryption_type);
                if($user && $pwd){
                    $transport->setUsername($user)
                            ->setPassword($pwd);
                }
                    
                break;
            case "sendmail":
                $transport = \Swift_SendmailTransport::newInstance(SEND_MAIL_CMD);
                break;
            case "mail":
                $transport = \Swift_MailTransport::newInstance();
                break;
            default:
                throw new \Exception('You have to use a valid engine (smtp, sendmail or mail).');
                break;
        }
        
        
        $this->transport = $transport;
        
        // Create the message
        $message = \Swift_Message::newInstance();
        
        $this->message = $message;
        
        $this->setCharset();
    }
    
    /**
     * Ajoute un contenu au mail
     * @param string $message
     */
    public function addMessage($message)
    {
        $this->message->setBody($message);
    }
    
    /**
     * Ajoute un destinataire
     * 
     * @param array|string $to
     */
    public function setTo($to)
    {
        $this->message->setTo($to);
    }
    
    /**
     * Ajoute un envoyeur.
     * 
     * @param string $from
     * @param string $sender
     * @param string $ReplyTo
     */
    public function setFrom($from, $sender = '', $ReplyTo = '')
    {
        
        if($sender === '') $sender = $from;
        if($ReplyTo === '') $ReplyTo = $from;
        
        $this->message->setSender($sender)
                ->setReplyTo($ReplyTo)
                ->setFrom($from);
    }
    
    /**
     * Spécifie un charset au mail.
     * 
     * @param string $charset
     */
    public function setCharset($charset='iso-8859-1')
    {
        $this->message->setCharset($charset);
    }
    
    /**
     * Ajoute un header au mail
     * 
     * @param string $key
     * @param string $value
     * @throws \Exception
     */
    public function setHeaders($key, $value)
    {
        $func = 'set'.ucfirst($key);
        
        if(method_exists($this->message, $func)){
            $this->message->$func($value);
        } else throw new \Exception('The '.$func.' method doesn\'t exist.');
    }
    
    /**
     * Ajoute un fichier en pièce jointe.
     * 
     * @param string $filepath
     * @throws \Exception
     */
    public function addAttachment($filepath)
    {
        if(file_exists($filepath))
            $this->message->attach(Swift_Attachment::fromPath($filepath));
        else throw new \Exception('This file doesn\'t existe ('.$filepath.')');
    }
    
    /**
     * Ajoute un sujet
     * 
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->message->setSubject($subject);
    }
    
    /**
     * Envoie le mail
     * 
     * @param array $failures variable ou seront stockées les erreurs
     * 
     * @return integer un nombre de mail envoyés.
     */
    public function send(&$failures = '')
    {
        
        $failures = array();
        
        // Create the Mailer using your created Transport
        $mailer = \Swift_Mailer::newInstance($this->transport);
        
        // Send the message
        $send = $mailer->send($this->message, $failures);
        
        return $send;
    }
}
