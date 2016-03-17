<?php

/**
 * Class SocialSharing_Core_ModelsFactory
 */
class SocialSharing_Core_ModelsFactory 
{

    /**
     * @var SocialSharing_Core_BaseModel[]
     */
    protected $models;

    /**
     * @var Rsc_Environment
     */
    protected $environment;

    /**
     * Constructor
     * @param Rsc_Environment $environment
     */
    public function __construct(Rsc_Environment $environment)
    {
        $this->models = array();
        $this->environment = $environment;
    }

    /**
     * Creates a new model and returns it.
     *
     * @param string $alias Model alias
     * @param SocialSharing_Core_BaseModule|string $module Module
     * @return SocialSharing_Core_BaseModel
     */
    public function factory($alias, $module = null)
    {
        if ($module) {
            if (is_string($module)) {
                $module = ucfirst($module);
            } elseif ($module instanceof SocialSharing_Core_BaseModule) {
                $module = $module->getModuleName();
            } else {
                throw new InvalidArgumentException(
                    sprintf(
                        'Parameter $module must be a string or an instance of SocialSharing_Core_BaseModule, %s given.',
                        is_object($module) ? get_class($module) : gettype(
                            $module
                        )
                    )
                );
            }
        } else {
            $module = ucfirst($alias);
        }

        $className = 'SocialSharing_'.$module.'_Model_'.ucfirst($alias);

        if (!class_exists($className)) {

            $className = 'SocialSharingPro_'.$module.'_Model_'.ucfirst($alias);
            if (!class_exists($className)) {
                throw new RuntimeException(
                    sprintf('Class "%s" not found.', $className)
                );
            }
        }

        $model = new $className;
        if ($model instanceof Rsc_Environment_AwareInterface) {
            $model->setEnvironment($this->environment);
        }

        return $model;
    }

    /**
     * Returns instance of the model.
     *
     * @param string $alias Model alias
     * @param SocialSharing_Core_BaseModule|string $module Module
     * @return SocialSharing_Core_BaseModel
     * @throws Exception
     */
    public function get($alias, $module = null)
    {
        if (!array_key_exists($alias, $this->models)) {
            $this->models[$alias] = $this->factory($alias, $module);
        }

        return $this->models[$alias];
    }
}