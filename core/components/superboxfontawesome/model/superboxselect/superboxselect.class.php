<?php
/**
 * SuperBoxSelect classfile
 *
 * Copyright 2011-2016 by Benjamin Vauchel <contact@omycode.fr>
 *
 * @package superboxselect
 * @subpackage classfile
 */

/**
 * class SuperBoxSelect
 */
class SuperBoxSelect
{
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'superboxselect';

    /**
     * The version
     * @var string $version
     */
    public $version = '2.1.0';

    /**
     * The class config
     * @var array $config
     */
    public $config = array();

    /**
     * SuperBoxSelect constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $config An config array. Optional.
     */
    function __construct(modX &$modx, $config = array())
    {
        $this->modx =& $modx;

        $corePath = $this->getOption('core_path', $config, $this->modx->getOption('core_path') . 'components/' . $this->namespace . '/');
        $assetsPath = $this->getOption('assets_path', $config, $this->modx->getOption('assets_path') . 'components/' . $this->namespace . '/');
        $assetsUrl = $this->getOption('assets_url', $config, $this->modx->getOption('assets_url') . 'components/' . $this->namespace . '/');

        // Load some default paths for easier management
        $this->config = array_merge(array(
            'namespace' => $this->namespace,
            'version' => $this->version,
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'vendorPath' => $corePath . 'vendor/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'pagesPath' => $corePath . 'elements/pages/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
            'controllersPath' => $corePath . 'controllers/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'connectorUrl' => $assetsUrl . 'connector.php',
        ), $config);

        // Set default options
        $this->config = array_merge($this->config, array(
            'advanced' => (bool)$this->getOption('advanced')
        ));

        $this->modx->lexicon->load($this->namespace . ':default');
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = array(), $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->config)) {
                $option = $this->config[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }
        return $option;
    }

    /**
     * Render supporting javascript to try and help it work with MIGX etc
     */
    public function includeScriptAssets()
    {
        $assetsUrl = $this->getOption('assetsUrl');
        $jsUrl = $this->getOption('jsUrl') . 'mgr/';
        $jsSourceUrl = $assetsUrl . '../../../source/js/mgr/';
        $cssUrl = $this->getOption('cssUrl') . 'mgr/';
        $cssSourceUrl = $assetsUrl . '../../../source/css/mgr/';

        if ($this->getOption('debug') && ($assetsUrl != MODX_ASSETS_URL . 'components/superboxselect/')) {
            $this->modx->regClientCSS($cssSourceUrl . 'superboxselect.css');
            $this->modx->regClientStartupScript($jsSourceUrl . 'superboxselect.js?v=v' . $this->version);
            $this->modx->regClientStartupScript($jsSourceUrl . 'superboxselect.panel.inputoptions.js?v=v' . $this->version);
            $this->modx->regClientStartupScript($jsSourceUrl . 'superboxselect.panel.inputoptions.resources.js?v=v' . $this->version);
            $this->modx->regClientStartupScript($jsSourceUrl . 'superboxselect.panel.inputoptions.users.js?v=v' . $this->version);
            $this->modx->regClientStartupScript($jsSourceUrl . 'superboxselect.combo.templatevar.js?v=v' . $this->version);
            $this->modx->regClientStartupScript($jsSourceUrl . 'superboxselect.renderer.js?v=v' . $this->version);
        } else {
            $this->modx->regClientCSS($cssUrl . 'superboxselect.min.css');
            $this->modx->regClientStartupScript($jsUrl . 'superboxselect.min.js?v=v' . $this->version);
        }
        $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">'
            . ' SuperBoxSelect.config = ' . json_encode($this->config) . ';'
            . '</script>');
    }
}

define('superboxselect', true);
