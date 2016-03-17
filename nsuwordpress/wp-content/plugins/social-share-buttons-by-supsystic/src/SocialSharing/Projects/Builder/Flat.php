<?php


class SocialSharing_Projects_Builder_Flat extends SocialSharing_Projects_Builder_Standard
{
    /**
     * Returns the unique builder name.
     *
     * @return string
     */
    public function getName()
    {
        return 'flat';
    }

    /**
     * Returns button composite.
     * @param SocialSharing_Projects_Builder_Network $network
     * @return SocialSharing_HtmlBuilder_AbstractElement
     */
    public function getButton(SocialSharing_Projects_Builder_Network $network)
    {
        $button = parent::getButton($network);
        $project = $this->getProject();
        $builder = $this->getBuilder();

        if (!in_array($project->get('design'), array('flat-8', 'flat-9'), false)) {
            return $button;
        }

        $text = $network->getButtonText();

        if (!is_string($text) || $text === '') {
            $text = $this->getEnvironment()->translate('Share');
        }

        $span = $builder->createElement('span');
        $span->addElement($builder->createTextElement($text));

        // Current we don't need to implement deep search,
        // so implement it in the future if it is will be required.
        foreach ($button->getElements() as $element) {
            if ('i' === $element->getTagName()) {
                $element->addElement($span);
            }
        }

        return $button;
    }
}
