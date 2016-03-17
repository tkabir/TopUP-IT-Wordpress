<?php


class SocialSharing_Projects_Builder_Decorator_Navigation extends SocialSharing_Projects_Builder
{
    /**
     * @var SocialSharing_Projects_Builder
     */
    private $builder;

    /**
     * Constructs the navigation decorator.
     * @param \SocialSharing_Projects_Builder $builder
     */
    public function __construct(SocialSharing_Projects_Builder $builder)
    {
        parent::__construct($builder->getProject(), $builder->getEnvironment());

        $this->builder = $builder;
    }

    /**
     * Returns the composite.
     *
     * @throws UnexpectedValueException If
     *                                  SocialSharing_Project_Builder::getButtons()
     *                                  returned invalid value.
     * @return \SocialSharing_HtmlBuilder_Element
     */
    public function getComposite()
    {
        $composite = $this->builder->getComposite();

        if (!$this->builder->getProject()->isShowAtSidebar()) {
            return $composite;
        }

        $composite->addElement($this->getToggleButton());

        return $composite;
    }

    /**
     * @return SocialSharing_HtmlBuilder_AbstractElement
     */
    protected function getToggleButton()
    {
        $builder = $this->builder->getBuilder();

        $position = $this->builder->getProject()->getExtra('left');
        $type = 'arrow';
        $icon = 'fa-' . $type . '-' . str_replace(
                array('top', 'bottom'),
                array('up', 'down'),
                $position
            );
        $pairs = array(
            'left'  => 'right',
            'right' => 'left',
            'up'    => 'down',
            'down'  => 'up'
        );

        return $builder->createElement(
            'button',
            array(
                $builder->createAttribute(
                    'class',
                    'social-sharing-navigation-toggle'
                ),
                $builder->createAttribute(
                    'title',
                    $builder->getEnvironment()->translate('Toggle')
                ),
                $builder->createAttribute(
                    'data-pointer',
                    $position
                ),
                $builder->createAttribute(
                    'data-replace',
                    $icon
                ),
                $builder->createAttribute(
                    'data-replace-with',
                    @strtr($icon, $pairs)
                )
            )
        )->addElement(
            $builder->createElement(
                'i',
                array(
                    $builder->createAttribute(
                        'class',
                        array(
                            'fa',
                            'fa-fw',
                            $icon
                        )
                    )
                )
            )
        );
    }

    /**
     * Returns the composite of the button.
     *
     * @param \SocialSharing_Projects_Builder_Network $network
     *
     * @return \SocialSharing_HtmlBuilder_AbstractElement
     */
    public function getButton(SocialSharing_Projects_Builder_Network $network)
    {
        return $this->builder->getButton($network);
    }

    /**
     * Returns the unique builder name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->builder->getName();
    }
}