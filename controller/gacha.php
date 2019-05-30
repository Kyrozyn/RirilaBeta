<?php

namespace Controller;

use Exception;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class gacha
{
    public static function gachaSatu()
    {
        $roll = null;
        try {
            $roll = random_int(1, 100);
        } catch (Exception $e) {
            file_put_contents('php://stderr', 'Exception while random: ' . $e->getMessage());
        }
        if ($roll == 100) {
            $reply = new TextMessageBuilder('5* Servant');
        } elseif ($roll <= 99 && $roll > 96) {
            $reply = new TextMessageBuilder('5* CE');
        } elseif ($roll <= 96 && $roll > 92) {
            $reply = new TextMessageBuilder('4* Servant');
        } elseif ($roll <= 92 && $roll > 84) {
            $reply = new TextMessageBuilder('4* CE');
        } elseif ($roll <= 84 && $roll > 44) {
            $reply = new TextMessageBuilder('3* Servant');
        } else {
            $reply = new TextMessageBuilder('3* CE');
        }
        return $reply;
    }

    public static function gachaBanyak()
    {
        re :
        $balas = null;
        $ssr = 0;
        $sr = 0;
        $r = 0;
        for ($a = 0; $a < 10; $a++) {
            $roll = null;
            try {
                $roll = random_int(1, 100);
            } catch (Exception $e) {
                file_put_contents('php://stderr', 'Exception while random: ' . $e->getMessage());
            }
            if ($roll == 100) {
                $balas = $balas . '5* Servant';
                $ssr = $ssr + 1;
            } elseif ($roll <= 99 && $roll > 96) {
                $balas = $balas . '5* CE';
                $ssr = $ssr + 1;
            } elseif ($roll <= 96 && $roll > 92) {
                $balas = $balas . '4* Servant';
                $sr = $sr + 1;
            } elseif ($roll <= 92 && $roll > 84) {
                $balas = $balas . '4* CE';
                $sr = $sr + 1;
            } elseif ($roll <= 84 && $roll > 44) {
                $balas = $balas . '3* Servant';
                $r = $r + 1;
            } else {
                $balas = $balas . '3* CE';
                $r = $r + 1;
            }
            if ($a != 9) {
                $balas = $balas . "\n";
            }
        }
        if ($ssr == 10 or $sr == 10 or $r == 10) {
            goto re;
        } else {
            $text1 = new TextMessageBuilder($balas);
            if ($sr < 2 and $ssr < 1) {
                $rand = ['Ampas sekali hidup anda ^_^', 'Perbanyak tobat agar luck anda meningkat ^_^'];
                $tx = $rand[array_rand($rand)];
            } else {
                $rand = ['Jangan lupa sikat gigi sebelum gacha ^_^', 'Jangan lupa puasa sebelum gacha ^_^', 'Jangan lupa makan sebelum gacha ^_^', 'Jangan lupa minum sebelum gacha ^_^'];
                $tx = $rand[array_rand($rand)];
            }
            $text2 = new TextMessageBuilder('SSR = ' . $ssr . "\nSR = " . $sr . "\nR =" . $r . "\n" . $tx);
            $reply = new MultiMessageBuilder();
            $reply->add($text1);
            $reply->add($text2);
            return $reply;
        }
    }
}