<?php

namespace App\DataFixtures;

use App\Entity\Video;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class VideoFixtures extends Fixture
{
        public function load(ObjectManager $manager)
        {
                $videoDatas = [
                        ["titre" => "Janemba VS Beerus", "iframe" => "4O_k5rUO8jk", "extrait" => "Un match-up très serré jusqu'à la fin !"],
                        ["titre" => "Supreme Kaï VS Droly", "iframe" => "izCsVynj0PU", "extrait" => "Un match à sens unique !"],
                        ["titre" => "OG Broly VS C-13", "iframe" => "EnTuoT9T8SI", "extrait" => "Eum provident debitis repellat ducimus expedita dicta nam. Aliquam enim facilis omnis rerum perferendis eum repellendus. "],
                        ["titre" => "Burn Gohan VS Bardock's crew", "iframe" => "smXn0Jpzk3I", "extrait" => "Eum provident debitis repellat ducimus expedita dicta nam. Aliquam enim facilis omnis rerum perferendis eum repellendus. "],
                        ["titre" => "C-21 VS Gogeta xeno", "iframe" => "j6_bIFsfWcM", "extrait" => "Eum provident debitis repellat ducimus expedita dicta nam. Aliquam enim facilis omnis rerum perferendis eum repellendus. "],
                        ["titre" => "Perfect Cell VS C-16", "iframe" => 'RHz-zNThhlI', "extrait" => "Eum provident debitis repellat ducimus expedita dicta nam. Aliquam enim facilis omnis rerum perferendis eum repellendus. "],
                ];

                foreach ($videoDatas as $videoData) {
                        $video = new Video();
                        $video->setTitre($videoData['titre'])->setIframe($videoData['iframe'])->setExtrait($videoData['extrait']);
                        $manager->persist($video);
                }

                $manager->flush();
        }
}
