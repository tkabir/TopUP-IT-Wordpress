<?php

class SocialSharing_Projects_ButtonSet
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $numberOfStyles;

    /**
     * @var int[]
     */
    private $stylesWithText;

    /**
     * Constructs the dto.
     */
    public function __construct($name, $numberOfStyles = 1, array $stylesWithText = array())
    {
        $this->name = (string)$name;
        $this->numberOfStyles = (int)$numberOfStyles;
        $this->stylesWithText = $stylesWithText;
    }

    /**
     * Sets the name of the set.
     * @param string $name The name of the set
     * @return SocialSharing_Projects_ButtonSet
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Returns the name of the set.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the lowercased name.
     * @return string
     */
    public function getLowerCaseName()
    {
        return strtolower($this->name);
    }

    /**
     * Sets the number of the available styles
     * @param int $numberOfStyles
     */
    public function setNumberOfStyles($numberOfStyles)
    {
        $this->numberOfStyles = (int)$numberOfStyles;

        return $this;
    }

    /**
     * Returns the number of the available styles.
     * @return int
     */
    public function getNumberOfStyles()
    {
        return $this->numberOfStyles;
    }

    /**
     * Adds the style to the list of the styles that can handle text.
     * @param int $styleWithText Number of the style
     * @return SocialSharing_Projects_ButtonSet
     */
    public function addStyleWithText($styleWithText)
    {
        if (!in_array($styleWithText, $this->stylesWithText, false)) {
            $this->stylesWithText[] = (int)$styleWithText;
        }

        return $this;
    }

    /**
     * Removes the style from the list of the styles that can handle text.
     * @return SocialSharing_Projects_ButtonSet
     */
    public function removeStyleWithText($styleWithText)
    {
        if (false !== $key = array_search((int)$styleWithText, $this->stylesWithText, false)) {
            unset ($this->stylesWithText[$key]);
        }

        return $this;
    }

    /**
     * Returns an array of the styles identifiers that can handle text.
     * @return int[]
     */
    public function getStylesWithText()
    {
        return $this->stylesWithText;
    }
}
