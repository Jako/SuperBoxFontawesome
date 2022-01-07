<?php
/**
 * SuperBoxFontawesome Plugin
 *
 * @package superboxfontawesome
 * @subpackage plugin
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$className = 'TreehillStudio\SuperBoxFontawesome\Plugins\Events\\' . $modx->event->name;

$corePath = $modx->getOption('superboxfontawesome.core_path', null, $modx->getOption('core_path') . 'components/superboxfontawesome/');
/** @var SuperBoxFontawesome $superboxfontawesome */
$superboxfontawesome = $modx->getService('superboxfontawesome', 'SuperBoxFontawesome', $corePath . 'model/superboxfontawesome/', [
    'core_path' => $corePath
]);

if ($superboxfontawesome) {
    if (class_exists($className)) {
        $handler = new $className($modx, $scriptProperties);
        if (get_class($handler) == $className) {
            $handler->run();
        } else {
            $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' could not be initialized!', '', 'SuperBoxFontawesome Plugin');
        }
    } else {
        $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' was not found!', '', 'SuperBoxFontawesome Plugin');
    }
}

return;