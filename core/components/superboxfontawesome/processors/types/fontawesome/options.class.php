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

    public function useRenderOptions($defaults)
    {
        $renderOptions = [
            'params' => [
                'fieldTpl' => ($defaults['fieldTpl']) ?: $this->fieldTpl
            ],
            'baseParams' => []
        ];
        if ($this->getProperty('useRequest')) {
            $baseParams = [
                'useRequest' => true,
                'fontawesomeUrl' => ($defaults['fontawesomeUrl']) ?: null,
                'fontawesomePrefix' => ($defaults['fontawesomePrefix']) ?: null,
                'excludeClasses' => ($defaults['excludeClasses']) ?: null
            ];
            foreach ($baseParams as $key => $value) {
                if (is_null($value)) {
                    unset($baseParams[$key]);
                }
            }
            $renderOptions['baseParams'] = $baseParams;

            $params = [];
            foreach ($params as $key => $value) {
                if (is_null($value)) {
                    unset($params[$key]);
                }
            }
            $renderOptions['params'] = array_merge($renderOptions['params'], $params);
        }
        return $renderOptions;
    }
}

return 'SuperboxselectFontawesomeOptionsProcessor';
