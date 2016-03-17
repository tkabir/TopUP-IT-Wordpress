<?php

/**
 * Class SocialSharing_HtmlBuilder_Attribute
 */
class SocialSharing_HtmlBuilder_Attribute
{
    /**
     * Attribute name
     * @var string
     */
    private $name;

    /**
     * Values
     * @var array
     */
    private $values;

    /**
     * Values separator
     * @var string
     */
    private $separator;

    /**
     * @param string $name Attribute name
     * @param array $values Attribute values
     */
    public function __construct($name, array $values = array())
    {
        $this->name = $name;
        $this->values = $values;
        $this->separator = ' ';
    }

    /**
     * Casts attribute to string.
     * @return string
     */
    public function __toString()
    {
        return $this->compile();
    }

    /**
     * Sets attribute name.
     * @param string $name Attribute name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns attribute name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets attribute values.
     * @param array $values An array of the values.
     */
    public function setValues(array $values)
    {
        $this->values = $values;
    }

    /**
     * Returns attribute values.
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Sets separator.
     * @param string $separator Values separator.
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }

    /**
     * Returns separator.
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * Tests value.
     * @param string $value Value to check.
     * @param bool $strict Strict search
     * @return bool
     */
    public function inValues($value, $strict = true)
    {
        return in_array($value, $this->values, (bool)$strict);
    }

    /**
     * Adds a value.
     * @param string $value Value
     */
    public function addValue($value)
    {
        $this->values[] = $value;
    }

    /**
     * Removes value.
     * @param string $value Value
     * @param bool $strict Strict search
     * @return bool
     */
    public function removeValue($value, $strict = true)
    {
        if (false === $key = array_search($value, $this->values, $strict)) {
            return false;
        }

        unset($this->values[$key]);

        return true;
    }

    /**
     * Compiles attribute to string.
     * @return string
     */
    public function compile()
    {
        return $this->name.'="'.addslashes(implode($this->separator, $this->values)).'"';
    }
}