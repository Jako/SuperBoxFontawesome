<?php
/**
 * @package superboxfontawesome
 * @subpackage plugin
 */

namespace TreehillStudio\SuperBoxFontawesome\Plugins\Events;

use TreehillStudio\SuperBoxFontawesome\Plugins\Plugin;

class OnSuperboxselectTypeOptions extends Plugin
{
    /**
     * {@inheritDoc}
     */
    public function process()
    {
        $internalTypes = $this->superboxfontawesome->getTypes();
        $method = 'get' . ucfirst($this->modx->getOption('option', $this->scriptProperties));
        if (method_exists($this, $method)) {
            call_user_func([$this, $method], $internalTypes);
        }
    }

    /**
     * Get list internal types
     *
     * @param $internalTypes
     */
    private function getList($internalTypes)
    {
        $list = [];
        foreach ($internalTypes as $internalType) {
            $list[] = [
                'id' => $internalType,
                'name' => $this->modx->lexicon('superboxfontawesome.' . $internalType)
            ];
        }
        $this->modx->event->output(serialize($list));
    }

    /**
     * Get inputoptions of internal types
     *
     * @param $internalTypes
     */
    private function getInputOptions($internalTypes)
    {
        $inputOptions = [];
        foreach ($internalTypes as $internalType) {
            $response = $this->modx->runProcessor('types/' . $internalType . '/options', [
                'option' => 'inputOptionType',
            ], [
                'processors_path' => $this->superboxfontawesome->getOption('processorsPath')
            ]);
            if (empty($response->errors)) {
                $inputOptions[] = $response->response['type'];
            }
        }
        $this->modx->event->output(serialize($inputOptions));
    }

    /**
     * Get renderoptions of internal types
     *
     * @param $internalTypes
     */
    private function getRenderOptions($internalTypes)
    {
        $params = $this->modx->getOption('params', $this->scriptProperties);
        $typeOptions = [];
        foreach ($internalTypes as $internalType) {
            $response = $this->modx->runProcessor('types/' . $internalType . '/options', [
                'option' => 'renderOptions',
                'useRequest' => $params['useRequest'],
                'params' => $params
            ], [
                'processors_path' => $this->superboxfontawesome->getOption('processorsPath')
            ]);
            if (empty($response->errors)) {
                $typeOptions[$internalType] = $response->response;
            }
        }
        $this->modx->event->output(serialize($typeOptions));
    }

    /**
     * @param $internalTypes
     */
    private function getScripts($internalTypes)
    {
        if ($this->modx->getOption('debug', $this->scriptProperties) && ($this->superboxfontawesome->getOption('assetsUrl') != MODX_ASSETS_URL . 'components/superboxfontawesome/')) {
            foreach ($internalTypes as $internalType) {
                $this->modx->controller->addJavascript($this->superboxfontawesome->getOption('assetsUrl') . '../../../source/js/types/' . $internalType . '/superboxfontawesome.panel.inputoptions.js?v=v' . $this->superboxfontawesome->version);
            }
        } else {
            foreach ($internalTypes as $internalType) {
                $this->modx->controller->addJavascript($this->superboxfontawesome->getOption('jsUrl') . 'types/' . $internalType . '/superboxfontawesome.panel.inputoptions.min.js?v=v' . $this->superboxfontawesome->version);
            }
        }
        $this->modx->controller->addLexiconTopic('superboxfontawesome:default');
    }
}
