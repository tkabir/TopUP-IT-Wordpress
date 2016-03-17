<?php


class SocialSharing_HtmlBuilder_Module extends SocialSharing_Core_BaseModule
{
    /**
     * @see SocialSharing_HtmlBuilder_Element::__construct()
     * @param string $tagName
     * @param array $attributes
     * @return SocialSharing_HtmlBuilder_Element
     */
    public function createElement($tagName, array $attributes = array())
    {
        return new SocialSharing_HtmlBuilder_Element($tagName, $attributes);
    }

    /**
     * @see SocialSharing_HtmlBuilder_Text::__construct()
     * @param string $text
     * @return SocialSharing_HtmlBuilder_Text
     */
    public function createTextElement($text)
    {
        return new SocialSharing_HtmlBuilder_Text($text);
    }

    /**
     * @see SocialSharing_HtmlBuilder_Attribute::__construct()
     * @param string $name
     * @param array|string $values
     * @return SocialSharing_HtmlBuilder_Attribute
     */
    public function createAttribute($name, $values = array())
    {
        if (!is_array($values)) {
            $values = array($values);
        }

        return new SocialSharing_HtmlBuilder_Attribute($name, $values);
    }
}