<?php

namespace App\DataFixtures;

use App\Entity\BlogBillet;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BlogBilletFixtures extends Fixture
{
        public function load(ObjectManager $manager)
        {

                $billetInfos = [
                        ["auteur" => 'nanou59', "titre" => 'Le PHP à la conquête du monde !', "contenu" => 'C\'est officiel, l\'éléPHPant a annoncé à la radio hier soir \"J\'ai l\'intention de conquérir le monde !\")'],
                        ["auteur" => 'madou', "titre" => 'Syndrome de la page blanche', "contenu" => 'Bonjour à tous ! Je vous explique mon soucis : je ne sais pas quoi écrire ! Au secours !'],
                        ["auteur" => 'pupuce', "titre" => 'mon bibi travail beaucoup trop !', "contenu" => 'Bonjour tout le monde ! Je ne sais pas quoi faire : mon mari ne cesse de travailler même le week-end ! Je ne sais pas quoi faire pour attirer son attention !'],
                        ["auteur" => 'YannickGwada', "titre" => 'Je comprend mais j\'ai pas compris', "contenu" => 'Salutations Honorables ! Je suis en train de me rouler un spliff là et je me disais, concernant le cours de PHP, parce que j\'ai du partir faire une course là. Du coup j\'ai pas tout suivi : est-ce que c\'est possible de recommencer depuis le début ?']
                ];
                foreach ($billetInfos as $billetInfo) {
                        $blogBillet = new BlogBillet();
                        $blogBillet->setAuteur($billetInfo['auteur']);
                        $blogBillet->setTitre($billetInfo['titre']);
                        $blogBillet->setContenu($billetInfo['contenu']);
                        $manager->persist($blogBillet);
                }

                $manager->flush();
        }
}
