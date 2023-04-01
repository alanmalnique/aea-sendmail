<?php

declare(strict_types=1);

namespace App\Sender;

use App\Template\TemplateProvider;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use App\Template\TemplateManager;

final class EmailSender
{
    private array $fields = [];
    private array $data = [];
    private string $smtpHost;
    private string $smtpUser;
    private string $smtpPass;
    private string $smtpPort;
    private string $emailFrom;
    private string $emailFromName;
    private string $emailTo;
    private array $cc;
    private array $attachments;

    private PHPMailer $sender;
    private TemplateManager $templateManager;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
        $this->data = isset($this->fields['data']) ? $this->fields['data'] : [];
        $this->sender = new PHPMailer(true);
        $this->templateManager = new TemplateManager();
        $this->smtpHost = getenv('SMTP_HOST');
        $this->smtpUser = getenv('SMTP_USER');
        $this->smtpPass = getenv('SMTP_PASS');
        $this->smtpPort = getenv('SMTP_PORT');
        $this->emailFrom = isset($this->fields['from']) ? $this->fields['from'] : getenv('EMAIL_FROM');
        $this->emailFromName = isset($this->fields['from_name']) ? $this->fields['from_name'] : getenv('EMAIL_FROM_NAME');
        $this->emailTo = isset($this->fields['to']) ? $this->fields['to'] : getenv('EMAIL_TO');
        $this->cc = isset($this->fields['cc']) ? $this->fields['cc'] : [];
        $this->attachments = isset($this->fields['attachments']) ? $this->fields['attachments'] : [];
    }

    public function send(string $template): void
    {
        $emailTemplate = $this->getEmailTemplate($template);
        
        $this->sender->SMTPDebug = SMTP::DEBUG_OFF;
        $this->sender->isSMTP();
        $this->sender->Host = $this->smtpHost;
        $this->sender->SMTPAuth = true;
        $this->sender->Username = $this->smtpUser;
        $this->sender->Password = $this->smtpPass;
        $this->sender->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->sender->Port = $this->smtpPort;

        //Recipients
        $this->sender->setFrom($this->emailFrom, $this->emailFromName);
        $this->sender->addAddress($this->emailTo);
        
        if (!empty($this->cc)) {
            foreach ($this->cc as $email) {
               	$this->sender->addCC($email);
            }
        }
        
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $this->sender->addAttachment($attachment);
            }
        }

        $this->sender->isHTML(true);
        $this->sender->Subject = $this->fields['subject'];
        $this->sender->Body = $emailTemplate;
        $this->sender->AltBody = 'Loading email...';
        $this->sender->CharSet = "UTF-8";

        $this->sender->send();
    }

    private function getEmailTemplate(string $template): string
    {
        $templateFile = TemplateProvider::getFromString($template);
        return $this->templateManager->loadFile($templateFile)
            ->replace($this->data)
            ->build();
    }
}