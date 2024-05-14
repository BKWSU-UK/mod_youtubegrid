<?php
namespace BKWSU\Module\Youtubegrid\Site\Helper;
defined('_JEXEC') or die;

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;

class YoutubegridHelper
{
    public static function getPlaylistVideos($apikey, $playlistid, $videos_per_page, $pageToken = '')
    {
        $playlistUri = Uri::getInstance('https://www.googleapis.com/youtube/v3/playlistItems');
        $playlistUri->setVar('part', 'snippet');
        $playlistUri->setVar('maxResults', $videos_per_page);
        $playlistUri->setVar('playlistId', $playlistid);
        $playlistUri->setVar('key', $apikey);
        $playlistUri->setVar('pageToken', $pageToken);

        $youtubeUri = Uri::getInstance('https://www.youtube.com/embed/');

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Referer: https://wembley-test.innerspace.org"
            ]
        ]);
        //echo Route::_($playlistUri->toString());
        $response = file_get_contents(Route::_($playlistUri->toString()), false, $context);
        $data = json_decode($response, true);

        $videos = [
            'items' => [],
            'nextPageToken' => $data['nextPageToken'] ?? '',
            'prevPageToken' => $data['prevPageToken'] ?? ''
        ];

        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $snippet = $item['snippet'];
                $videoId = $snippet['resourceId']['videoId'];
                $videos['items'][] = [
                    'id' => $videoId,
                    'url' => Route::_($youtubeUri->toString()) . $videoId,
                    'thumbnail' => !isset($snippet['thumbnails']['medium']) ? null : $snippet['thumbnails']['medium']['url'],
                    'title' => $snippet['title'],
                    'description' => $snippet['description'],
                    'publishedAt' => $snippet['publishedAt']
                ];
            }
        }
        else {
            echo "<p><strong>No videos found in this playlist.</strong></p>";
        }

        return $videos;
    }

    public static function shortenText($text, $maxChars = 100) {
        if (strlen($text) > $maxChars) {
            return substr($text, 0, $maxChars) . '...';
        }
        return $text;
    }
}
