<?php

namespace Onadrog\ImageConverterBundle\Mock\Entity\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Onadrog\ImageConverterBundle\Mock\Entity\Entity\Media;
use Onadrog\ImageConverterBundle\Mock\Entity\Entity\SoloFile;

class SolofileFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 2; ++$i) {
            $soloFile = new SoloFile();
            $soloFile->setName('JPG.webp');
            $soloFile->setSlug('/tests/uploads/media/JPG.webp');
            $soloFile->setDimension(['height' => 150, 'width' => 150]);
            $soloFile->setAlt('A fixture image.');
            $soloFile->setMimeTypes(['webp']);
            $manager->persist($soloFile);
        }

        $media = new Media();
        $media->setName('fixture-0.webp');
        $media->setSlug('/img/fixture-0');
        $media->setDimension(['height' => 150, 'width' => 150]);
        $media->setAlt('A fixture image.');
        $media->setMimeTypes(['webp']);
        $manager->persist($media);

        $manager->flush();
    }
}
