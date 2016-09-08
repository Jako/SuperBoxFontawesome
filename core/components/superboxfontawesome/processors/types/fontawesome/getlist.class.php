<?php

/**
 * Get list processor for SuperBoxSelect TV.
 *
 * @package superboxselect
 * @subpackage processor
 */
class SuperboxselectFontawesomeGetListProcessor extends modProcessor
{
    public function process()
    {
        $modx =& $this->modx;
        $scriptProperties = $this->getProperties();

        $fontawesomeUrl = $this->modx->getOption('superboxselect.fontawesomeUrl', $scriptProperties, 'https://raw.githubusercontent.com/FortAwesome/Font-Awesome/master/scss/_icons.scss');
        $fontawesomePrefix = $this->modx->getOption('superboxselect.fontawesomePrefix', $scriptProperties, 'fa-');
        $excludeClasses = $this->modx->getOption('superboxselect.excludeClasses', $scriptProperties, 'ul,li');
        $excludeClasses = ($excludeClasses) ? array_filter(array_map('trim', explode(',', $excludeClasses))) : array();

        $limit = $this->getProperty('limit', 10);
        $start = $this->getProperty('start', 0);
        $id = $this->getProperty('id');

        $cacheKey = $this->modx->getOption('cacheKey', $scriptProperties, 'superboxselect.fontawesome');
        $provider = $this->modx->cacheManager->getCacheProvider('default');
        $faIcons = $provider->get($cacheKey);
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
            $faIcons = array();
            $regex = '/\.#\{\$fa-css-prefix\}-([\w-]*):before/';
            $matches = array();
            if (preg_match_all($regex, $css, $matches)) {
                $icons = array_diff($matches[1], $excludeClasses);
                foreach ($icons as $icon) {
                    $faIcons[$icon] = array(
                        'id' => $fontawesomePrefix . $icon,
                        'title' => $icon
                    );
                }
            } else {
                return $this->failureLog('Could not find the icons in the FontAwesome scss!');
            }

            ksort($faIcons, SORT_NATURAL);

            $provider->set($cacheKey, $faIcons, 0);
        }

        // One icon selected?
        if ($id) {
            if (isset($faIcons[$id])) {
                return $this->outputArray(array($faIcons[$id]));
            } else {
                return $this->failureLog('Could not find the icon in the FontAwesome scss!');
            }
        }

        // Filter the icons by a query
        $query = $this->getProperty('query');
        if ($query) {
            $faIcons = array_filter($faIcons, array($this, 'filterIcon'));
        }

        $total = count($faIcons);
        $output = array_splice(array_values($faIcons), $start, $limit);

        return $this->outputArray($output, $total);
    }

    protected function failureLog($msg = '', $object = null)
    {
        $this->modx->log(modX::LOG_LEVEL_ERROR, $msg, '', 'superboxselect.fontawesome');
        return parent::failure($msg, $object);
    }

    protected function filterIcon($var)
    {
        return (strpos($var['id'], $this->getProperty('query')) !== false);
    }
}

return 'SuperboxselectFontawesomeGetListProcessor';
