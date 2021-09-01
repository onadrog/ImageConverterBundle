<?php

namespace Onadrog\ImageConverterBundle\Mock\Entity\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Onadrog\ImageConverterBundle\Mock\Entity\Entity\SoloFile;

class SolofileFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $soloFile = new SoloFile();
        $soloFile->setName('JPEG.fixtures');
        $soloFile->setSlug('/public/uploads/fixtures/JPEG.jpg');
        $soloFile->setDimension(['height' => 150, 'width' => 150]);
        $soloFile->setAlt('A fixture image.');
        $manager->persist($soloFile);
        $manager->flush();
    }
}
