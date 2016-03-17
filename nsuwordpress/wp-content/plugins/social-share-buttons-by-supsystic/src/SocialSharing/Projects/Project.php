<?php


class SocialSharing_Projects_Project 
{
    const POSITION_SIDEBAR = 'sidebar';
    const POSITION_WIDGET = 'widget';
    const POSITION_SHORTCODE = 'code';
    const POSITION_POPUP = 'popup';
    const POSITION_CONTENT = 'content';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $settings;

    /**
     * @var array
     */
    private $networks;

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->title = isset($data['title']) ? $data['title'] : null;
        $this->settings = isset($data['settings']) ? $data['settings'] : array();
        $this->networks = isset($data['networks']) ? $data['networks'] : array();
    }

    /**
     * Returns Id.
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets Id.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Returns Title.
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets Title.
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;
    }

    /**
     * Returns Settings.
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Sets Settings.
     * @param array $settings
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Returns Networks.
     * @return array
     */
    public function getNetworks()
    {
        return $this->networks;
    }

    /**
     * Sets Networks.
     * @param array $networks
     */
    public function setNetworks($networks)
    {
        $this->networks = $networks;
    }

    /**
     * @return bool
     */
    public function isDisplayTotalShares()
    {
        return 'on' === $this->get('display_total_shares');
    }

    /**
     * @return bool
     */
    public function isShortNumbers()
    {
        return 'on' === $this->get('short_numbers');
    }

    public function isShowOn($what)
    {
        return $what === $this->get('when_show');
    }

    /**
     * This project should be shown when user click on page?
     * @return bool
     */
    public function isShowOnClick()
    {
        return $this->isShowOn('click');
    }

    /**
     * This project should be shown on page loading?
     * @return bool
     */
    public function isShowOnLoad()
    {
        return $this->isShowOn('load');
    }

    /**
     * This project should be shown on mobile devices?
     * @return bool
     */
    public function isHiddenOnMobile()
    {
        return 'on' === $this->get('hide_on_mobile');
    }

    /**
     * This project should be shown on homepage?
     * @return bool
     */
    public function isHiddenOnHomePage()
    {
        return (bool) $this->get('hide_in_home', false);
    }

    /**
     * This project should be shown only on mobile devices?
     * @return bool
     */
    public function isShowOnlyOnMobile()
    {
        return 'on' === $this->get('show_only_on_mobile');
    }

    /**
     * This project should be shown somewhere?
     * @param string $where Where to show
     * @return bool
     */
    public function isShowAt($where)
    {
        return $where === $this->get('where_to_show');
    }

    /**
     * This project should be shown in the sidebar?
     * @return bool
     */
    public function isShowAtSidebar()
    {
        return $this->isShowAt('sidebar');
    }

    /**
     * This project should be shown in the content?
     * @return bool
     */
    public function isShowAtContent()
    {
        return $this->isShowAt('content');
    }

    /**
     * Get buttons align type in the content
     * @return string
     */
    public function getAlignTypeInContent()
    {
        return $this->get('align_in_content');
    }

    /**
     * This project should be shown in the widget?
     * @return bool
     */
    public function isShowAtWidget()
    {
        return $this->isShowAt('widget');
    }

    /**
     * This project should be shown in the shortcode?
     * @return bool
     */
    public function isShowAtShortcode()
    {
        return $this->isShowAt('code');
    }

    /**
     * This project should be shown in the popup?
     * @return bool
     */
    public function isShowAtPopup()
    {
        return $this->isShowAt('popup');
    }

    public function isShowOnPosts()
    {
        return $this->has('show_on_posts') && is_array($this->get('show_on_posts'));
    }

    public function isShowOnSpecificPost($id)
    {
        if (!$this->isShowOnPosts()) {
            return false;
        }

        return in_array((int)$id, $this->get('show_on_posts'), false);
    }

    public function showOnSpecificPostType($postType)
    {
        return in_array($postType, $this->get('show_on_posts'), false);
    }

    public function isShowOnAllPosts()
    {
        return $this->isShowOnSpecificPost(-1);
    }

    public function isShowOnAllPages()
    {
        return $this->isShowOnSpecificPost(-2);
    }

    public function isShowOnAllPostTypes()
    {
        return $this->isShowOnSpecificPost(-3);
    }

    public function isShowEverywhere()
    {
        return 'everywhere' === $this->get('show_at');
    }

    public function isShowOnHomepage()
    {
        return 'homepage' === $this->get('show_at');
    }

    public function isShortCodeShow()
    {
        return $this->get('where_to_show') === 'code';
    }

    public function isPopupShow()
    {
        return $this->get('where_to_show') === 'popup';
    }

    public function getExtra($default = null)
    {
        return $this->get('where_to_show_extra', $default);
    }

    public function isSharePostLinkInList()
    {
        return ($this->get('share_post_link_in_list') === 'on' && ($this->get('where_to_show') === 'content' || $this->get('where_to_show') === 'code')) ? true : false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->settings);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return ($this->has($key) ? $this->settings[$key] : $default);
    }
}