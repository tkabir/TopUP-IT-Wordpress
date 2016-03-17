<?php


class SocialSharing_Projects_Builder_Network
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $primaryColor;

    /**
     * @var string
     */
    private $secondaryColor;

    /**
     * @var int
     */
    private $shares;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $tooltip;

    /**
     * @var string
     */
    private $buttonText;

    /**
     * @var string
     */
    private $textFormat;

    /**
     * Constructs the network dto.
     * @param array $network Network data
     * @throws InvalidArgumentException
     */
    public function __construct(array $network)
    {
        if (!array_key_exists('id', $network)) {
            throw new InvalidArgumentException(
                'Trying to create undefined network dto.'
            );
        }

        $this->id = (int)$network['id'];

        $this->name = array_key_exists(
            'name',
            $network
        ) ? $network['name'] : 'Undefined';

        $this->url = array_key_exists(
            'url',
            $network
        ) ? $network['url'] : '#undefined';

        $this->class = array_key_exists(
            'class',
            $network
        ) ? $network['class'] : null;

        $this->primaryColor = array_key_exists(
            'brand_primary',
            $network
        ) ? $network['brand_primary'] : '#000000';

        $this->secondaryColor = array_key_exists(
            'brand_secondary',
            $network
        ) ? $network['brand_secondary'] : '#ffffff';

        $this->shares = array_key_exists(
            'shares',
            $network
        ) ? (int)$network['shares'] : 0;

        $this->title = array_key_exists(
            'title',
            $network
        ) ? $network['title'] : null;

        $this->tooltip = array_key_exists(
            'tooltip',
            $network
        ) ? $network['tooltip'] : null;

        $this->buttonText = array_key_exists(
            'text',
            $network
        ) ? $network['text'] : null;
        
        $this->textFormat = array_key_exists(
            'text_format',
            $network
        ) ? $network['text_format'] : null;

        $this->useShortUrl = array_key_exists(
            'use_short_url',
            $network
        ) ? (bool) $network['use_short_url'] : false;
    }

    /**
     * Returns Id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns Name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets Name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Returns Url.
     *
     * @param \WP_Post $post Current post
     *
     * @return string
     */
    public function getUrl(WP_Post $post = null)
    {
        $title = $post ? get_the_title($post) : get_bloginfo('name');
        $url = $post ? urlencode(get_the_permalink($post)) : urlencode(get_bloginfo('wpurl'));
        $description = $post ? urlencode( wp_trim_words($post->post_content)) : urlencode(get_bloginfo('description '));

        if ($this->useShortUrl && $post)
            $url = urlencode(wp_get_shortlink($post->ID));

        if (is_string($this->textFormat) && strlen($this->textFormat)) 
        {
            $title = str_replace(
                array(
                    '[page_title]',
                    '[page_url]'
                ),
                array(
                    $post ? get_the_title($post) : get_bloginfo('name'),
                    $url
                ),
                $this->textFormat
            );

            $description = str_replace(
                array(
                    '[page_title]',
                    '[page_url]'
                ),
                array(
                    $post ? get_the_title($post) : get_bloginfo('name'),
                    $url
                ),
                $this->textFormat
            );
        }

        $pairs = array(
            '{url}'   => $url,
            '{title}' => urlencode($title),
            '{description}' => $description
        );

        return strtr($this->url, $pairs);
    }

    /**
     * Sets Url.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = (string)$url;

        return $this;
    }

    /**
     * Returns Class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Sets Class.
     *
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = (string)$class;

        return $this;
    }

    /**
     * Returns PrimaryColor.
     *
     * @return string
     */
    public function getPrimaryColor()
    {
        return $this->primaryColor;
    }

    /**
     * Sets PrimaryColor.
     *
     * @param string $primaryColor
     *
     * @return $this
     */
    public function setPrimaryColor($primaryColor)
    {
        $this->primaryColor = (string)$primaryColor;

        return $this;
    }

    /**
     * Returns SecondaryColor.
     *
     * @return string
     */
    public function getSecondaryColor()
    {
        return $this->secondaryColor;
    }

    /**
     * Sets SecondaryColor.
     *
     * @param string $secondaryColor
     *
     * @return $this
     */
    public function setSecondaryColor($secondaryColor)
    {
        $this->secondaryColor = (string)$secondaryColor;

        return $this;
    }

    /**
     * Returns Shares.
     *
     * @param bool $humanize Use humanized number or not
     *
     * @return int
     */
    public function getShares($humanize = false)
    {
        if ($humanize) {
            $abbreviations = array(
                12 => 't',
                9  => 'b',
                6  => 'm',
                3  => 'k',
                0  => ''
            );

            foreach ($abbreviations as $exponent => $suffix) {
                if ($this->shares >= pow(10, $exponent)) {
                    return round((float)($this->shares / pow(10, $exponent)), 1) . $suffix;
                }
            }
        }

        return $this->shares;
    }

    /**
     * Sets Shares.
     *
     * @param int $shares
     *
     * @return $this
     */
    public function setShares($shares)
    {
        $this->shares = (int)$shares;

        return $this;
    }

    /**
     * Returns network icon.
     *
     * @return string
     */
    public function getIcon()
    {
        switch ($this->class) {
            case 'facebook':
                return 'fa-facebook';

            case 'twitter':
                return 'fa-twitter';

            case 'googleplus':
                return 'fa-google-plus';

            case 'vk':
                return 'fa-vk';

            case 'like':
                return 'fa-heart';

            case 'reddit':
                return 'fa-reddit';

            case 'pinterest':
                return 'fa-pinterest';

            case 'digg':
                return 'fa-digg';

            case 'stumbleupon':
                return 'fa-stumbleupon';

            case 'delicious':
                return 'fa-delicious';

            case 'livejournal':
                return 'fa-pencil';

            case 'odnoklassniki':
                return 'fa-odnoklassniki';

            case 'linkedin':
                return 'fa-linkedin';

            case 'print':
                return 'fa-print';

            case 'bookmark':
                return 'fa-plus';

            case 'mail':
                return 'fa-paper-plane';

            case 'evernote':
                return 'bd-evernote';

            case 'whatsapp':
                return 'fa-whatsapp';

            case 'tumblr':
                return 'fa-tumblr';
        }

        return 'fa-share-alt';
    }

    /**
     * Returns Title.
     *
     * @return string
     */
    public function getTitle()
    {
        if ($this->tooltip) {
            return $this->tooltip;
        }

        return $this->title ? $this->title : $this->name;
    }

    /**
     * Sets Title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;

        return $this;
    }

    /**
     * Returns Tooltip.
     *
     * @return string
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }

    /**
     * Sets Tooltip.
     *
     * @param string $tooltip
     *
     * @return $this
     */
    public function setTooltip($tooltip)
    {
        $this->tooltip = (string)$tooltip;

        return $this;
    }

    /**
     * Returns ButtonText.
     *
     * @return string
     */
    public function getButtonText()
    {
        return $this->buttonText;
    }

    /**
     * Sets ButtonText.
     *
     * @param string $buttonText
     *
     * @return $this
     */
    public function setButtonText($buttonText)
    {
        $this->buttonText = (string)$buttonText;

        return $this;
    }

    /**
     * Returns TextFormat.
     *
     * @return string
     */
    public function getTextFormat()
    {
        return $this->textFormat;
    }

    /**
     * Sets TextFormat.
     *
     * @param string $textFormat
     *
     * @return $this
     */
    public function setTextFormat($textFormat)
    {
        $this->textFormat = (string)$textFormat;

        return $this;
    }

    /**
     * Get parameter 'data-action' value.
     *
     * @return string
     */
    public function getDataAction()
    {
        switch ($this->class) {
            case 'whatsapp':
                $dataActionValue = 'share/whatsapp/share';
                break;
            default:
                $dataActionValue = null;
        }
        return $dataActionValue;
    }
}