<?php


class SocialSharing_Projects_Builder_Decorator_Popup extends SocialSharing_Projects_Builder
{
    /**
     * @var SocialSharing_Projects_Builder
     */
    private $builder;

    /**
     * @var array
     */
    private $networks;

    /**
     * Constructs the builder decorator.
     *
     * @param \SocialSharing_Projects_Builder $builder
     * @param array                           $networks
     */
    public function __construct(SocialSharing_Projects_Builder $builder, array $networks)
    {
        parent::__construct($builder->getProject(), $builder->getEnvironment());

        $this->builder = $builder;
        $this->networks = $networks;
    }

    /**
     * Returns the composite.
     * @throws UnexpectedValueException If
     *                                  SocialSharing_Project_Builder::getButtons()
     *                                  returned invalid value.
     * @return \SocialSharing_HtmlBuilder_Element
     */
    public function getComposite()
    {
        if (count($this->networks) === 0) {
            return $this->builder->getComposite();
        }

        $composite = $this->builder->getComposite();
        $container = $this->getPopupContainer();

        foreach ($this->networks as $data) {
            $container->addElement(
                $this->getButton(
                    new SocialSharing_Projects_Builder_Network(
                        (array)$data
                    )
                )
            );
        }

        $composite->addElement($this->getPopupButton());
        $composite->addElement($container);

        return $composite;
    }

    /**
     * Returns popup container.
     * @return \SocialSharing_HtmlBuilder_Element
     */
    protected function getPopupContainer()
    {
        $builder = $this->builder->getBuilder();

        return $builder->createElement(
            'div',
            array(
                $builder->createAttribute(
                    'class',
                    array(
                        'supsystic-social-sharing-popup'
                    )
                )
            )
        );
    }

    /**
     * Returns the popup button.
     * @return \SocialSharing_HtmlBuilder_AbstractElement
     */
    protected function getPopupButton()
    {
        $button = $this->builder->getButton(
            new SocialSharing_Projects_Builder_Network(
                array(
                    'class' => 'trigger-popup',
                    'id'    => -1,
                    'name'  => '__popup__',
                    'url'   => '#',
                    'title' => $this->getEnvironment()->translate('Show all networks')
                )
            )
        );

        foreach ($button->getAttributes() as $attribute) {
            if ($attribute->getName() === 'class') {
                $attribute->addValue('without-counter');
            }
        }

        return $button;
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

    /**
     * Returns the composite of the button.
     * @param \SocialSharing_Projects_Builder_Network $network
     * @return \SocialSharing_HtmlBuilder_AbstractElement
     */
    public function getButton(SocialSharing_Projects_Builder_Network $network)
    {
        return $this->builder->getButton($network);
    }
}