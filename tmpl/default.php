<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
Use BKWSU\Module\Youtubegrid\Site\Helper\YoutubegridHelper;

$doc = Factory::getApplication()->getDocument();
$doc->addStyleDeclaration('
    .video-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .video { position: relative; cursor: pointer; /*padding: 8px;*/ box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .video img { width: 100%; display: block; }
    .play-button { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-family: "Material Icons"; font-size: 72px; color: white; text-shadow: 0 1px 4px rgba(0,0,0,0.5); pointer-events: none; }
    /*.video-title, .video-desc, .video-date { margin-top: 8px; }*/
    .overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); z-index: 1000; display: none; }
    .overlay-content { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80%; height: 80%; }
    iframe { width: 100%; height: 100%; }
    /*.pagination { text-align: center; padding: 20px 0; }
    .pagination a { padding: 8px 16px; background: #f0f0f0; color: #333; margin: 0 4px; text-decoration: none; }
    .pagination a:hover { background: #ddd; }*/
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

?>
<div class="relative w-full h-auto p-4 lg:p-12 bg-light-cream">
    <div class="video-grid grid w-full grid-cols-1 gap-8 mt-12 md:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($videos['items'] as $video) : ?>
            <div class="video flex flex-col w-full h-auto rounded-15px virtue-shadow animation-hover" data-video-id="<?php echo htmlspecialchars($video['id']); ?>">
                <div class="relative w-full h-auto cursor-pointer">
                    <img class="rounded-t-xl" src="<?php echo htmlspecialchars($video['thumbnail']); ?>" alt="<?php echo htmlspecialchars($video['title']); ?>">
                    <!--span class="play-button material-symbols-rounded">play_arrow</span-->
                    <i aria-hidden="true" class="play-button fa fa-play"></i>
                </div>
                <div class="p-3 my-3 justify-between">
                    <h3 class="video-title text-24px lg:text-30px text-deep-maroon Montserrat-Medium"><?php echo htmlspecialchars($video['title']); ?></h3>
                    <p class="video-desc text-15px lg:text-21px text-charcoal Montserrat-Regular"><?php echo htmlspecialchars(YoutubegridHelper::shortenText($video['description'])); ?></p>
                    <p class="video-date text-sm Montserrat-LightItalic text-charcoal"><?php echo htmlspecialchars(date('F j, Y', strtotime($video['publishedAt']))); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="flex justify-center lg:justify-end">
        <div class="flex items-center justify-end mt-20 cursor-pointer Montserrat-Regular text-15px md:text-24px lg:text-30px">
            <?php if (!empty($videos['prevPageToken'])) : ?>
                <a class="flex items-center justify-center mx-2 animation-hover pagination box-shadow text-deep-maroon" href="<?php echo Route::_("index.php?option=com_content&view=article&id=47&pageToken=" . $videos['prevPageToken']); ?>"> <i aria-hidden="true" class="fa fa-chevron-left"></i> </a>
            <?php endif; ?>
            <?php if (!empty($videos['nextPageToken'])) : ?>
                <a class="flex items-center justify-center mx-2 animation-hover pagination box-shadow text-deep-maroon" href="<?php echo Route::_("index.php?option=com_content&view=article&id=47&pageToken=" . $videos['nextPageToken']); ?>"> <i aria-hidden="true" class="fa fa-chevron-right"></i> </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="overlay">
    <div class="overlay-content"></div>
</div>