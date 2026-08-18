<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;

final class pkg_jemeventhubInstallerScript
{
    public function postflight(string $type, $parent): bool
    {
        if (!in_array($type, ['install', 'update', 'discover_install'], true)) {
            return true;
        }

        $db = Factory::getContainer()->get(DatabaseInterface::class);

        foreach ([
            ['folder' => 'system', 'element' => 'jemeventhubplacement'],
            ['folder' => 'ajax', 'element' => 'jemeventhubsearch'],
        ] as $plugin) {
            $query = $db->getQuery(true)
                ->update($db->quoteName('#__extensions'))
                ->set($db->quoteName('enabled') . ' = 1')
                ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
                ->where($db->quoteName('folder') . ' = ' . $db->quote($plugin['folder']))
                ->where($db->quoteName('element') . ' = ' . $db->quote($plugin['element']));

            $db->setQuery($query)->execute();
        }

        return true;
    }
}
