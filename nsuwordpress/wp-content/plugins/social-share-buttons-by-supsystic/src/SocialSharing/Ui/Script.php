<?php


class SocialSharing_Ui_Script extends SocialSharing_Ui_Asset
{

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
        wp_enqueue_script(
            $this->handle,
            $this->source,
            $this->dependencies,
            $this->version
        );
    }
}