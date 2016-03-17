<?php


class SocialSharing_Shares_Model_Shares extends SocialSharing_Core_BaseModel
{
    /**
     * Adds share to the database.
     * @param int $projectId Project Id
     * @param int $networkId Network Id
     * @param null|int $postId Post Id
     * @return int
     * @throws Exception
     */
    public function add($projectId, $networkId, $postId = null)
    {
        $query = $this->getQueryBuilder()
            ->insertInto($this->getTable())
            ->fields(array('project_id', 'network_id', 'post_id'))
            ->values(
                array(
                    (int)$projectId,
                    (int)$networkId,
                    $postId ? (int)$postId : null
                )
            );

        $this->db->query($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }

        return $this->db->insert_id;
    }

    /**
     * Returns share by id.
     * @param int $id Share Id
     * @return mixed
     * @throws Exception
     */
    public function get($id)
    {
        $query = $this->getQueryBuilder()
            ->select('*')
            ->from($this->getTable())
            ->where('id', '=', (int)$id);

        return $this->db->get_row($query->build());
    }

    /**
     * @param int $projectId
     * @return array|stdClass[]
     * @throws Exception
     */
    public function getProjectStats($projectId)
    {
        $fields = array(
            $this->getField('networks', 'id'),
            $this->getField('networks', 'name'),
            $this->getField('networks', 'brand_primary', 'color'),
            'COUNT(*) AS shares'
        );

        $query = $this->getQueryBuilder()
            ->select($fields)
            ->from($this->getTable('shares'))
            ->join($this->getTable('networks'))
            ->on(
                $this->getField('shares', 'network_id'),
                '=',
                $this->getField('networks', 'id')
            )
            ->where(
                $this->getField('shares', 'project_id'),
                '=',
                (int)$projectId
            )
            ->groupBy($this->getField('shares', 'network_id'));

        $stats = $this->db->get_results($query->build());
        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }

        return $stats;
    }

    public function getProjectStatsForPeriod(
        $projectId,
        DateTime $from,
        DateTime $to = null
    ) {
        $fields = array(
            'DATE_FORMAT(timestamp, \'%Y-%m-%d\') AS date',
            'COUNT(*) AS shares'
        );

        if (!$to) {
            $to = new DateTime('now');
        }

        // Needed
        $to->modify('+1 day');

        $query = $this->getQueryBuilder()
            ->select($fields)
            ->from($this->getTable('shares'))
            ->where(
                $this->getField('shares', 'project_id'),
                '=',
                (int)$projectId
            )
            ->andWhere(
                $this->getField('shares', 'timestamp'),
                '>=',
                sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $from->format('Y-m-d'))
            )
            ->andWhere(
                $this->getField('shares', 'timestamp'),
                '<=',
                sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $to->format('Y-m-d'))
            )
            ->groupBy('DATE_FORMAT(timestamp, \'%Y-%m-%d\')');

        // Rewrite it xD
        $query = str_replace(array(
            '\''.sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $from->format('Y-m-d')).'\'',
            '\''.sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $to->format('Y-m-d')).'\''
        ), array(
            sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $from->format('Y-m-d')),
            sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $to->format('Y-m-d'))
        ), $query->build());

        $stats = $this->db->get_results($query);
        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }

        return $stats;
    }

    public function getPopularPostsForPeriod(
        $projectId,
        DateTime $from,
        DateTime $to = null,
        $limit = 5
    ) {
        $fields = array(
            'post_id',
            'COUNT(*) AS shares'
        );

        if (!$to) {
            $to = new DateTime('now');
        }

        // Needed
        $to->modify('+1 day');

        $query = $this->getQueryBuilder()
            ->select($fields)
            ->from($this->getTable('shares'))
            ->where(
                $this->getField('shares', 'project_id'),
                '=',
                (int)$projectId
            )
            ->andWhere(
                $this->getField('shares', 'timestamp'),
                '>=',
                sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $from->format('Y-m-d'))
            )
            ->andWhere(
                $this->getField('shares', 'timestamp'),
                '<=',
                sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $to->format('Y-m-d'))
            )
            ->groupBy('post_id')
            ->order('DESC')
            ->orderBy('shares')
            ->limit((int)$limit);

        // Rewrite it xD
        $query = str_replace(array(
            '\''.sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $from->format('Y-m-d')).'\'',
            '\''.sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $to->format('Y-m-d')).'\''
        ), array(
            sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $from->format('Y-m-d')),
            sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $to->format('Y-m-d'))
        ), $query->build());

        $stats = $this->db->get_results($query);
        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }

        return $stats;
    }

    /**
     * Returns list of the networks with the shares count for the specified project
     * @param int $projectId
     * @return mixed
     * @throws Exception
     */
    public function getProjectShares($projectId)
    {
        $query = $this->getQueryBuilder()
            ->select(array('network_id', 'COUNT(*) AS shares'))
            ->from($this->getTable())
            ->where('project_id', '=', (int)$projectId)
            ->groupBy('network_id');

        return $this->db->get_results($query->build());
    }

    /**
     * Returns total project shares
     * @param int $projectId
     * @return int
     * @throws Exception
     */
    public function getProjectTotalShares($projectId)
    {
        $query = $this->getQueryBuilder()
            ->select('COUNT(*) AS total_shares')
            ->from($this->getTable())
            ->where('project_id', '=', (int)$projectId);

        return (int)$this->db->get_var($query->build(), 0, 0);
    }

    /**
     * Returns shares for the specified project and the specified network
     * @param int $projectId
     * @param int $networkId
     * @return int
     * @throws Exception
     */
    public function getProjectNetworkShares($projectId, $networkId)
    {
        $query = $this->getQueryBuilder()
            ->select('COUNT(*) AS network_shares')
            ->from($this->getTable())
            ->where('project_id', '=', (int)$projectId)
            ->andWhere('network_id', '=', (int)$networkId);

        return (int)$this->db->get_var($query->build(), 0, 0);
    }

    /**
     * Returns shares for the specified project and the specified network
     * @param int $projectId
     * @param array $networksId
     * @return array<int>
     * @throws Exception
     */
    public function getListProjectNetworkShares($projectId, array $networksId)
    {
        $query = $this->getQueryBuilder()
            ->select(array('network_id', 'COUNT(*) AS network_shares'))
            ->from($this->getTable())
            ->where('project_id', '=', (int)$projectId)
            ->andWhere('network_id', 'in', implode(',', $networksId))
            ->groupBy('network_id');

        $dbresult = $this->db->get_results($query->build());
        $list = array();

        foreach ($dbresult as $item)
            $list[$item->network_id] = $item->network_shares;

        return $list;
    }

    /**
     * Returns shares for the specified project for the specified post.
     * @param int $projectId
     * @param int $networkId
     * @param int $postId
     * @return int
     * @throws Exception
     */
    public function getProjectPageShares($projectId, $networkId, $postId)
    {
        $query = $this->getQueryBuilder()
            ->select('COUNT(*) AS post_shares')
            ->from($this->getTable())
            ->where('project_id', '=', (int)$projectId)
            ->andWhere('network_id', '=', (int)$networkId)
            ->andWhere('post_id', '=', $postId ? (int)$postId : 0);

        return (int)$this->db->get_var($query->build(), 0, 0);
    }

    /**
     * Returns shares for the specified project for the specified post.
     * @param int $projectId
     * @param array $networkId
     * @param int $postId
     * @return array<int>
     * @throws Exception
     */
    public function getListProjectPageShares($projectId, array $networksId, $postId)
    {
        $query = $this->getQueryBuilder()
            ->select(array('network_id', 'COUNT(*) AS total_shares'))
            ->from($this->getTable())
            ->where('project_id', '=', (int)$projectId)
            ->andWhere('network_id', 'in', implode(',', $networksId))
            ->andWhere('post_id', '=', $postId ? (int)$postId : 0)
            ->groupBy('network_id');

        $dbresult = $this->db->get_results($query->build());
        $list = array();

        foreach ($dbresult as $item)
            $list[$item->network_id] = $item->total_shares;

        return $list;
    }

    /**
     * Returns the list of the networks and its shares count
     * @return mixed
     * @throws Exception
     */
    public function getNetworksShares()
    {
        $query = $this->getQueryBuilder()
            ->select(array('network_id', 'COUNT(*) AS shares'))
            ->from($this->getTable())
            ->groupBy('network_id');

        return $this->db->get_results($query->build());
    }

    /**
     * Returns total shares for the specified network
     * @param array $networksId
     * @return array<int>
     * @throws Exception
     */
    public function getListNetworksShares(array $networksId)
    {
        $query = $this->getQueryBuilder()
            ->select(array('network_id', 'COUNT(*) AS total_shares'))
            ->from($this->getTable())
            ->where('network_id', 'in', implode(',', $networksId))
            ->groupBy('network_id');

        $dbresult = $this->db->get_results($query->build());
        $list = array();

        foreach ($dbresult as $item)
            $list[$item->network_id] = $item->total_shares;

        return $list;
    }


}