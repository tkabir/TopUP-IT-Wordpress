<?php

/**
 * Class SocialSharing_Projects_Controller
 *
 * Projects controller.
 */
class SocialSharing_Projects_Controller extends SocialSharing_Core_BaseController
{

    /**
     * Shows list of the created projects.
     *
     * @param Rsc_Http_Request $request Http request
     * @return Rsc_Http_Response
     */
    public function indexAction(Rsc_Http_Request $request)
    {
        $projects = $this->modelsFactory->get('projects')->all();

        if ($projects && count($projects)) {
            foreach($projects as $project) {
                $shares = $this->modelsFactory->get('shares')->getProjectShares($project->id);
                $totalShares = 0;

                foreach($shares as $share) {
                    $totalShares += $share->shares;
                }
                $project->totalShares = $totalShares;
                $project->totalViews = $this->modelsFactory->get('views', 'shares')->getProjectTotalViews($project->id);
            }
        }

        return $this->response('@projects/index.twig', array(
            'projects' => $projects
        ));
    }

    /**
     * @param Rsc_Http_Request $request
     * @return Rsc_Http_Response
     */
    public function addAction(Rsc_Http_Request $request)
    {
        $title = $request->post->get('title');
        $design = $request->post->get('design');
        $networksInProject = $request->post->get('networks');
        $networks = $this->modelsFactory->get('networks')->all();
        $networkModel = $this->modelsFactory->get('projectNetworks', 'networks');

        if(empty($title) || empty($title)) {
            $buttonsPreview = $this->getModelsFactory()->get('projects')->getButtonsDesignPreview();
            return $this->response('@projects/add_new.twig',
                array(
                    'buttons_preview' => $buttonsPreview,
                    'networks'        => $networks,
                )
            );
        } else {
            try {
                $insertId = $this->modelsFactory->get('projects')->create(
                    $title,
                    $design
                );

                foreach ((array)$networksInProject as $networkId) {
                    if (!$networkModel->has($insertId, $networkId)) {
                        $networkModel->add($insertId, $networkId);
                    }
                }

            } catch (RuntimeException $e) {
                return $this->ajaxError($e->getMessage());
            }

            return $this->ajaxSuccess(array(
                'redirect_url' => $this->generateUrl(
                    'projects',
                    'view',
                    array(
                        'id' => $insertId
                    )
                )
            ));
        }
    }

    /**
     * @param Rsc_Http_Request $request
     * @return Rsc_Http_Response
     */
    public function saveAction(Rsc_Http_Request $request)
    {
        $id = $request->post->get('id');
        $settings = $request->post->get('settings');
        $projects = $this->modelsFactory->get('projects');

        if (array_key_exists('popup_id', $settings)) {
            /** @var SocialSharing_Popup_Module $popup */
            $popup = $this->getEnvironment()->getModule('popup');

            if (!$popup->isInstalled()) {
                $settings['popup_id'] = 0;
            } else {
                $hasPopup = $popup->call('getModule', array('popup'))
                    ->getModel()
                    ->getById((int)$settings['popup_id']);

                if (!$hasPopup) {
                    $settings['popup_id'] = 0;
				} else {
					if(!isset($hasPopup['params']['tpl']['enb_sm']) || empty($hasPopup['params']['tpl']['enb_sm'])) {
						$hasPopup['params']['tpl']['enb_sm'] = 1;
						$hasPopup['params']['tpl']['use_sss_prj_id'] = 1;
						$popup->call('getModule', array('popup'))
							->getModel()
							->updateParamsById( $hasPopup );
					}
				}
            }
        }

        $projects->save($id, $settings);

        return $this->ajaxSuccess(array('popup_id' => $settings['popup_id']));
    }

    /**
     * View specific project.
     *
     * @param Rsc_Http_Request $request Http request
     * @return Rsc_Http_Response Http response
     */
    public function viewAction(Rsc_Http_Request $request)
    {
        $projectId = (int)$request->query->get('id');

        $project = $this->modelsFactory->get('projects')->get($projectId);
        $networks = $this->modelsFactory->get('networks')->all();
        $tooltips = $this->modelsFactory->get('projects')->getTooltips();
        $buttonsPreview = $this->getModelsFactory()->get('projects')->getButtonsDesignPreview();

        $popup = $this->getEnvironment()->getModule('popup');
		$popupInstalled = $popup->isInstalled();
		$popups = $popupInstalled ? $popup->getModel()->getSimpleList('original_id != 0') : array();
		$popupAddUrl = $popupInstalled ? $popup->call('getModule', array('options'))->getTabUrl('popup_add_new') : '';

        $dispatcher = $this->getEnvironment()->getDispatcher();

        return $this->response(
            '@projects/view.twig',
            array(
                'project'           => $project,
                'networks'          => $networks,
                'posts'             => get_posts(array('posts_per_page' => -1)),
                'pages'             => get_pages(array('posts_per_page' => -1)),
                'post_types'        => get_post_types(array('public' => true)),
                'popup_installed'   => $popupInstalled,
                'popups'            => $popups,
				'popup_add_new_url' => $popupAddUrl,
                'tooltips'          => $tooltips,
                'buttons_preview' => $buttonsPreview,
                'button_sets'       => $dispatcher->apply(
                    'button_sets',
                    array(
                        $this->getButtonSets()
                    )
                ),
                'templates'         => array(
                    'twitter', 'pinterest', 'facebook', 'digg'
                ),
            )
        );
    }

    /**
     * @param Rsc_Http_Request $request
     * @return Rsc_Http_Response
     */
    public function deleteAction(Rsc_Http_Request $request)
    {
        $this->modelsFactory->get('projects')->delete($request->query->get('id'));

        return $this->redirect($this->generateUrl('projects', 'index'));
    }

    public function renameAction(Rsc_Http_Request $request)
    {
        try {
            $projects = $this->modelsFactory->get('projects');

            $projects->rename(
                $request->post->get('id'),
                $request->post->get('title')
            );
        } catch (Exception $e) {
            return $this->ajaxError($this->translate(sprintf('Failed to rename project: %s', $e->getMessage())));
        }

        return $this->ajaxSuccess();
    }

    public function cloneAction(Rsc_Http_Request $request)
    {
        $id = $request->post->get('id', $request->query->get('id'));

        try {
            $cloneId = $this->modelsFactory
                ->get('projects')
                ->makeClone($id);

            $prototype = $this->modelsFactory->get('projects')->get($id);
            $this->modelsFactory->get('projectNetworks', 'Networks')
                ->cloneNetworks($cloneId, $prototype);

            $redirectUri = $this->generateUrl(
                'projects',
                'view',
                array('id' => $cloneId)
            );

            if ($request->isXmlHttpRequest()) {
                return $this->response(
                    Rsc_Http_Response::AJAX,
                    array(
                        'location' => $redirectUri
                    )
                );
            }

            return $this->redirect($redirectUri);
        } catch (InvalidArgumentException $e) {
            throw $this->error(
                sprintf(
                    $this->translate('Unable to clone project: %s'),
                    $e->getMessage()
                )
            );
        } catch (RuntimeException $e) {
            throw $this->error(
                sprintf(
                    $this->translate(
                        'Unable to clone project due database error: %s'
                    ),
                    $e->getMessage()
                )
            );
        }
    }

    public function removeNetworkAction(Rsc_Http_Request $request)
    {
        /** @var int $networkId */
        $networkId = (int) $request->post->get('network_id');
        /** @var int $projectId */
        $projectId = (int) $request->post->get('project_id');
        /** @var SocialSharing_Networks_Model_ProjectNetworks $projectNetworks */
        $projectNetworks = $this->getModelsFactory()->get('projectNetworks', 'networks');

        try {
            $projectNetworks->removeNetworkFromProject($networkId, $projectId);
        } catch (RuntimeException $e) {
            return $this->ajaxError($e->getMessage());
        }

        return $this->ajaxSuccess();
    }

    public function devPreviewAction()
    {
        return $this->response('@projects/dev_preview.twig');
    }

    protected function getButtonSets()
    {
        $environment = $this->getEnvironment();
        $sets = array(
            new SocialSharing_Projects_ButtonSet(
                $environment->translate('Flat'),
                9,
                array(8, 9)
            )
        );

        return $sets;
    }
}
