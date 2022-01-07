<?php
/**
 * Options processor
 *
 * @package superboxfontawesome
 * @subpackage processors
 */

namespace TreehillStudio\SuperBoxFontawesome\Processors;

class OptionsProcessor extends Processor
{
    public $fieldTpl = '{title} ({id})';
    public $inputOptionType = '';

    /**
     * Run the processor and return the result.
     *
     * @return string
     */
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

    /**
     * Get the field template.
     *
     * @return string
     */
    public function getFieldTpl()
    {
        return $this->fieldTpl;
    }

    /**
     * Get the input option xtype and register the panel javascript
     *
     * @return array|null
     */
    public function getInputOptionType()
    {
        if ($this->inputOptionType) {
            $inputOptionType = [
                'type' => "{xtype: 'superboxfontawesome-panel-inputoptions-$this->inputOptionType', params: config.params}",
                'success' => true
            ];
            return $inputOptionType;
        } else {
            return null;
        }
    }
}
