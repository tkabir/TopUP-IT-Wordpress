<?php

/**
 * Class SocialSharing_Networks_Model_Networks
 */
class SocialSharing_Networks_Model_Networks extends SocialSharing_Core_BaseModel
{
    /**
     * Returns all networks.
     * @return array
     */
    public function all()
    {
        $query = $this->getQueryBuilder()
            ->select('*')
            ->from($this->getTable());

        return $this->db->get_results($query->build());
    }

    /**
     * Sets total shares value.
     *
     * @param int $id Network Id
     * @param int $totalShares Total shares value
     * @return bool TRUE on success, FALSE on error
     */
    public function setTotalShares($id, $totalShares)
    {
        $query = $this->getQueryBuilder()
            ->update($this->getTable())
            ->where('id', '=', (int)$id)
            ->fields('total_shares')
            ->values((int)$totalShares ? (int)$totalShares : 0);

        return $this->db->query($query->build()) ? true : false;
    }

    /**
     * Increments total shares for specified network.
     *
     * @param int $id Network Id
     * @return bool TRUE on success, FALSE on error.
     * @throws Exception
     */
    public function incrementTotalShares($id)
    {
        $query = $this->getQueryBuilder()
            ->update($this->getTable())
            ->where('id', '=', (int)$id)
            ->set('total_shares', '{expr}');

        $query = str_replace('\'{expr}\'', '`total_shares` + 1', $query->build());

        return $this->db->query($query) ? true : false;
    }
}