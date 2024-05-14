<?php
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;
use BKWSU\Module\Youtubegrid\Site\Helper\YoutubegridHelper;

//require_once '../helper.php';

$apikey = $params->get('apikey', '');
$playlistid = $params->get('playlistid', '');
$videos_per_page = $params->get('videos_per_page', 12);

$app = Factory::getApplication();
$pageToken = $app->input->get('pageToken', '');

$videos = YoutubegridHelper::getPlaylistVideos($apikey, $playlistid, $videos_per_page, $pageToken);

require ModuleHelper::getLayoutPath('mod_youtubegrid');
