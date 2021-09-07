<?php

namespace Onadrog\ImageConverterBundle\Mock\Entity\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Onadrog\ImageConverterBundle\Mock\Entity\Entity\SoloFile;

class SolofileFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 2; ++$i) {
            $soloFile = new SoloFile();
            $soloFile->setName('JPG.webp');
            $soloFile->setSlug('/uploads/media/fixtures/JPG.webp');
            $soloFile->setDimension(['height' => 150, 'width' => 150]);
            $soloFile->setAlt('A fixture image.');
            $manager->persist($soloFile);
        }
        $manager->flush();
    }
}
