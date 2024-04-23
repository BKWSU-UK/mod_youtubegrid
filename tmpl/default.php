<?php
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$doc->addStyleSheet('https://fonts.googleapis.com/icon?family=Material+Icons');
$doc->addStyleDeclaration('
    .video-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .video { position: relative; cursor: pointer; padding: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .thumbnail-wrapper { position: relative; }
    .video img { width: 100%; display: block; }
    .play-button { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-family: "Material Icons"; font-size: 48px; color: white; text-shadow: 0 1px 4px rgba(0,0,0,0.5); pointer-events: none; }
    .video-title, .video-desc, .video-date { text-align: center; margin-top: 8px; }
    .overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); z-index: 1000; display: none; }
    .overlay-content { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80%; height: 80%; }
    iframe { width: 100%; height: 100%; }
    .pagination { text-align: center; padding: 20px 0; }
    .pagination a { padding: 8px 16px; background: #f0f0f0; color: #333; margin: 0 4px; text-decoration: none; }
    .pagination a:hover { background: #ddd; }
');
$doc->addScriptDeclaration('
    document.addEventListener("DOMContentLoaded", function() {
        const videos = document.querySelectorAll(".video");
        videos.forEach(video => {
            video.addEventListener("click", function() {
                const videoId = this.getAttribute("data-video-id");
                const iframe = document.createElement("iframe");
                iframe.setAttribute("src", "https://www.youtube.com/embed/" + videoId + "?autoplay=1");
                iframe.setAttribute("frameborder", "0");
                iframe.setAttribute("allowfullscreen", "");
                document.querySelector(".overlay-content").innerHTML = "";
                document.querySelector(".overlay-content").appendChild(iframe);
                document.querySelector(".overlay").style.display = "flex";
            });
        });
        document.querySelector(".overlay").addEventListener("click", function(e) {
            if (e.target === this) {
                this.style.display = "none";
                this.querySelector(".overlay-content").innerHTML = "";
            }
        });
    });
');
function shortenText($text, $maxChars = 100) {
    if (strlen($text) > $maxChars) {
        return substr($text, 0, $maxChars) . '...';
    }
    return $text;
}
?>
<div class="video-grid">
    <?php foreach ($videos['items'] as $video): ?>
        <div class="video" data-video-id="<?php echo htmlspecialchars($video['id']); ?>">
            <div class="thumbnail-wrapper">
                <img src="<?php echo htmlspecialchars($video['thumbnail']); ?>" alt="<?php echo htmlspecialchars($video['title']); ?>">
                <i class="play-button material-icons">play_arrow</i>
            </div>
            <div class="video-title"><?php echo htmlspecialchars($video['title']); ?></div>
            <div class="video-desc"><?php echo htmlspecialchars(shortenText($video['description'])); ?></div>
            <div class="video-date"><?php echo htmlspecialchars(date('F j, Y', strtotime($video['publishedAt']))); ?></div>
        </div>
    <?php endforeach; ?>
</div>
<div class="overlay">
    <div class="overlay-content"></div>
</div>
<div class="pagination">
    <?php if (!empty($videos['prevPageToken'])): ?>
        <a href="<?php echo JRoute::_("index.php?option=com_content&view=article&id=47&pageToken=" . $videos['prevPageToken']); ?>">Previous</a>
    <?php endif; ?>
    <?php if (!empty($videos['nextPageToken'])): ?>
        <a href="<?php echo JRoute::_("index.php?option=com_content&view=article&id=47&pageToken=" . $videos['nextPageToken']); ?>">Next</a>
    <?php endif; ?>
</div>
