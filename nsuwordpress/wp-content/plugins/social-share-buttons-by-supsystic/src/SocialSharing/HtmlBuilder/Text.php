<?php

/**
 * Class SocialSharing_HtmlBuilder_Text
 */
class SocialSharing_HtmlBuilder_Text extends SocialSharing_HtmlBuilder_AbstractElement
{
    /**
     * @var string
     */
    private $text;

    /**
     * @param string $text Text
     */
    public function __construct($text)
    {
        parent::__construct(null);
        $this->text = $text;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * {@inheritdoc}
     */
    public function addElement(SocialSharing_HtmlBuilder_AbstractElement $element)
    {
        throw new LogicException('Text can\'t have children.');
    }

    /**
     * {@inheritdoc}
     */
    public function removeElement(SocialSharing_HtmlBuilder_AbstractElement $element)
    {
        throw new LogicException('Text can\'t have children.');
    }

    /**
     * {@inheritdoc}
     */
    public function getElements()
    {
        throw new LogicException('Text can\'t have children.');
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        return htmlspecialchars($this->text);
    }
}
