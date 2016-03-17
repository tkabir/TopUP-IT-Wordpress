<?php


interface SocialSharing_Ajax_RequestHandlerInterface
{
    /**
     * Takes AJAX request and re-route it to requested module.
     * @param Rsc_Http_Request $request
     * @return mixed
     */
    public function handleRequest(Rsc_Http_Request $request);
}