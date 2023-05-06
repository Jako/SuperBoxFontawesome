<?php
/**
 * Fontawesome get list processor
 *
 * @package superboxfontawesome
 * @subpackage processors
 */

use TreehillStudio\SuperBoxFontawesome\Processors\Processor;

class SuperboxselectFontawesomeGetListProcessor extends Processor
{
    public function process()
    {
        // Get Properties
        $tvid = $this->getProperty('tvid', 0);
        /** @var modTemplateVar $tv */
        $tv = $this->modx->getObject('modTemplateVar', $tvid);
        if ($tv) {
            $tvProperties = $tv->get('input_properties');
        } else {
            $tvProperties = [];
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Invalid template variable ID!', '', 'SuperBoxSelect');
        }

        $fontawesomeUrl = $this->modx->getOption('fontawesomeUrl', $tvProperties, $this->superboxfontawesome->getOption('fontawesomeUrl', [], 'https://raw.githubusercontent.com/FortAwesome/Font-Awesome/4.x/scss/_icons.scss'), true);
        $fontawesomePrefix = $this->modx->getOption('fontawesomePrefix', $tvProperties, $this->superboxfontawesome->getOption('fontawesomePrefix', [], 'fa-'), true);
        $excludeClasses = $this->modx->getOption('excludeClasses', $tvProperties, $this->superboxfontawesome->getOption('excludeClasses', [], 'ul,li'), true);
        $excludeClasses = ($excludeClasses) ? array_filter(array_map('trim', explode(',', $excludeClasses))) : [];

        $limit = $this->getProperty('limit', 10);
        $start = $this->getProperty('start', 0);
        $id = $this->getProperty('id');

        $cacheKey = $this->superboxfontawesome->getOption('cacheKey', [], 'superboxfontawesome.fontawesome');
        $cacheManager = $this->modx->getCacheManager();
        $faIcons = $cacheManager->get($cacheKey);
        if (!$faIcons) {
            // Get the FontAwesome source file
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $fontawesomeUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $css = curl_exec($ch);
            curl_close($ch);

            if (!$css) {
                return $this->failureLog('Could not download the FontAwesome scss source!');
            }
            $faIcons = [];
            $regex = '/\.#\{\$fa-css-prefix\}-([\w-]*):before/';
            $matches = [];
            if (preg_match_all($regex, $css, $matches)) {
                $icons = array_diff($matches[1], $excludeClasses);
                foreach ($icons as $icon) {
                    $faIcons[$icon] = [
                        'id' => $fontawesomePrefix . $icon,
                        'title' => $icon
                    ];
                }
            } else {
                return $this->failureLog('Could not find the icons in the FontAwesome scss!');
            }

            ksort($faIcons, SORT_NATURAL);

            $cacheManager->set($cacheKey, $faIcons);
        }

        // One icon selected?
        if ($id) {
            if (substr($id, 0, strlen($fontawesomePrefix)) == $fontawesomePrefix) {
                $id = substr($id, strlen($fontawesomePrefix));
            }
            if (isset($faIcons[$id])) {
                return $this->outputArray([$faIcons[$id]]);
            } else {
                return $this->failureLog('Could not find the icon in the FontAwesome scss!');
            }
        }

        // Filter the icons by a query
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $valuesqry = $this->getProperty('valuesqry');
            if (!empty($valuesqry)) {
                $this->setProperty('query', explode('|', $query));
            } else {
                $this->setProperty('query', [$query]);
            }
            $faIcons = array_filter($faIcons, [$this, 'filterIcon']);
        }

        $faIcons = array_values($faIcons);
        $count = count($faIcons);
        $output = array_splice($faIcons, $start, $limit);

        return $this->outputArray($output, $count);
    }

    protected function failureLog($msg = '', $object = null)
    {
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, $msg, '', 'SuperBoxfontawesome');
        return parent::failure($msg, $object);
    }

    protected function filterIcon($var)
    {
        $success = false;
        foreach ($this->getProperty('query') as $query) {
            $success |= strpos($var['id'], $query) !== false;
        }
        return $success;
    }
}

return 'SuperboxselectFontawesomeGetListProcessor';
