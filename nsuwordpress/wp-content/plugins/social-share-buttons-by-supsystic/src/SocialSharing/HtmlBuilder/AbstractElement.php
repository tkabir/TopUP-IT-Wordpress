<?php

/**
 * Class SocialSharing_HtmlBuilder_AbstractElement
 */
abstract class SocialSharing_HtmlBuilder_AbstractElement
{
    /**
     * @var string
     */
    protected $tagName;

    /**
     * @var SocialSharing_HtmlBuilder_Attribute[]
     */
    protected $attributes;

    /**
     * @param string $tagName Tag name
     * @param array $attributes An array of the attributes
     */
    public function __construct($tagName, array $attributes = array())
    {
        $this->tagName = $tagName;
        $this->attributes = $attributes;
    }

    /**
     * Sets the tag name.
     * @param string $tagName Tag name
     * @return SocialSharing_HtmlBuilder_AbstractElement
     */
    public function setTagName($tagName)
    {
        $this->tagName = (string)$tagName;

        return $this;
    }

    /**
     * Returns tag name.
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Adds an attribute.
     * @param SocialSharing_HtmlBuilder_Attribute $attribute
     * @return $this
     */
    public function addAttribute(SocialSharing_HtmlBuilder_Attribute $attribute)
    {
        $this->attributes[] = $attribute;

        return $this;
    }

    /**
     * Returns attributes.
     * @return array|SocialSharing_HtmlBuilder_Attribute[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Returns the attribute by name.
     * @param string $name Attribute name
     * @return null|SocialSharing_HtmlBuilder_Attribute
     */
    public function getAttributeByName($name)
    {
        if (!$this->hasAttributes() || !$this->hasAttributeWithName($name)) {
            return null;
        }

        foreach ($this->attributes as $attribute) {
            if ($attribute->getName() === $name) {
                return $attribute;
            }
        }

        return null;
    }

    /**
     * Sets the attributes.
     * @param array $attributes An array of the attributes.
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $attribute) {
            $this->addAttribute($attribute);
        }
    }

    /**
     * Tests attribute instance.
     * @param SocialSharing_HtmlBuilder_Attribute $attribute Attribute instance
     * @return bool
     */
    public function hasAttribute(SocialSharing_HtmlBuilder_Attribute $attribute)
    {
        return false !== array_search($attribute, $this->attributes, true);
    }

    /**
     * Tests attributes length.
     * @return bool
     */
    public function hasAttributes()
    {
        return count($this->attributes) > 0;
    }

    /**
     * Removes attribute.
     * @param SocialSharing_HtmlBuilder_Attribute $attribute
     * @return bool
     */
    public function removeAttribute(SocialSharing_HtmlBuilder_Attribute $attribute)
    {
        if (false === $key = array_search($attribute, $this->attributes, true)) {
            return false;
        }

        unset($this->attributes[$key]);

        return true;
    }

    /**
     * Tests attribute by name.
     * @param string $attributeName Attribute name
     * @return bool
     */
    public function hasAttributeWithName($attributeName)
    {
        // PHP 5.2
        if (count($this->attributes) === 0) {
            return false;
        }

        foreach ($this->attributes as $attribute) {
            if ($attribute->getName() === $attributeName) {
                return true;
            }
        }

        return false;
    }

    /**
     * Compile all attributes in one string.
     * @return string
     */
    public function compileAttributes()
    {
        // PHP 5.2
        if (!$this->hasAttributes()) {
            return '';
        }

        $attributes = array();
        foreach ($this->attributes as $attribute) {
            $attributes[] = $attribute->compile();
        }

        return ' '.implode(' ', $attributes);
    }

    /**
     * Adds a child element.
     * @param SocialSharing_HtmlBuilder_AbstractElement $element
     * @return $this
     */
    abstract public function addElement(SocialSharing_HtmlBuilder_AbstractElement $element);

    /**
     * Removes a child element.
     * @param SocialSharing_HtmlBuilder_AbstractElement $element
     * @return bool
     */
    abstract public function removeElement(SocialSharing_HtmlBuilder_AbstractElement $element);

    /**
     * Returns all child elements.
     * @return SocialSharing_HtmlBuilder_AbstractElement[]
     */
    abstract public function getElements();

    /**
     * Returns the full html string.
     * @return string
     */
    abstract public function build();
}
