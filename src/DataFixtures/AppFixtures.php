<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Champion;
use App\Entity\Hability;
use App\Entity\Sonata\Media;

class AppFixtures extends Fixture
{
    private const ES = 'es';
    private const EN = 'en';

    private function downloadAndCreateMedia(mixed $id, mixed $url, ObjectManager $manager, string $type = 'image', string $extra = null): Media
    {
        $extension = $type === 'video' ? '.mp4' : '.jpg';
        $extraPath = isset($extra) ? '_' . str_replace([' ', ':', '/'], ['_', '_', '_'], $extra) . '_hab' : '';
        $path = dirname(__FILE__) . '/../../public/upload/media/' . $id . $extraPath . $extension;
        if (is_string($url)) {
            file_put_contents($path, file_get_contents($url));
        }

        $media = new Media();
        $media->setBinaryContent($path);
        $media->setContext('default');
        $providerName = 'sonata.media.provider.' . ($type === 'video' ? 'file' : 'image');
        $media->setProviderName($providerName);
        $manager->persist($media);

        return $media;
    }

    public function load(ObjectManager $manager): void
    {
        $data = file_get_contents('https://ddragon.leagueoflegends.com/cdn/13.20.1/data/es_ES/champion.json');

        if ($data === false) {
            throw new \RuntimeException('Error al obtener los datos del campeón en español.');
        }

        $json = json_decode($data, true);
        if (!is_array($json) || !isset($json['data'])) {
            throw new \RuntimeException('Error al decodificar los datos del campeón en español.');
        }

        $jsonData = array_slice($json['data'], 0, 5);
        $slugify = new Slugify();

        foreach ($json['data'] as $id => $chamData) {
            $dataChampEn = file_get_contents("https://ddragon.leagueoflegends.com/cdn/13.20.1/data/en_US/champion/$id.json");
            $dataChampEs = file_get_contents("https://ddragon.leagueoflegends.com/cdn/13.20.1/data/es_ES/champion/$id.json");

            if ($dataChampEn === false || $dataChampEs === false) {
                throw new \RuntimeException("Error al obtener los datos del campeón {$id}.");
            }

            $jsonChampEn = json_decode($dataChampEn);
            $jsonChampEs = json_decode($dataChampEs);

            if (!is_object($jsonChampEn) || !property_exists($jsonChampEn, 'data') || !is_object($jsonChampEs) || !property_exists($jsonChampEs, 'data')) {
                throw new \RuntimeException("Error al decodificar los datos del campeón {$id}.");
            }

            $champDataEn = $jsonChampEn->data->$id;
            $champDataEs = $jsonChampEs->data->$id;

            $imageUrl = 'https://ddragon.leagueoflegends.com/cdn/img/champion/loading/' . $id . '_0.jpg';

            $mediaSplashart = $this->downloadAndCreateMedia($id, $imageUrl, $manager, 'image');

            $champEntity = new Champion();
            $champEntity->setName($champDataEs->name);
            dump($champDataEs->name);
            $champEntity->setSlug($slugify->slugify($champDataEs->name));
            $champEntity->setActive(true);
            $champEntity->setSplashart($mediaSplashart);
            $champEntity->translate(self::ES)->setDescription($champDataEs->lore);
            $champEntity->translate(self::EN)->setDescription($champDataEn->lore);
            $champEntity->setRol($champDataEs->tags);
            $champEntity->mergeNewTranslations();

            $manager->persist($champEntity);

            $videoUrls = [
                'https://d28xe8vt774jo5.cloudfront.net/champion-abilities/' . str_pad($champDataEs->key, 4, '0', STR_PAD_LEFT) . '/ability_' . str_pad($champDataEs->key, 4, '0', STR_PAD_LEFT) . '_Q1.mp4',
                'https://d28xe8vt774jo5.cloudfront.net/champion-abilities/' . str_pad($champDataEs->key, 4, '0', STR_PAD_LEFT) . '/ability_' . str_pad($champDataEs->key, 4, '0', STR_PAD_LEFT) . '_W1.mp4',
                'https://d28xe8vt774jo5.cloudfront.net/champion-abilities/' . str_pad($champDataEs->key, 4, '0', STR_PAD_LEFT) . '/ability_' . str_pad($champDataEs->key, 4, '0', STR_PAD_LEFT) . '_E1.mp4',
                'https://d28xe8vt774jo5.cloudfront.net/champion-abilities/' . str_pad($champDataEs->key, 4, '0', STR_PAD_LEFT) . '/ability_' . str_pad($champDataEs->key, 4, '0', STR_PAD_LEFT) . '_R1.mp4',
            ];
            $i = 0;

            array_map(function ($spellEs, $spellEn) use ($id, $manager, $videoUrls, &$i, $champEntity) {
                $habilityEntity = new Hability();
                $habilityEntity->setChampion($champEntity);
                $habilityEntity->translate(self::ES)->setName($spellEs->name);
                $habilityEntity->translate(self::EN)->setName($spellEn->name);

                $habilityEntity->translate(self::ES)->setDescription($spellEs->description);
                $habilityEntity->translate(self::EN)->setDescription($spellEn->description);

                $habilityEntity->mergeNewTranslations();

                // image
                $imageUrlHability = 'https://ddragon.leagueoflegends.com/cdn/13.20.1/img/spell/' . $spellEn->image->full;
                $mediaHabilityImage = $this->downloadAndCreateMedia($id, $imageUrlHability, $manager, 'image', $spellEs->name);
                $habilityEntity->setImage($mediaHabilityImage);

                // video
                $mediaHabilityVideo = null;
                if (isset($videoUrls[$i])) {
                    $videoUrl = $videoUrls[$i];
                    if (@file_get_contents($videoUrl, false, null, 0, 1)) {
                        $mediaHabilityVideo = $this->downloadAndCreateMedia($id, $videoUrl, $manager, 'video', $spellEs->name);
                    }
                }
                $habilityEntity->setVideo($mediaHabilityVideo);
                ++$i;
                $manager->persist($habilityEntity);
            }, $champDataEs->spells, $champDataEn->spells);
        }

        $manager->flush();
    }
}
