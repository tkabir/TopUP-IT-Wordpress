<?php

/**
 * Class SocialSharing_HtmlBuilder_Element
 */
class SocialSharing_HtmlBuilder_Element extends SocialSharing_HtmlBuilder_AbstractElement
{
    /**
     * @var SocialSharing_HtmlBuilder_AbstractElement[]
     */
    private $elements;

    /**
     * {@inheritdoc}
     */
    public function __construct($tagName, array $attributes = array())
    {
        parent::__construct($tagName, $attributes);
        $this->elements = array();
    }

    /**
     * {@inheritdoc}
     */
    public function addElement(SocialSharing_HtmlBuilder_AbstractElement $element)
    {
        $this->elements[] = $element;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeElement(SocialSharing_HtmlBuilder_AbstractElement $element)
    {
        if (false === $key = array_search($element, $this->elements, true)) {
            return false;
        }

        unset($this->elements[$key]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $elements = array();

        if (count($this->elements) > 0) {
            foreach ($this->elements as $element) {
                $elements[] = $element->build();
            }
        }

        return '<'.$this->tagName.$this->compileAttributes().'>'.implode('', $elements).'</'.$this->tagName.'>';
    }
}
