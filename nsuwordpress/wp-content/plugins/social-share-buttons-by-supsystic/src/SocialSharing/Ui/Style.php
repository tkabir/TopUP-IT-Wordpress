<?php

/**
 * Class SocialSharing_Ui_Style
 */
class SocialSharing_Ui_Style extends SocialSharing_Ui_Asset
{
    /**
     * @var string
     */
    protected $media;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->hookName = 'wp_enqueue_scripts';
    }

    /**
     * Adds asset to the global WordPress assets queue.
     */
    public function enqueue()
    {
        wp_enqueue_style(
            $this->handle,
            $this->source,
            $this->dependencies,
            $this->version,
            $this->media
        );
    }

    /**
     * Returns Media.
     * @return string
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Sets Media.
     * @param string $media
     * @return $this
     */
    public function setMedia($media)
    {
        $this->media = (string)$media;

        return $this;
    }
}