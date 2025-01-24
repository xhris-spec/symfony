<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Champion;

class ApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MediaProviderInterface $mediaProvider,
    ) {
    }

    private function getHost(): string
    {
        return (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]";
    }

    #[Route('/api/{locale}/champions', name: 'api_champion')]
    public function champions(string $locale, Request $request): JsonResponse
    {
        $championsData = [];
        $host = $this->getHost();

        $champions = $this->entityManager->getRepository(Champion::class)->findBy([
            'active' => true,
        ]);

        foreach ($champions as $champion) {
            $championsData[] = [
                'splashart' => $champion->getSplashart() == null ? '' : $host . $this->mediaProvider->generatePublicUrl($champion->getSplashart(), 'reference'),
                'name' => $champion->getName(),
                'slug' => $champion->getSlug(),
                'description' => $champion->getDescription(),
                'role' => $champion->getRol(),
            ];
        }

        return $this->json($championsData);
    }

    #[Route('/api/{locale}/{slug}', name: 'api_champion_slug')]
    #[ParamConverter('champion', class: Champion::class, options: ['mapping' => ['slug' => 'slug']])]
    public function getChampionBySlug(string $locale, Champion $champion): JsonResponse
    {
        $host = $this->getHost();

        if (!$champion->isActive()) {
            throw new NotFoundHttpException();
        }
        $abilities = [];

        foreach ($champion->getHabilities() as $ability) {
            $imageUrl = $ability->getImage() == null ? '' : $host . $this->mediaProvider->generatePublicUrl($ability->getImage(), 'reference');
            $videoUrl = $ability->getVideo() == null ? '' : $host . $this->mediaProvider->generatePublicUrl($ability->getVideo(), 'reference');

            $abilities[] = [
                'name' => $ability->getName(),
                'description' => $ability->getDescription(),
                'image' => $imageUrl,
                'video' => $videoUrl,
            ];
        }

        $data = [
            'name' => $champion->getName(),
            'description' => $champion->getDescription(),
            'abilities' => $abilities,
            'splashart' => $champion->getSplashart() == null ? '' :
                            $host .
                            $this->mediaProvider->generatePublicUrl($champion->getSplashart(), 'reference'),
        ];

        return $this->json($data);
    }

    #[Route('/api/translations', name: 'translations')]
    public function getTranslations(TranslatorInterface $translator): JsonResponse
    {
        $translations = [
            'champion' => [
                'description' => $translator->trans('champion.description'),
                'position' => $translator->trans('champion.position'),
            ],
            'button' => [
                'hide_video' => $translator->trans('button.hide_video'),
                'show_video' => $translator->trans('button.show_video'),
                'details' => $translator->trans('button.details'),
            ],
            'error' => [
                'not_found' => $translator->trans('error.not_found'),
                'exception' => $translator->trans('error.exception'),
            ],
        ];

        return new JsonResponse($translations);
    }
}
