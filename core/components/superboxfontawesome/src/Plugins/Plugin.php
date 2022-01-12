<?php
/**
 * Abstract plugin
 *
 * @package superboxfontawesome
 * @subpackage plugin
 */

namespace TreehillStudio\SuperBoxFontawesome\Plugins;

use modX;
use TreehillStudio\SuperBoxFontawesome\SuperBoxFontawesome;

/**
 * Class Plugin
 */
abstract class Plugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var SuperBoxFontawesome $superboxfontawesome */
    protected $superboxfontawesome;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    /**
     * Plugin constructor.
     *
     * @param $modx
     * @param $scriptProperties
     */
    public function __construct($modx, &$scriptProperties)
    {
        $this->scriptProperties = &$scriptProperties;
        $this->modx =& $modx;
        $corePath = $this->modx->getOption('superboxfontawesome.core_path', null, $this->modx->getOption('core_path') . 'components/superboxfontawesome/');
        $this->superboxfontawesome = $this->modx->getService('superboxfontawesome', 'SuperBoxFontawesome', $corePath . 'model/superboxfontawesome/', [
            'core_path' => $corePath
        ]);
    }

    /**
     * Run the plugin event.
     */
    public function run()
    {
        $init = $this->init();
        if ($init !== true) {
            return;
        }

        $this->process();
    }

    /**
     * Initialize the plugin event.
     *
     * @return bool
     */
    public function init()
    {
        return true;
    }

    /**
     * Process the plugin event code.
     *
     * @return mixed
     */
    abstract public function process();
}