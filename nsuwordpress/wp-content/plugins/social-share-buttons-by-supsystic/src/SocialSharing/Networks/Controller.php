<?php

/**
 * Class SocialSharing_Networks_Controller
 */
class SocialSharing_Networks_Controller extends SocialSharing_Core_BaseController
{
    /**
     * Returns list of the all networks.
     * @return Rsc_Http_Response
     */
    public function allAction()
    {
        $networks = $this->modelsFactory->get('networks')->all();

        return $this->response(
            Rsc_Http_Response::AJAX,
            $networks
        );
    }

    /**
     * Adds networks to the project
     * @param Rsc_Http_Request $request
     */
    public function addToProjectAction(Rsc_Http_Request $request)
    {
        $networks = $request->post->get('networks');
        $projectId = $request->post->get('project_id');

        $model = $this->getProjectsNetworksModel();

//        $this->modelsFactory->get('ProjectNetworks', 'Networks')
//            ->drop($projectId);

        foreach ((array)$networks as $networkId) {
            if (!$model->has($projectId, $networkId)) {
                $model->add($projectId, $networkId);
            }
        }
    }

    /**
     * @param Rsc_Http_Request $request
     * @return Rsc_Http_Response
     */
    public function incrementAction(Rsc_Http_Request $request)
    {
        $id = $request->post->get('id');
        $this->modelsFactory->get('networks')->incrementTotalShares($id);

        return $this->response(Rsc_Http_Response::AJAX);
    }

    public function saveTooltipsAction(Rsc_Http_Request $request) {
        $projectId = (int)$request->post->get('project_id');
        $data = $request->post->get('data', array());
        $networkId = array_key_exists('id', $data) ? (int)$data['id'] : null;
        $tooltip = array_key_exists('value', $data) ? $data['value'] : null;

        try {
            $projectNetworks = $this->getProjectsNetworksModel();
            $projectNetworks->updateTooltip($projectId, $networkId, $tooltip);
        } catch (RuntimeException $e) {
            return $this->ajaxError($e->getMessage(), $data);
        }

        return $this->ajaxSuccess();
    }

    public function saveTitlesAction(Rsc_Http_Request $request) {
        $projectId = (int)$request->post->get('project_id');
        $data = $request->post->get('data', array());
        $networkId = array_key_exists('id', $data) ? (int)$data['id'] : null;
        $title = array_key_exists('value', $data) ? $data['value'] : null;

        try {
            $projectNetworks = $this->getProjectsNetworksModel();
            $projectNetworks->updateTitle($projectId, $networkId, $title);
        } catch (RuntimeException $e) {
            return $this->ajaxError($e->getMessage(), $data);
        }

        return $this->ajaxSuccess();
    }

    public function saveTextFormatAction(Rsc_Http_Request $request) {
        $projectId = (int)$request->post->get('project_id');
        $data = $request->post->get('data', array());
        $networkId = array_key_exists('id', $data) ? (int)$data['id'] : null;
        $format = array_key_exists('value', $data) ? $data['value'] : null;

        try {
            $projectNetworks = $this->getProjectsNetworksModel();
            $projectNetworks->updateTextFormat($projectId, $networkId, $format);
        } catch (RuntimeException $e) {
            return $this->ajaxError($e->getMessage(), $data);
        }

        return $this->ajaxSuccess();
    }

    public function saveUseShortUrlAction(Rsc_Http_Request $request) {
        $projectId = (int)$request->post->get('project_id');
        $data = $request->post->get('data', array());
        $networkId = array_key_exists('id', $data) ? (int)$data['id'] : null;
        $useShortUrl = array_key_exists('value', $data) ? $data['value'] : 0;

        try {
            $projectNetworks = $this->getProjectsNetworksModel();
            $projectNetworks->updateUseShortUrl($projectId, $networkId, ($useShortUrl ? 1 : 0));
        } catch (RuntimeException $e) {
            return $this->ajaxError($e->getMessage(), $data);
        }

        return $this->ajaxSuccess();
    }

    public function saveNamesAction(Rsc_Http_Request $request) {
        $projectId = (int)$request->post->get('project_id');
        $data = $request->post->get('data', array());
        $networkId = array_key_exists('id', $data) ? (int)$data['id'] : null;
        $text = array_key_exists('value', $data) ? $data['value'] : null;

        try {
            $projectNetworks = $this->getProjectsNetworksModel();
            $projectNetworks->updateText($projectId, $networkId, $text);
        } catch (RuntimeException $e) {
            return $this->ajaxError($e->getMessage(), $data);
        }

        return $this->ajaxSuccess();
    }

    public function updateSortingAction(Rsc_Http_Request $request)
    {
        $projectId = $request->post->get('project_id');
        $positions = $request->post->get('positions');
        /** @var SocialSharing_Networks_Model_ProjectNetworks $projectNetworks */
        $projectNetworks = $this->modelsFactory->get('projectNetworks', 'networks');

        if (!is_array($positions) || count($positions) === 0) {
            // Returns here success to prevent errors on frontend.
            // Its not error, just empty array
            return $this->ajaxSuccess(array(
                'notice' => 'empty'
            ));
        }

        foreach ($positions as $data) {
            try {
                $projectNetworks->updateNetworkPosition(
                    $projectId,
                    $data['network'],
                    $data['position']
                );
            } catch (Exception $e) {
                return $this->ajaxError($e->getMessage());
            }
        }

        return $this->ajaxSuccess();
    }

    /**
     * @return \SocialSharing_Networks_Model_ProjectNetworks
     */
    protected function getProjectsNetworksModel()
    {
        return $this->modelsFactory->get('projectNetworks', 'networks');
    }
}