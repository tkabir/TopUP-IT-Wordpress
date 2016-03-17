<?php

/**
 * Class SocialSharing_Core_BaseController
 */
class SocialSharing_Core_BaseController extends Rsc_Mvc_Controller
{
    /**
     * @var SocialSharing_Core_ModelsFactory
     */
    protected $modelsFactory;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        Rsc_Environment $environment,
        Rsc_Http_Request $request
    ) {
        parent::__construct(
            $environment,
            $request
        );

        $this->modelsFactory = new SocialSharing_Core_ModelsFactory(
            $environment
        );
    }

    /**
     * Creates new response
     * @param string $template The name of the template
     * @param array $data An associative array of the data
     * @param string $filter Filter name
     * @return Rsc_Http_Response
     */
    public function response($template, array $data = array(), $filter = null)
    {
        $request = $this->getRequest();
        $dispatcher = $this->getEnvironment()->getDispatcher();

        if (null === $filter) {
            $filter = '@';

            if ($request->post->has('route')) {
                $route = $request->post->get('route');
                $filter.= $route['module'] . '/';

                if (!array_key_exists('action', $route)) {
                    $route['action'] = 'index';
                }

                $filter.= $route['action'];
            } else {
                $filter.= $request->query->get(
                    'module',
                    $this->getEnvironment()->getConfig()->get('default_module')
                ) . '/';

                $filter.= $request->query->get('action', 'index');
            }
        }

        $data = $dispatcher->apply($filter, array($data));

        if ($template !== Rsc_Http_Response::AJAX) {
            try {
                $twig = $this->getEnvironment()->getTwig();
                $content = $twig->render($template, $data);
            } catch (Exception $e) {
                wp_die ($e->getMessage());
            }
        } else {
            wp_send_json($data);
        }

        return Rsc_Http_Response::create()->setContent($content);
    }

    /**
     * @return SocialSharing_Core_ModelsFactory
     */
    public function getModelsFactory()
    {
        return $this->modelsFactory;
    }

    public function translate($string)
    {
        return $this->getEnvironment()->translate($string);
    }

    /**
     * @param string $message
     * @return ErrorException
     */
    public function error($message = null)
    {
        if (!$message) {
            $message = $this->translate('An error has occurred');
        }

        return new ErrorException($message);
    }

    public function ajaxSuccess(array $data = array())
    {
        return $this->response(
            Rsc_Http_Response::AJAX,
            array_merge(array('success' => true), $data)
        );
    }

    public function ajaxError($message, array $data = array())
    {
        return $this->response(
            Rsc_Http_Response::AJAX,
            array_merge(array('success' => false, 'message' => $message), $data)
        );
    }
}
