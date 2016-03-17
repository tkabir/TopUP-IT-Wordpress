<?php


class SocialSharing_Ajax_Module extends SocialSharing_Core_BaseModule
{
    /**
     * @var SocialSharing_Ajax_RequestHandlerInterface
     */
    private $requestHandler;

    /**
     * {@inheritdoc}
     */
    public function onInit()
    {
        parent::onInit();

        add_action('wp_ajax_social-sharing', array($this, 'handleRequest'));
    }

    public function handleRequest()
    {
        $this->getRequestHandler()->handleRequest($this->getRequest());
    }

    /**
     * Returns RequestHandler.
     * @return SocialSharing_Ajax_RequestHandlerInterface
     */
    public function getRequestHandler()
    {
        if (!$this->requestHandler) {
            $this->requestHandler = new SocialSharing_Ajax_RequestHandler(
                $this->getEnvironment()
            );
        }

        return $this->requestHandler;
    }

    /**
     * Sets RequestHandler.
     * @param SocialSharing_Ajax_RequestHandlerInterface $requestHandler
     */
    public function setRequestHandler(SocialSharing_Ajax_RequestHandlerInterface $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }
}