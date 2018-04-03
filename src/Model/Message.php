<?php

namespace NotificationBundle\Model;

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
     * @var array
     */
    private $to = [];

    /**
     * @var string
     */
    private $template;

    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    private $css = [];

    /**
     * @var array
     */
    private $files = [];

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return $this
     */
    public function setFrom(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getReplyTo(): string
    {
        return $this->replyTo;
    }

    /**
     * @param string $replyTo
     * @return $this
     */
    public function setReplyTo(string $replyTo): self
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * @return array
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return $this
     */
    public function addTo(string $to): self
    {
        $this->to = [];

        $this->to[] = $to;

        return $this;
    }

    /**
     * @param array $to
     * @return $this
     */
    public function setTo(array $to): self
    {
        $this->to = [];

        $this->to[] = $to;

        return $this;
    }

    /**
     * @return array
     */
    public function getCss(): array
    {
        return $this->css;
    }

    /**
     * @param string $css
     * @return $this
     */
    public function addCss(string $css): self
    {
        $this->css[] = $css;

        return $this;
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param \Swift_Mime_Attachment $file
     * @return $this
     */
    public function addFile(\Swift_Mime_Attachment $file): self
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }
}
