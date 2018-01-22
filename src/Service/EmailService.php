<?php

namespace NotificationBundle\Service;

use Html2Text\Html2Text;
use NotificationBundle\Exception\HtmlTemplateException;
use NotificationBundle\Exception\SubjectTemplateException;
use NotificationBundle\Model\Message;
use Symfony\Bundle\TwigBundle\TwigEngine;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

/**
 * Class EmailService
 *
 * @package NotificationBundle\Service
 */
class EmailService
{
    /**
     * @var string
     */
    const TYPE_HTML = 'text/html';

    /**
     * @var string
     */
    const TYPE_TXT = 'text/plain';

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var TwigEngine
     */
    protected $twig;

    /**
     * @var Html2Text
     */
    protected $html2text;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var Message
     */
    private $message;

    /**
     * EmailService constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param TwigEngine    $twig
     * @param Html2Text     $html2Text
     * @param array         $params
     */
    public function __construct(
        \Swift_Mailer $mailer,
        TwigEngine $twig,
        Html2Text $html2Text,
        array $params
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->html2text = $html2Text;
        $this->params = $params;
        $this->createMessage();
    }

    /**
     * @return void
     */
    public function createMessage()
    {
        $this->message = new Message();

        $this->message
            ->setFrom($this->params['from'])
            ->setReplyTo($this->params['reply_to']);

        foreach ((array) $this->params['template']['css'] as $css) {
            $this->message->addCss($css);
        }
    }

    /**
     * @param string|array $email
     * @return $this
     */
    public function setTo($email)
    {
        if (is_string($email)) {
            $this->message->addTo($email);
        } elseif (is_array($email)) {
            $this->message->setTo($email);
        }

        return $this;
    }

    /**
     * @param string $template
     * @param array  $params
     * @return $this
     */
    public function setTemplate($template, array $params)
    {
        $this->message->setTemplate($template);
        $this->message->setParams($params);

        return $this;
    }

    /**
     * @param \Swift_Mime_Attachment $attachment
     * @return $this
     */
    public function addAttach(\Swift_Mime_Attachment $attachment)
    {
        $this->message->addFiles($attachment);

        return $this;
    }

    /**
     * @return int
     */
    public function send()
    {
        return $this->mailer->send($this->getMessage());
    }

    /**
     * @return \Swift_Message $message
     */
    protected function getMessage()
    {
        /** @var \Swift_Message $message */
        $message = $this
            ->mailer
            ->createMessage()
            ->setFrom($this->message->getFrom())
            ->setTo($this->message->getTo()->toArray())
            ->setSubject($this->getSubject());

        $this->html2text->setHtml($this->getHtmlMessage($message));

        $message->setBody($this->html2text->getHtml(), self::TYPE_HTML);
        $message->addPart($this->html2text->getText(), self::TYPE_TXT);

        foreach ($this->message->getFiles() as $file) {
            $message->embed($file);
        }

        return $message;
    }

    /**
     * @return string
     */
    protected function getSubject()
    {
        $path = implode('/', [
            $this->params['template']['path'],
            $this->message->getTemplate(),
            $this->params['template']['subject_name'],
        ]);

        if (!$this->twig->exists($path)) {
            throw new SubjectTemplateException('Subject template is not exist.');
        }

        return $this->render($path, $this->message->getParams());
    }

    /**
     * @param \Swift_Message $message
     * @return string
     */
    protected function getHtmlMessage(\Swift_Message $message)
    {
        $path = implode('/', [
            $this->params['template']['path'],
            $this->message->getTemplate(),
            $this->params['template']['html_name'],
        ]);

        if (!$this->twig->exists($path)) {
            throw new HtmlTemplateException('HTML template is not exist.');
        }

        $html = $this->render($path, $this->message->getParams());

        if ($this->message->getCss()) {
            $html = (new CssToInlineStyles())->convert($html, $this->message->getInlineCss());
        }

        foreach ($this->getImages($html) as $match) {
            $embed = $message->embed(\Swift_Image::fromPath($this->params['template']['image']['path'] . $match[1]));
            $html = str_replace($match[1], $embed, $html);
        }

        return $html;
    }

    /**
     * @param string $html
     * @return array
     */
    protected function getImages($html)
    {
        preg_match_all('/src="([^"]*)"/i', $html, $matches, PREG_SET_ORDER);

        return $matches;
    }

    /**
     * @param string $template
     * @param array  $params
     * @return string
     */
    protected function render($template, array $params)
    {
        return $this->twig->render(
            $template,
            array_merge(
                $params,
                ['utm_params' => implode('&', $this->params['utm'])]
            )
        );
    }
}
