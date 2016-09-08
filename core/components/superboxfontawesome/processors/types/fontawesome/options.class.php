<?php

/**
 * Options processor for SuperBoxSelect TV.
 *
 * @package superboxselect
 * @subpackage processor
 */
class SuperboxselectFontawesomeOptionsProcessor extends modProcessor
{
    /**
     * @var array $languageTopics
     */
    public $languageTopics = array('superboxselect:default');

    public function process()
    {
        $option = $this->getProperty('option');

        $result = '';
        if (method_exists($this, 'get' . ucfirst($option))) {
            $method = 'get' . ucfirst($option);
            $result = $this->$method();
        }
        return $result;
    }

    public function getFieldTpl()
    {
        return '<i class="icon icon-{title}"></i> {title}';
    }
}

return 'SuperboxselectFontawesomeOptionsProcessor';
