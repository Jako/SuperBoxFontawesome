<?php
/**
 * SuperBoxFontawesome
 *
 * Copyright 2016-2022 by Thomas Jakobi <office@treehillstudio.com>
 *
 * @package superboxfontawesome
 * @subpackage classfile
 */

namespace TreehillStudio\SuperBoxFontawesome;

use modX;

/**
 * Class SuperBoxFontawesome
 */
class SuperBoxFontawesome
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
    public $namespace = 'superboxfontawesome';

    /**
     * The package name
     * @var string $packageName
     */
    public $packageName = 'SuperBoxFontawesome';

    /**
     * The version
     * @var string $version
     */
    public $version = '2.0.1';

    /**
     * The class options
     * @var array $options
     */
    public $options = [];

    /**
     * SuperBoxFontawesome constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $options An array of options. Optional.
     */
    public function __construct(modX &$modx, $options = [])
    {
        $this->modx =& $modx;
        $this->namespace = $this->getOption('namespace', $options, $this->namespace);

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/' . $this->namespace . '/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/' . $this->namespace . '/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/' . $this->namespace . '/');
        $modxversion = $this->modx->getVersionData();

        // Load some default paths for easier management
        $this->options = array_merge([
            'namespace' => $this->namespace,
            'version' => $this->version,
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
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ], $options);

        // Add default options
        $this->options = array_merge($this->options, [
            'debug' => (bool)$this->modx->getOption($this->namespace . '.debug', null, '0') == 1,
            'modxversion' => $modxversion['version']
        ]);

        $lexicon = $this->modx->getService('lexicon', 'modLexicon');
        $lexicon->load($this->namespace . ':default');
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
    public function getOption($key, $options = [], $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("$this->namespace.$key", $this->modx->config)) {
                $option = $this->modx->getOption("$this->namespace.$key");
            }
        }
        return $option;
    }

    /**
     * Get the types
     *
     * @return array
     */
    public function getTypes()
    {
        $types = [];
        $files = glob($this->getOption('processorsPath') . 'types/*', GLOB_ONLYDIR);
        foreach ($files as $file) {
            $types[] = basename($file);
        }
        return $types;
    }

    /**
     * Get the inputoption types
     *
     * @return string
     */
    public function getInputOptionTypes()
    {
        $internalTypes = $this->getTypes();
        $types = [];
        foreach ($internalTypes as $internalType) {
            $response = $this->modx->runProcessor('types/' . $internalType . '/options', [
                'option' => 'inputOptionType',
            ], [
                'processors_path' => $this->getOption('processorsPath')
            ]);
            if (empty($response->errors)) {
                $types[] = $response->response['type'];
            }
        }

        $this->modx->smarty->assign('types', implode(',', $types));
        return $this->modx->smarty->fetch($this->getOption('templatesPath') . 'inputoptionstypes.tpl');
    }

}
