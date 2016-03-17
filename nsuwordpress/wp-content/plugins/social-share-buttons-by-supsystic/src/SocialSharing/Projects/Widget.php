<?php


class SocialSharing_Projects_Widget extends WP_Widget
{
    /**
     * @var SocialSharing_Projects_Handler
     */
    protected $sharer;

    /**
     * {@inheritdoc}
     */
    public function __construct(SocialSharing_Projects_Handler $sharer)
    {
        parent::__construct(
            'supsystic_social_sharing_' . $sharer->getProject()->getId(),
            'Social Sharing: "'.$sharer->getProject()->getTitle().'"',
            array('description' => 'Social sharing project "'.$sharer->getProject()->getTitle().'"')
        );

        $this->sharer = $sharer;
    }

    /**
     * {@inheritdoc}
     */
    public function widget($args, $instance)
    {
        $title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base );

        echo sprintf(
            '%1$s%2$s%3$s%4$s',
            $args['before_widget'],
            $title ? $args['before_title'] . $title . $args['after_title'] : '',
            $this->sharer->build(),
            $args['after_widget']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function form($instance)
    {
        $instance = wp_parse_args((array)$instance, array('title' => ''));
        $title = strip_tags($instance['title']);

        echo '<p>' .
                '<label for="'.$this->get_field_id('title').'">Title:</label>' .
                '<input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$title.'">' .
            '</p>';
    }

    /**
     * {@inheritdoc}
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }
}