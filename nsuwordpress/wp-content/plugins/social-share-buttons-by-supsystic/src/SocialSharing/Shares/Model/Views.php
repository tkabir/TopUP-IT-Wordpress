<?php


class SocialSharing_Shares_Model_Views extends SocialSharing_Core_BaseModel
{
    /**
     * Adds share to the database.
     * @param int $projectId Project Id
     * @param int $networkId Network Id
     * @param null|int $postId Post Id
     * @return int
     * @throws Exception
     */
    public function add($projectId, $postId = null)
    {
        $query = $this->getQueryBuilder()
            ->insertInto($this->getTable())
            ->fields(array('project_id', 'post_id'))
            ->values(
                array(
                    (int)$projectId,
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
     * Returns view by id.
     * @param int $id View Id
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
            'COUNT(*) AS views'
        );

        $query = $this->getQueryBuilder()
            ->select($fields)
            ->from($this->getTable('views'))
            ->join($this->getTable('networks'))
            ->on(
                $this->getField('views', 'network_id'),
                '=',
                $this->getField('networks', 'id')
            )
            ->where(
                $this->getField('views', 'project_id'),
                '=',
                (int)$projectId
            )
            ->groupBy($this->getField('views', 'network_id'));

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
            'COUNT(*) AS views'
        );

        if (!$to) {
            $to = new DateTime('now');
        }

        // Needed
        $to->modify('+1 day');

        $query = $this->getQueryBuilder()
            ->select($fields)
            ->from($this->getTable('views'))
            ->where(
                $this->getField('views', 'project_id'),
                '=',
                (int)$projectId
            )
            ->andWhere(
                $this->getField('views', 'timestamp'),
                '>=',
                sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $from->format('Y-m-d'))
            )
            ->andWhere(
                $this->getField('views', 'timestamp'),
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
            'COUNT(*) AS views'
        );

        if (!$to) {
            $to = new DateTime('now');
        }

        // Needed
        $to->modify('+1 day');

        $query = $this->getQueryBuilder()
            ->select($fields)
            ->from($this->getTable('views'))
            ->where(
                $this->getField('views', 'project_id'),
                '=',
                (int)$projectId
            )
            ->andWhere(
                $this->getField('views', 'timestamp'),
                '>=',
                sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $from->format('Y-m-d'))
            )
            ->andWhere(
                $this->getField('views', 'timestamp'),
                '<=',
                sprintf('STR_TO_DATE(\'%s\', \'%%Y-%%m-%%d\')', $to->format('Y-m-d'))
            )
            ->groupBy('post_id')
            ->order('DESC')
            ->orderBy('views')
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
     * Returns total project views
     * @return int
     * @throws Exception
     */
    public function getProjectTotalViews($projectId)
    {
        $query = $this->getQueryBuilder()
            ->select('COUNT(*) AS total_views')
            ->from($this->getTable())
            ->where('project_id', '=', (int)$projectId)
            ->groupBy('project_id');

        return (int)$this->db->get_var($query->build());
    }

    /**
     * Returns views for the specified project and the specified network
     * @param int $projectId
     * @param int $networkId
     * @return int
     * @throws Exception
     */
    public function getProjectNetworkShares($projectId, $networkId)
    {
        $query = $this->getQueryBuilder()
            ->select('COUNT(*) AS network_views')
            ->from($this->getTable())
            ->where('project_id', '=', (int)$projectId)
            ->andWhere('network_id', '=', (int)$networkId);

        return (int)$this->db->get_var($query->build(), 0, 0);
    }
}