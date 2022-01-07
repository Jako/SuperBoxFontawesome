<?php
/**
 * SuperBoxFontawesome connector
 *
 * @package superboxfontawesome
 * @subpackage connector
 *
 * @var modX $modx
 */
require_once dirname(__FILE__, 4) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('superboxfontawesome.core_path', null, $modx->getOption('core_path') . 'components/superboxfontawesome/');
/** @var SuperBoxFontawesome $superboxfontawesome */
$superboxfontawesome = $modx->getService('superboxfontawesome', 'SuperBoxFontawesome', $corePath . 'model/superboxfontawesome/', [
    'core_path' => $corePath
]);

// Handle request
$modx->request->handleRequest([
    'processors_path' => $superboxfontawesome->getOption('processorsPath'),
    'location' => ''
]);
