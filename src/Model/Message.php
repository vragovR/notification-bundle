<?php

namespace NotificationBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Message
 *
 * @package NotificationBundle\Model
 */
class Message
{
    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $replyTo;

    /**
     * @var ArrayCollection
     */
    private $to;

    /**
     * @var string
     */
    private $template;

    /**
     * @var array
     */
    private $params;

    /**
     * @var ArrayCollection
     */
    private $css;

    /**
     * @var ArrayCollection
     */
    private $files;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->to = new ArrayCollection();
        $this->css = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @param string $replyTo
     * @return $this
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return $this
     */
    public function addTo($to)
    {
        $this->to->add($to);

        return $this;
    }

    /**
     * @param array $listTo
     * @return $this
     */
    public function setTo(array $listTo)
    {
        foreach ($listTo as $to) {
            $this->to->add($to);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * @return string
     */
    public function getInlineCss()
    {
        $inline = '';
        foreach ($this->getCss() as $css) {
            $inline .= file_get_contents($css);
        }

        return $inline;
    }

    /**
     * @param string $css
     * @return $this
     */
    public function addCss($css)
    {
        $this->css->add($css);

        return $this;
    }

    /**
     * @return ArrayCollection|\Swift_Mime_Attachment[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param \Swift_Mime_Attachment $files
     * @return $this
     */
    public function addFiles(\Swift_Mime_Attachment $files)
    {
        $this->files->add($files);

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }
}
