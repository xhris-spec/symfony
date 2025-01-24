<?php

declare(strict_types=1);

namespace App\Controller;

use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Sonata\Media;
use App\Entity\Champion;

class MainController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'default')]
    public function default(): Response
    {
        $champions = $this->entityManager->getRepository(Champion::class)->findBy([
            'active' => true,
        ]);

        $championsData = [];

        foreach ($champions as $champion) {
            $championsData[] = [
                'splashart' => $champion->getSplashart(),
                'name' => $champion->getName(),
                'slug' => $champion->getSlug(),
            ];
        }

        return $this->render('home.html.twig', [
            'champions' => $championsData,
        ]);
    }

    #[Route('/{slug}', name: 'link')]
    #[ParamConverter('champion', class: Champion::class, options: ['mapping' => ['slug' => 'slug']])]
    public function champion(Champion $champion): Response
    {
        if (!$champion->isActive()) {
            throw new NotFoundHttpException();
        }

        return $this->render('champion.html.twig', [
            'champion' => $champion,
        ]);
    }

    #[Route('/slugify/{text}', name: 'slugify')]
    public function slugifyAction(string $text): Response
    {
        // Slugify
        $slugify = new Slugify();

        return new JsonResponse(['slug' => $text === '' ? '' : $slugify->slugify($text)]);
    }

    #[Route(
        '/image-preview-content/{element_id}/{id}/{image_size}',
        name: 'image-preview',
        defaults: ['id' => '', 'image_size' => 'medium']
    )]
    #[ParamConverter('media', class: Media::class, options: ['id' => 'id'])]
    public function imagePreviewContent(string $element_id, string $image_size, Request $request): Response
    {
        /** @var Media|null */
        $media = $request->attributes->get('media');

        $html = $this->renderView('admin/image-preview-content.html.twig', [
            'image' => $media,
            'image_label' => null,
            'image_size' => $image_size,
            'display_name' => true,
            'id' => $element_id,
        ]);

        return new JsonResponse(['html' => $html]);
    }
}
