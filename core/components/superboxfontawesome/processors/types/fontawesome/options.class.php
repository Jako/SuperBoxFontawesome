<?php
/**
 * Fontawesome options processor
 *
 * @package superboxfontawesome
 * @subpackage processors
 */

use TreehillStudio\SuperBoxFontawesome\Processors\OptionsProcessor;

class SuperboxselectFontawesomeOptionsProcessor extends OptionsProcessor
{
    public $inputOptionType = 'fontawesome';
    public $fieldTpl = '<i class="icon icon-{title}"></i> {title}';

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function initialize()
    {
        $this->modx->lexicon('superboxfontawesome.fontawesome');
        return parent::initialize();
    }
}

return 'SuperboxselectFontawesomeOptionsProcessor';
