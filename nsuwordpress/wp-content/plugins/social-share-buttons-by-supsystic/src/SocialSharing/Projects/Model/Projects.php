<?php

/**
 * Class SocialSharing_Projects_Model_Projects
 */
class SocialSharing_Projects_Model_Projects extends SocialSharing_Core_BaseModel
{
    /**
     * Creates a new project.
     *
     * @param string $title Project title
     * @param string $design Button design type
     * @return int Project id
     * @throws Exception
     */
    public function create($title, $design = null)
    {
        $title = htmlspecialchars($title);
        $design = htmlspecialchars($this->isButtonDesignAvailable($design)) . '-1';
        $design = 's:6:"design";s:' . strlen($design) . ':"' . $design . '";';

        $query = $this->getQueryBuilder()
            ->insertInto($this->getTable())
            ->fields('title', 'created_at', 'settings')
            ->values($title, date('Y-m-d'), 'a:6:{s:12:"hide_in_home";b:0;s:13:"where_to_show";s:7:"sidebar";s:19:"where_to_show_extra";s:4:"left";s:7:"show_at";s:10:"everywhere";s:9:"when_show";s:4:"load";' . $design . '}');

        $this->db->query($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException(
                sprintf('Failed to execute query: %s', $this->db->last_query)
            );
        }

        return $this->db->insert_id;
    }

    /**
     * Returns project.
     *
     * @param int $id
     * @return object|null Project
     * @throws Exception
     */
    public function get($id)
    {
        $query = $this->getQueryBuilder()
            ->select('*')
            ->from($this->getTable())
            ->where('id', '=', (int)$id);

        $project = $this->db->get_row($query->build());

        if (!$project) {
            return null;
        }

        return $this->applyFilters($project);
    }

    public function searchByPopupId($like)
    {
        $query = $this->getQueryBuilder()
            ->select('*')
            ->from($this->getTable())
            ->where('settings', 'LIKE', $like)
            ->limit(1);

        $project = $this->db->get_row($query->build());

        if (!$project) {
            return null;
        }

        return $this->applyFilters($project);
    }

    /**
     * Returns all projects.
     *
     * @param string $order ASC or DESC (default 'desc')
     * @param string $orderBy Field (default 'id')
     * @return mixed|null An array of the projects or NULL
     * @throws Exception
     */
    public function all($order = 'DESC', $orderBy = 'id')
    {
        $orderTypes = array('desc', 'asc');
        if (!in_array(strtolower($order), $orderTypes, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    $this->translate('Order by can be %s, "%s" given.'),
                    implode(', ', $orderTypes),
                    $order
                )
            );
        }

        $query = $this->getQueryBuilder()
            ->select('*')
            ->from($this->getTable())
            ->order($order)
            ->orderBy($orderBy);

        $projects = $this->db->get_results($query);

        if (!$projects) {
            return null;
        }

        return array_map(array($this, 'applyFilters'), $projects);
    }

    /**
     * Removes a project.
     *
     * @param int $id Project Id
     * @return bool TRUE on success, FALSE on error
     */
    public function delete($id)
    {
        $query = $this->getQueryBuilder()
            ->deleteFrom($this->getTable())
            ->where('id', '=', (int)$id);

        return $this->db->query($query) ? true : false;
    }

    /**
     * Saves a project.
     *
     * @param int $id Project Id
     * @param array $settings An array of the settings.
     * @return bool TRUE on success, FALSE on error
     * @throws Exception
     */
    public function save($id, array $settings)
    {
        /** @var SocialSharing_Popup_Module $facade */
//        $facade = $this->environment->getModule('popup');

//        if ($settings['where_to_show'] === 'popup') {
//            if ($settings['popup_id'] == 0) {
//                $project = $this->get($id);
//                $popupId = $facade->getModel()->createFromTpl(
//                    array(
//                        'label'       => htmlspecialchars(
//                            'Social Sharing \"' . $project->title . '\"'
//                        ),
//                        'original_id' => $facade->getTemplateId()
//                    )
//                );
//
//                if (!$popupId) {
//                    throw new RuntimeException(
//                        sprintf(
//                            $this->translate(
//                                'Failed to create popup for project "%s".'
//                            ),
//                            $project->title
//                        )
//                    );
//                }
//
//                $settings['popup_id'] = $popupId;
//            }
//
//            $facade->getModel()->save($facade->getPopupSettings($id, $settings));
//        } elseif ($settings['popup_id'] != 0) {
//            $facade->getModel()->remove($settings['popup_id']);
//            $settings['popup_id'] = 0;
//        }

        $query = $this->getQueryBuilder()
            ->update($this->getTable())
            ->where('id', '=', (int)$id)
            ->fields('settings')
            ->values(serialize($settings));

        $this->db->query($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException(
                sprintf(
                    $this->translate('Failed to execute query: %s'),
                    $this->db->last_query
                )
            );
        }
    }

    public function rename($id, $title)
    {
        $query = $this->getQueryBuilder()
            ->update($this->getTable())
            ->where('id', '=', (int)$id)
            ->set(array('title' => htmlspecialchars($title)));

        $this->db->query($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }
    }

    public function makeClone($id)
    {
        $project = $this->get($id);

        if ($project === null) {
            throw new InvalidArgumentException(
                sprintf(
                    $this->translate(
                        'The project with identifier %d not found.'
                    ),
                    $id
                )
            );
        }

        try {
            $cloneId = $this->create($project->title . ' (clone)');
            $this->save($cloneId, $project->settings);
        } catch (Exception $e) {
            throw $e;
        }

        return $cloneId;
    }

    public function filterGetProject($project)
    {
        $project->networks = $this->db->get_results(
            'SELECT n.*, pn.title, pn.text, pn.tooltip, pn.text_format, pn.use_short_url FROM `' . $this->getTable(
            ) . '` AS p LEFT JOIN `' . $this->getTable(
                'project_networks'
            ) . '` AS pn ON p.id = pn.project_id LEFT JOIN `' . $this->getTable(
                'networks'
            ) . '` AS n ON pn.network_id = n.id WHERE p.id = ' . $project->id . ' ORDER BY pn.position ASC'
        );

        if (count($project->networks) === 1 && !$project->networks[0]->id) {
            $project->networks = array();
        }

        $project->settings = unserialize($project->settings);

        return $project;
    }

    public function applyFilters($project)
    {
        return $this->environment->getDispatcher()->apply(
            'project_get',
            array($project)
        );
    }

    public function getTooltips() {
        // method exists for compatibility.
        $tooltips = new SocialSharing_Projects_Model_Tooltips();
        $tooltips->setEnvironment($this->environment);

        return $tooltips->getTooltips();
    }

    protected function isButtonDesignAvailable($design){
        return ($design == 'flat' || $this->environment->isPro()) ? $design : 'flat';
    }

    public function getButtonsDesignPreview() {
        $e = $this->environment;
        $dispatcher = $e->getDispatcher();
        $path = dirname(dirname(dirname(dirname(__FILE__))));
        $url = plugin_dir_url($path) . 'src/SocialSharing/Projects/assets/images/';

        $previews = array(
            array(
                'title' => $e->translate('Flat'),
                'img_url' => $url . 'free_icons.png',
                'design' => 'flat',
                'free' => true,
            ),
            array(
                'title' => $e->translate('Mono'),
                'img_url' => $url . 'mono_icons.png',
                'design' => 'mono',
                'free' => false,
            ),
            array(
                'title' => $e->translate('Bordered'),
                'img_url' => $url . 'bordered_icons.png',
                'design' => 'bordered',
                'free' => false,
            ),
            array(
                'title' => $e->translate('Livejournal'),
                'img_url' => $url . 'lj_icons.png',
                'design' => 'livejournal',
                'free' => false,
            ),
//            array(
//                'title' => $e->translate('Various'),
//                'img_url' => $url . 'various_icons.png',
//                'design' => 'various',
//                'free' => false,
//            ),
        );

        return $dispatcher->apply(
            'openButtonsDesignPreview',
            array($previews)
        );
    }
}