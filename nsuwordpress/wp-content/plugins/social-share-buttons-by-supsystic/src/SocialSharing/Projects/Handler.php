<?php


class SocialSharing_Projects_Handler
{
    /**
     * @var SocialSharing_Projects_Builder
     */
    protected $builder;

    /**
     * @var SocialSharing_Projects_Project
     */
    protected $project;

    /**
     * @var Rsc_Environment
     */
    protected $environment;

    /**
     * @var array
     */
    protected static $listenProjects = array();

    /**
     * Constructs the handler.
     * @param \SocialSharing_Projects_Project $project
     */
    public function __construct(SocialSharing_Projects_Project $project, Rsc_Environment $environment)
    {
        $this->project = $project;
        $this->environment = $environment;
        $this->builder = $this->instantiateBuilder();
    }

    /**
     * Builds the composite and returns button markup.
     * @return string
     */
    public function build()
    {
        $environment = $this->builder->getEnvironment();
        $dispatcher = $environment->getDispatcher();

        $this->project = $dispatcher->apply(
            'before_build',
            array($this->project)
        );

        if (
            (
                !array_key_exists('action', $_GET)
                    || $_GET['action'] !== 'getPreviewHtml'
            )
                && $this->project->isShowOnPosts()
                    && !$this->project->isShowAt('popup')
        ) {
            $current = get_post();

            if ($current === null) {
                return '';
            }

            if (!$this->project->isShortCodeShow()
                && $current->post_type === 'post'
                    && (!$this->project->isShowOnAllPosts()
                        && !$this->project->isShowOnSpecificPost($current->ID))
                            && !$this->project->showOnSpecificPostType($current->post_type)
                                && !$this->project->isShowOnAllPostTypes()
            ) {
                return '';
            }

            if ($current->post_type === 'page'
                && (!$this->project->isShowOnAllPages()
                    && !$this->project->isShowOnSpecificPost($current->ID))
                        && !$this->project->showOnSpecificPostType($current->post_type)
                            && !$this->project->isShowOnAllPostTypes()
            ) {
                return '';
            }

            if ((!$this->project->isShowOnAllPosts()
                    && !$this->project->isShowOnAllPages())
                        && !in_array($current->post_type, array('post', 'page'), false)
                            && !$this->project->showOnSpecificPostType($current->post_type)
                                && !$this->project->isShowOnAllPostTypes()
            ) {
                return '';
            }
        }

        if (!$this->project->isShortCodeShow() && !$this->project->isPopupShow() && $this->project->isShowOnHomepage() && !$this->builder->isHomepage()) {
            return '';
        }

        try {
            $composite = $this->builder->getComposite();
        } catch (Exception $e) {
            if ((defined('WP_DEBUG') && WP_DEBUG) && (function_exists(
                        'is_super_admin'
                    ) && is_super_admin())
            ) {
                return sprintf(
                    $this->environment->translate(
                        'Failed to build the project: %s.'
                    ),
                    $e->getMessage()
                );
            }

            return '';
        }

        $this->project = $dispatcher->apply(
            'after_build',
            array($this->project)
        );

        $dispatcher->dispatch('before_html_build');

        return $composite->build();
    }

    /**
     * Adds the listeners and filter to the WordPress hooks to render the buttons.
     */
    public function listen()
    {
        $projectId = $this->project->getId();

        if (in_array($projectId, self::$listenProjects)) return;

        self::$listenProjects[] = $projectId;
        
        switch ($this->project->get('where_to_show')) {
            case SocialSharing_Projects_Project::POSITION_WIDGET:
                $this->action('widgets_init', 'applyWidgetCallback');
                break;
            case SocialSharing_Projects_Project::POSITION_SIDEBAR:
                $this->action('wp_footer', 'applySidebarCallback');
                break;
            case SocialSharing_Projects_Project::POSITION_CONTENT:
                $this->filter('the_content', 'applyContentCallback');
                $this->filter('the_excerpt', 'applyContentCallback');
                $this->filter('wp_trim_excerpt', 'wpCustomTrimExcerpt');
                break;
        }
    }

    public function wpCustomTrimExcerpt($content)
    {
        $socialShareHTML = $this->applyContentCallback('');

        $text = get_the_content('');

        $text = str_replace($socialShareHTML, '', $text);

        $text = strip_shortcodes( $text );

        $text = str_replace(']]>', ']]&gt;', $text);

        $excerpt_length = apply_filters( 'excerpt_length', 55 );

        $excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );

        $text = wp_trim_words( $text, $excerpt_length, $excerpt_more );

        return $text;
    }

    /**
     * Handles the project.
     * @return null|string
     */
    public function handle()
    {
        if ($this->project->isShowAtShortcode() || $this->project->isShowAtPopup()) {
            return $this->build();
        }

        $this->listen();

        return null;
    }

    /**
     * Callback method to add project's widget to the widgets list.
     */
    public function applyWidgetCallback()
    {
        global $wp_widget_factory;

        $className = 'SocialSharing_Projects_Widget';
        $widget = $className.'_'.$this->project->getId();

        $wp_widget_factory->widgets[$widget] = new $className($this);
    }

    /**
     * Callback method to render the buttons for sidebar.
     */
    public function applySidebarCallback()
    {
        echo $this->build();
    }

    /**
     * Callback method to render buttons inside the page or post.
     * @param string $content The content of the post
     * @return string
     */
    public function applyContentCallback($content)
    {
        $content = sprintf('%1$s%2$s%3$s',
            ('above' === $this->project->getExtra() || 'above_below' === $this->project->getExtra())
                ? $this->build()
                : '',
            $content,
            ('below' === $this->project->getExtra() || 'above_below' === $this->project->getExtra())
                ? $this->build()
                : ''
        );

        return $content;
    }

    /**
     * Returns current builder.
     * @return \SocialSharing_Projects_Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Returns current project.
     * @return \SocialSharing_Projects_Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Shows notice about the unavailable button sets.
     * This may happens while PRO version is disabled but one or more projects
     * uses PRO button sets.
     */
    public function invalidBuilderNotice()
    {
        if (!$this->environment->isPluginPage()) {
            return;
        }

        $twig = $this->environment->getTwig();
        $twig->display(
            'notice.twig',
            array(
                'type' => 'error',
                'message' => sprintf(
                    $this->environment->translate('Selected button set is unavailable for the project "%s", "Flat 1" will be used.'),
                    $this->project->getTitle()
                )
            )
        );
    }

    /**
     * Creates new instance of the builder for the current project.
     * @return \SocialSharing_Projects_Builder
     */
    protected function instantiateBuilder()
    {
        $builder = null;

        if ('flat' === substr($this->project->get('design', 'flat-1'), 0, 4)) {
            $builder = new SocialSharing_Projects_Builder_Flat(
                $this->project,
                $this->environment
            );
        } elseif (class_exists('SocialSharingPro_Projects_Builder_Mono') && 'mono' === substr($this->project->get('design'), 0, 4)) {
            $builder = new SocialSharingPro_Projects_Builder_Mono(
                $this->project,
                $this->environment
            );
        } elseif (class_exists('SocialSharingPro_Projects_Builder_Bordered') && 'bordered' === substr($this->project->get('design'), 0, 8)) {
            $builder = new SocialSharingPro_Projects_Builder_Bordered(
                $this->project,
                $this->environment
            );
        } elseif (class_exists('SocialSharingPro_Projects_Builder_Livejournal') && 'livejournal' === substr($this->project->get('design'), 0, 11)) {
            $builder = new SocialSharingPro_Projects_Builder_Livejournal(
                $this->project,
                $this->environment
            );
        } elseif (class_exists('SocialSharingPro_Projects_Builder_Various') && 'various' === substr($this->project->get('design'), 0, 7)) {
            $builder = new SocialSharingPro_Projects_Builder_Various(
                $this->project,
                $this->environment
            );
        } else {
            $this->project->setSettings(
                array_merge(
                    $this->project->getSettings(),
                    array(
                        'design' => 'flat-1'
                    )
                )
            );

            $builder = new SocialSharing_Projects_Builder_Flat(
                $this->project,
                $this->environment
            );

            add_action('admin_notices', array($this, 'invalidBuilderNotice'));
        }

        /** @var SocialSharing_Networks_Module $networks */
        if ($this->project->has('show_more')
            && null !== $networks = $this->environment->getModule('networks')
        ) {
            $builder = new SocialSharing_Projects_Builder_Decorator_Popup(
                $builder, $networks->getAll()
            );

            /** @var SocialSharing_Ui_Module $ui */
            $ui = $this->environment->getModule('ui');
            $ui->addAsset(
                $ui->create('script', 'social-sharing-builder-popup')
                    ->setHookName('before_html_build')
                    ->setModuleSource(
                        $this->environment->getModule('projects'),
                        'js/builder/popup.js'
                    )
                    ->setCachingAllowed($this->environment->isProd())
            );

            $ui->addAsset(
                $ui->create('style', 'social-sharing-builder-popup')
                    ->setHookName('before_html_build')
                    ->setModuleSource(
                        $this->environment->getModule('projects'),
                        'css/builder/popup.css'
                    )
                    ->setCachingAllowed($this->environment->isProd())
            );
        }

        if ($this->project->has('sidebar_navigation')) {
            $builder = new SocialSharing_Projects_Builder_Decorator_Navigation(
                $builder
            );

            /** @var SocialSharing_Ui_Module $ui */
            $ui = $this->environment->getModule('ui');
            $ui->addAsset(
                $ui->create('script', 'social-sharing-builder-navigation')
                    ->setHookName('before_html_build')
                    ->setModuleSource(
                        $this->environment->getModule('projects'),
                        'js/builder/navigation.js'
                    )
                    ->setCachingAllowed($this->environment->isProd())
            );

            $ui->addAsset(
                $ui->create('style', 'social-sharing-builder-navigation')
                    ->setHookName('before_html_build')
                    ->setModuleSource(
                        $this->environment->getModule('projects'),
                        'css/builder/navigation.css'
                    )
                    ->setCachingAllowed($this->environment->isProd())
            );
        }



        return $builder;
    }

    /**
     * Adds the filter.
     * @param string $hook Hook name
     * @param string $method Method name
     */
    protected function filter($hook, $method)
    {
        add_filter($hook, array($this, $method));
    }

    /**
     * Adds the action.
     * @param string $hook Hook name
     * @param string $method Method name
     */
    protected function action($hook, $method)
    {
        add_action($hook, array($this, $method));
    }
}