<?php

namespace JuniorEsiee\BusinessBundle\Twig;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\Translation\Translator;

/**
 * @Service
 * @Tag("twig.extension")
 */
class StateFormat extends \Twig_Extension
{
    private $translator;

    /**
     * @InjectParams({
     *     "translator" = @Inject("translator")
     * })
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return array(
            'projectState' => new \Twig_Filter_Method($this, 'projectState', array('is_safe' => array('html'))),
        );
    }

    public function projectState($state)
    {
        $result = array(
            'state_closed'              => array('pink', 'Cloturé'),
            'state_aborted'             => array('orange', 'Avorté'),
            'state_opened'              => array('green', 'Ouvert'),
            'state_waiting_information' => array('purple', 'Att Rens'),
            'state_waiting_student'     => array('teal', 'Att Real'),
            'state_waiting_commercial'  => array('blue', 'Att Comm'),
        );

        return '<div class="tiny ui '.$result[$state][0].' label" title="'.$this->translator->trans($state, array(), 'JuniorEsieeBusinessBundle').'">'.$result[$state][1].'</div>';
    }

    public function getName()
    {
        return 'project_state_format';
    }
}
