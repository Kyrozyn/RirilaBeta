<?php


namespace Controller;


use Jikan\Jikan;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

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
        $halaman = $page;
        $batas = 4;
        if (empty($halaman)) {
            $posisi = 0;
            $halaman = 1;
        } else {
            $posisi = ($halaman - 1) * $batas;
        }
        $result = $this->jikan->AnimeSearch($title)->getResults();
//        $Carousel = new ImageCarouselTemplateBuilder([
//            new ImageCarouselColumnTemplateBuilder($result[$posisi]->getImageUrl(), new UriTemplateActionBuilder($result[$posisi]->getTitle(), $result[$posisi]->getUrl())),
//            new ImageCarouselColumnTemplateBuilder($result[$posisi + 1]->getImageUrl(), new UriTemplateActionBuilder($result[$posisi + 1]->getTitle(), $result[$posisi + 1]->getUrl())),
//            new ImageCarouselColumnTemplateBuilder($result[$posisi + 2]->getImageUrl(), new UriTemplateActionBuilder($result[$posisi]->getTitle(), $result[$posisi]->getUrl())),
//            new ImageCarouselColumnTemplateBuilder($result[$posisi + 3]->getImageUrl(), new UriTemplateActionBuilder($result[$posisi]->getTitle(), $result[$posisi]->getUrl())),
//            new ImageCarouselColumnTemplateBuilder("https://cdn.myanimelist.net/img/sp/icon/apple-touch-icon-256.png", new MessageTemplateActionBuilder("Next", "nim"))
//        ]);
//        $reply = new TemplateMessageBuilder("Anime", $Carousel);
        $reply = new TextMessageBuilder(print_r($result, 1));
        return $reply;
    }
}