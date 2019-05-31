<?php


namespace Controller;


use Exception;
use Jikan\Jikan;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;

class anime
{
    private $jikan;

    /**
     * anime constructor.
     */
    public function __construct()
    {
        $this->jikan = new Jikan();
    }

    public function searchAnime($title, $page = 1)
    {
        $reply = null;
        $halaman = $page;
        $batas = 4;
        if (empty($halaman)) {
            $posisi = 0;
            $halaman = 1;
        } else {
            $posisi = ($halaman - 1) * $batas;
        }
        $result = $this->jikan->AnimeSearch($title)->getResults();
        try {
            $Carousel = new ImageCarouselTemplateBuilder([
                new ImageCarouselColumnTemplateBuilder($result[$posisi]->getImageUrl(), new UriTemplateActionBuilder(substr($result[$posisi]->getTitle(),0,11), $result[$posisi]->getUrl())),
                new ImageCarouselColumnTemplateBuilder($result[$posisi + 1]->getImageUrl(), new UriTemplateActionBuilder(substr($result[$posisi + 1]->getTitle(),0,11), $result[$posisi + 1]->getUrl())),
                new ImageCarouselColumnTemplateBuilder($result[$posisi + 2]->getImageUrl(), new UriTemplateActionBuilder(substr($result[$posisi+2]->getTitle(),0,11), $result[$posisi+2]->getUrl())),
                new ImageCarouselColumnTemplateBuilder($result[$posisi + 3]->getImageUrl(), new UriTemplateActionBuilder(substr($result[$posisi+3]->getTitle(),0,11), $result[$posisi+3]->getUrl())),
                new ImageCarouselColumnTemplateBuilder("https://cdn.myanimelist.net/img/sp/icon/apple-touch-icon-256.png", new MessageTemplateActionBuilder("Next", "nim"))
            ]);
            $reply = new TemplateMessageBuilder("Anime", $Carousel);
        } catch (Exception $e) {
            file_put_contents('php://stderr', 'Exception : ' . $e->getMessage());
        }
        return $reply;
    }
}