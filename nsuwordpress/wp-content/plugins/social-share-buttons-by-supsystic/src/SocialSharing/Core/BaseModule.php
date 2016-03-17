<?php


class SocialSharing_Core_BaseModule extends Rsc_Mvc_Module
{
    /**
     * @return SocialSharing_Core_ModelsFactory
     */
    public function getModelsFactory()
    {
        $controller = $this->getController();

        if (!$controller) {
            throw new LogicException(
                'Can\'t get models factory from module that doesn\'t have controller.'
            );
        }

        if (!$controller instanceof SocialSharing_Core_BaseController) {
            throw new LogicException(
                sprintf(
                    'The controller %s must extend SocialSharing_Core_BaseController to use models factory.',
                    get_class($controller)
                )
            );
        }

        return $controller->getModelsFactory();
    }
}