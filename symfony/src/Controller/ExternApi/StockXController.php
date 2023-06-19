<?php

namespace App\Controller\ExternApi;

use App\Entity\Sneaker;
use App\Repository\SneakerRepository;
use App\Service\Hydrator\SneakerHydrator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/extern/api', name: 'stock_x_api_')]
class StockXController extends AbstractController
{
    #[Route('/add-sneaker', name: 'add_sneaker', methods: ['POST'])]
    public function addSneaker(
        Request $request,
        SneakerHydrator $sneakerHydrator,
        EntityManagerInterface $manager,
        SneakerRepository $sneakerRepository
    ): JsonResponse {
        $json = $request->getContent();

        $sneakerDto = $sneakerHydrator->hydrateSneakerDTO($json);

        if ($sneakerDto === null) {
            return new JsonResponse('wrong datas', 400);
        }

        $sneaker = $sneakerRepository->findOneBySku($sneakerDto->sku);

        if ($sneaker === null) {
            $sneaker = new Sneaker();
        }

        try {
            $sneakerHydrator->hydrateSneaker($sneaker, $sneakerDto);
            $manager->persist($sneaker);
            $manager->flush();
        } catch (Throwable) {
            return new JsonResponse('ko', 400);
        }

        return new JsonResponse('ok');
    }
}