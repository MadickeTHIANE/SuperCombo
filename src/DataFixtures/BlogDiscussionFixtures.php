<?php

namespace App\DataFixtures;

use App\Entity\BlogDiscussion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlogDiscussionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $discussionInfos = [
            ["id_billet" => 1, "auteur" => 'rorchar62', "contenu" => 'Ca nous fait une belle jambe ! Merci pour l\'info ! Et sinon comment est votre blanquette ?'],
            ["id_billet" => 1, "auteur" => 'hater77', "contenu" => 'Je m\'en tape les escalopes avec des claquettes'],
            ["id_billet" => 1, "auteur" => 'louis92', "contenu" => 'Génial ! J\'attend ça depuis que je suis né !'],
            ["id_billet" => 2, "auteur" => 'YannickGwada', "contenu" => 'Alors moi dans ces cas là j\'ai une super solution : un gros pétard ! Derrière l\'inspiration vient toute seule !'],
            ["id_billet" => 2, "auteur" => 'Abdel', "contenu" => 'Bonjour ! Alors moi je préconise une grosse sieste ! Rien ne vaut le repos'],
            ["id_billet" => 2, "auteur" => 'Anyssa94', "contenu" => 'Bonjour ! J\'aime bien jouer à League of Legends personnellement. Ca me permet de me changer les idées et derrière je suis plus inspirée'],
            ["id_billet" => 3, "auteur" => 'Juju59', "contenu" => 'Bonjour ! Je te plains, ça ne doit pas être facile pour toi :('],
            ["id_billet" => 3, "auteur" => 'rigide77', "contenu" => 'Bonjour ma belle. Holala ma pauvre ça ne doit pas être agréable tous les jours la vie avec ton homme. Moi à ta place je le quitterai direct !'],
            ["id_billet" => 3, "auteur" => 'hater05', "contenu" => 'Non mais c\'est pas posible ça ?! Non seulement faut ramener le pain sur la table mais en plus il faut être à disposition H24 ?!?! Non mais réveillez-vous !!!'],
            ["id_billet" => 4, "auteur" => 'jamaicMan', "contenu" => 'Popopopo !!! Vas-y fait péter mec !!!'],
            ["id_billet" => 4, "auteur" => 'gandjaman', "contenu" => 'C\'est les bons bails ça mec. Vas-y envoie ton adresse j\'arrive !'],
            ["id_billet" => 4, "auteur" => 'madou', "contenu" => 'Mais mec tu fais n\'importe quoi ma parole ?! Tu penses aux autres au moins ? Tout le monde va devoir patienter que Monsieur rattrape le cour parce que Monsieur est sorti s\'acheter de la weed ?? Non mais on va où là ?!?!'],
            ["id_billet" => 3, "auteur" => 'madou', "contenu" => 'test'],
            ["id_billet" => 3, "auteur" => 'madou', "contenu" => 'test'],
            ["id_billet" => 2, "auteur" => 'madou', "contenu" => 'test'],
            ["id_billet" => 4, "auteur" => 'dcd', "contenu" => 'test'],
            ["id_billet" => 4, "auteur" => 'pupuce', "contenu" => 'retest'],
            ["id_billet" => 4, "auteur" => 'pupuce', "contenu" => 'tartiflette !!'],
            ["id_billet" => 4, "auteur" => 'nanou', "contenu" => 'cassoulet !!!'],
            ["id_billet" => 4, "auteur" => 'nanou', "contenu" => 'carbonnade !!!'],
            ["id_billet" => 4, "auteur" => 'nanou', "contenu" => 'on s\'en bat les couilles']
        ];
        foreach ($discussionInfos as $discussionInfo) {
            $blogDiscussion = new BlogDiscussion();
            $blogDiscussion->setAuteur($discussionInfo['auteur']);
            $blogDiscussion->setContenu($discussionInfo['contenu']);
            $manager->persist($blogDiscussion);
        }

        $manager->flush();
    }
}
