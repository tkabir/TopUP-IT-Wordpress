<?php

/**
 * Class SocialSharing_Networks_Model_ProjectNetworks
 *
 * Model for many-to-many relations for the projects and the networks.
 */
class SocialSharing_Networks_Model_ProjectNetworks extends SocialSharing_Core_BaseModel
{

    /**
     * Removes network from the project.
     * @param int $networkId Network Id
     * @param int $projectId Project Id
     */
    public function removeNetworkFromProject($networkId, $projectId)
    {
        $query = $this->getQueryBuilder()
            ->deleteFrom($this->getTable())
            ->where('project_id', '=', (int) $projectId)
            ->andWhere('network_id', '=', (int) $networkId);

        $this->db->query($query);

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }
    }

    /**
     * Removes all networks from the specified project
     * @param int $projectId
     * @return bool
     */
    public function drop($projectId)
    {
        $query = $this->getQueryBuilder()
            ->deleteFrom($this->getTable())
            ->where('project_id', '=', (int)$projectId);

        return $this->db->query($query->build()) ? true : false;
    }

    public function has($projectId, $networkId)
    {
        $query = $this->getQueryBuilder()
            ->select('id')
            ->from($this->getTable())
            ->where('project_id', '=', (int)$projectId)
            ->andWhere('network_id', '=', (int)$networkId);

        $result = $this->db->get_row($query->build());

        return null !== $result;
    }

    /**
     * Adds new network to the specified project
     * @param int $projectId
     * @param int $networkId
     * @return bool
     * @throws Exception
     */
    public function add($projectId, $networkId)
    {
        $query = $this->getQueryBuilder()
            ->insertInto($this->getTable())
            ->fields(array('project_id', 'network_id'))
            ->values(array((int)$projectId, (int)$networkId));

        return $this->db->query($query->build()) ? true : false;
    }

    public function cloneNetworks($id, $prototype)
    {
        if (count($prototype->networks) === 0) {
            return;
        }

        foreach ($prototype->networks as $network) {
            $this->add($id, $network->id);
        }
    }


    /**
     * @param int $projectId
     * @param int $networkId
     * @param int $position
     */
    public function updateNetworkPosition($projectId, $networkId, $position)
    {
        $query = $this->getQueryBuilder()
            ->update($this->getTable())
            ->where('project_id', '=', (int)$projectId)
            ->andWhere('network_id', '=', (int)$networkId)
            ->set('position', (int)$position);

        $this->db->query($query->build());
        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }
    }

    /**
     * Updates network text format.
     * @param int $projectId Project id
     * @param int $networkId Network id
     * @param string $format string format
     */
    public function updateTextFormat($projectId, $networkId, $format)
    {
        try {
            $this->updateSomething($projectId, $networkId, 'text_format', $format);
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    /**
     * Updates network use short url.
     * @param int $projectId Project id
     * @param int $networkId Network id
     * @param bit $useShrotUrl
     */
    public function updateUseShortUrl($projectId, $networkId, $useShrotUrl)
    {
        try {
            $this->updateSomething($projectId, $networkId, 'use_short_url', (int) $useShrotUrl);
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    /**
     * Updates network title.
     * @param int $projectId Project id
     * @param int $networkId Network id
     * @param string $title Network title
     */
    public function updateTitle($projectId, $networkId, $title)
    {
        try {
            $this->updateSomething($projectId, $networkId, 'title', $title);
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    /**
     * Updates network's button text.
     * @param int $projectId Project id
     * @param int $networkId Network id
     * @param string $text Button text
     */
    public function updateText($projectId, $networkId, $text)
    {
        try {
            $this->updateSomething($projectId, $networkId, 'text', $text);
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    /**
     * Updates network tooltip.
     * @param int $projectId Project Id
     * @param int $networkId Network Id
     * @param string $tooltip Tooltip text
     */
    public function updateTooltip($projectId, $networkId, $tooltip)
    {
        try {
            $this->updateSomething($projectId, $networkId, 'tooltip', $tooltip);
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    /**
     * Update selected field with the specified value.
     * @param int $projectId Project Id
     * @param int $networkId Network Id
     * @param string $field Field name
     * @param int|float|string|bool $value Value
     */
    protected function updateSomething($projectId, $networkId, $field, $value)
    {
        $query = $this->getQueryBuilder()
            ->update($this->getTable())
            ->where('project_id', '=', (int)$projectId)
            ->andWhere('network_id', '=', (int)$networkId)
            ->set($field, htmlspecialchars($value));

        $this->db->query($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTable($tableName = null)
    {
        if (null === $tableName) {
            $tableName = 'project_networks';
        }
        
        return parent::getTable($tableName);
    }
}