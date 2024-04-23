<?php
defined('_JEXEC') or die;

class ModMyVideoGridHelper
{
    public static function getPlaylistVideos($apikey, $playlistid, $videos_per_page, $pageToken = '')
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Referer: https://wembley-test.innerspace.org"
            ]
        ]); 
        $url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults={$videos_per_page}&playlistId={$playlistid}&key={$apikey}&pageToken={$pageToken}";
        $response = file_get_contents($url, false, $context);
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
                    'url' => 'https://www.youtube.com/embed/' . $videoId,
                    'thumbnail' => $snippet['thumbnails']['high']['url'],
                    'title' => $snippet['title'],
                    'description' => $snippet['description'],
                    'publishedAt' => $snippet['publishedAt']
                ];
            }
        }

        return $videos;
    }
}
