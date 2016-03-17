<?php


class SocialSharing_Networks_Module extends SocialSharing_Core_BaseModule
{
    /**
     * {@inheritdoc}
     */
    public function onInit()
    {
        parent::onInit();
    }

    /**
     * Returns the list of the networks.
     * @return array
     */
    public function getAll()
    {
        $networks = new SocialSharing_Networks_Model_Networks();
        $networks->setEnvironment($this->getEnvironment());

        return $networks->all();
    }
}