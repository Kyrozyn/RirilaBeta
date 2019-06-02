<?php

namespace Controller;

use Jikan\Exception\BadResponseException;
use Jikan\Exception\ParserException;
use Jikan\Jikan;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
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
        if ($page > 25) {
            $reply = new TextMessageBuilder("Maaf, max hanya sampai 25 halaman uwu");
        } else {
            $result = null;
            $reply = null;
            $halaman = $page;
            $nextpage = $page + 1;
            $batas = 4;
            if (empty($halaman)) {
                $posisi = 0;
            } else {
                $posisi = ($halaman - 1) * $batas;
            }

            try {
                $result = $this->jikan->AnimeSearch($title)->getResults();
            } catch (BadResponseException $e) {
                file_put_contents('php://stderr', 'Exception : ' . $e->getMessage());
            } catch (ParserException $e) {
                file_put_contents('php://stderr', 'Exception : ' . $e->getMessage());
            }
            ///TODO CHANGE IT TO ANOTHER THING...
//        $Carousel = new ImageCarouselTemplateBuilder([
//            new ImageCarouselColumnTemplateBuilder($result[$posisi]->getImageUrl(), new UriTemplateActionBuilder(substr($result[$posisi]->getTitle(), 0, 11), $result[$posisi]->getUrl())),
//            new ImageCarouselColumnTemplateBuilder($result[$posisi + 1]->getImageUrl(), new UriTemplateActionBuilder(substr($result[$posisi + 1]->getTitle(), 0, 11), $result[$posisi + 1]->getUrl())),
//            new ImageCarouselColumnTemplateBuilder($result[$posisi + 2]->getImageUrl(), new UriTemplateActionBuilder(substr($result[$posisi + 2]->getTitle(), 0, 11), $result[$posisi + 2]->getUrl())),
//            new ImageCarouselColumnTemplateBuilder($result[$posisi + 3]->getImageUrl(), new UriTemplateActionBuilder(substr($result[$posisi + 3]->getTitle(), 0, 11), $result[$posisi + 3]->getUrl())),
//            new ImageCarouselColumnTemplateBuilder('https://cdn.myanimelist.net/img/sp/icon/apple-touch-icon-256.png', new MessageTemplateActionBuilder('Next', 'nim*'.$title.'*'.$nextpage)),
//        ]);
            $care = [];
            for ($i = 0; $i <= 3; $i++) {
                $imageUrl = substr($result[$posisi + $i]->getImageUrl(), 0, 999);
                $titlee = substr($result[$posisi + $i]->getTitle(), 0, 39);
                $score = "☆" . $result[$posisi + $i]->getScore() . "\n" . substr($result[$posisi + $i]->getSynopsis(), 0, 49);
                $link = substr($result[$posisi + $i]->getUrl(), 0, 999);
                $action_builder = [new UriTemplateActionBuilder("More Info", $link)];
                $care[$i] = new CarouselColumnTemplateBuilder($titlee, $score, $imageUrl, $action_builder);
            }
            $care[4] = new CarouselColumnTemplateBuilder("~~~", "Next Result", 'https://cdn.myanimelist.net/img/sp/icon/apple-touch-icon-256.png', [new MessageTemplateActionBuilder('Next', 'nim*' . $title . '*' . $nextpage)]);
            $carousel2 = new CarouselTemplateBuilder([$care[0], $care[1], $care[2], $care[3], $care[4]]);

            $reply = new TemplateMessageBuilder('Anime', $carousel2);
        }
        return $reply;
    }

    public function searchChara($name, $page = 1)
    {
        if ($page > 25) {
            $reply = new TextMessageBuilder("Maaf, max hanya sampai 25 halaman uwu");
        } else {
            $result = null;
            $reply = null;
            $halaman = $page;
            $nextpage = $page + 1;
            $batas = 4;
            if (empty($halaman)) {
                $posisi = 0;
            } else {
                $posisi = ($halaman - 1) * $batas;
            }

            try {
                $result = $this->jikan->CharacterSearch($name)->getResults();
            } catch (BadResponseException $e) {
                file_put_contents('php://stderr', 'Exception : ' . $e->getMessage());
            } catch (ParserException $e) {
                file_put_contents('php://stderr', 'Exception : ' . $e->getMessage());
            }
            ///TODO CHANGE IT TO ANOTHER THING...
//        $Carousel = new ImageCarouselTemplateBuilder([
//            new ImageCarouselColumnTemplateBuilder($result[$posisi]->getImageUrl(), new UriTemplateActionBuilder(substr($result[$posisi]->getTitle(), 0, 11), $result[$posisi]->getUrl())),
//            new ImageCarouselColumnTemplateBuilder($result[$posisi + 1]->getImageUrl(), new UriTemplateActionBuilder(substr($result[$posisi + 1]->getTitle(), 0, 11), $result[$posisi + 1]->getUrl())),
//            new ImageCarouselColumnTemplateBuilder($result[$posisi + 2]->getImageUrl(), new UriTemplateActionBuilder(substr($result[$posisi + 2]->getTitle(), 0, 11), $result[$posisi + 2]->getUrl())),
//            new ImageCarouselColumnTemplateBuilder($result[$posisi + 3]->getImageUrl(), new UriTemplateActionBuilder(substr($result[$posisi + 3]->getTitle(), 0, 11), $result[$posisi + 3]->getUrl())),
//            new ImageCarouselColumnTemplateBuilder('https://cdn.myanimelist.net/img/sp/icon/apple-touch-icon-256.png', new MessageTemplateActionBuilder('Next', 'nim*'.$title.'*'.$nextpage)),
//        ]);
            $care = [];
            for ($i = 0; $i <= 3; $i++) {
                $imageUrl = substr($result[$posisi + $i]->getImageUrl(), 0, 999);
                $namee = substr($result[$posisi + $i]->getName(), 0, 11);
                $link = substr($result[$posisi + $i]->getUrl(), 0, 999);
                $action_builder = new UriTemplateActionBuilder($namee, $link);
                $care[$i] = new ImageCarouselColumnTemplateBuilder($imageUrl, $action_builder);
            }
            $care[4] = new ImageCarouselColumnTemplateBuilder('https://cdn.myanimelist.net/img/sp/icon/apple-touch-icon-256.png', new MessageTemplateActionBuilder('Next', 'chara*' . $name . '*' . $nextpage));
            $carousel2 = new ImageCarouselTemplateBuilder([$care[0], $care[1], $care[2], $care[3], $care[4]]);

            $reply = new TemplateMessageBuilder('Anime', $carousel2);
        }
        return $reply;
    }
}
