<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Offer;
use App\Entity\Team;
use App\Entity\Player;

class OfferController extends AbstractController
{
    #[Route('/offer', name: 'offer_index', methods:'GET')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        $offers = $doctrine
            ->getRepository(Offer::class)
            ->findAll();
  
        $data = [];
  
        foreach ($offers as $offer) {
            $data[] = [
                'id' => $offer->getId(),
                'team_id' => $offer->getTeam()->getId(),
                'player_id' => $offer->getPlayer()->getId(),
                'value' => $offer->getValue(),
                'status' => $offer->getStatus(),
            ];
        }
  
        return $this->json($data);
    }

    #[Route('/offer', name: 'offer_new', methods:'POST')]
    public function new(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
  
        $team = $entityManager->getRepository(Team::class)->find($request->request->get('team_id'));
        $player = $entityManager->getRepository(Player::class)->find($request->request->get('player_id'));

        $offer = new Offer();
        $offer->setTeam($team);
        $offer->setPlayer($player);
        $offer->setValue($request->request->get('value'));
        $offer->setStatus($request->request->get('status'));
  
        $entityManager->persist($offer);
        $entityManager->flush();

        return $this->json('Created new offer successfully with id ' . $offer->getId());
    }

    #[Route('/offer', name: 'offer_show', methods:'POST')]
    public function show(int $id, PersistenceManagerRegistry $doctrine): Response
    {
        $offer = $doctrine
            ->getRepository(Offer::class)
            ->find($id);
  
        if (!$offer) {
            return $this->json('No offer found for id' . $id, 404);
        }
  
        $data = [
            'id' => $offer->getId(),
            'team_id' => $offer->getTeam()->getId(),
            'player_id' => $offer->getPlayer()->getId(),
            'value' => $offer->getValue(),
            'status' => $offer->getStatus(),
        ];
          
        return $this->json($data);
    }

    #[Route('/offer/{id}', name: 'offer_edit', methods:'PUT')]
    public function edit(Request $request, int $id, PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $offer = $entityManager->getRepository(Offer::class)->find($id);
  
        if (!$offer) {
            return $this->json('No offer found for id' . $id, 404);
        }
        
        $content = json_decode($request->getContent());
        
        if (isset($content->team_id)) {
            $team = $entityManager->getRepository(Team::class)->find($content->team_id);
            $offer->setTeam($team);
        }
        if (isset($content->player_id)) {
            $player = $entityManager->getRepository(Player::class)->find($content->player_id);
            $offer->setPlayer($player);
        }
        if (isset($content->value)) {
            $offer->setValue($content->value);
        }
        if (isset($content->status)) {
            $offer->setStatus($content->status);
        }

        $entityManager->flush();
  
        $data = [
            'id' => $offer->getId(),
            'team_id' => $offer->getTeam()->getId(),
            'player_id' => $offer->getPlayer()->getId(),
            'value' => $offer->getValue(),
            'status' => $offer->getStatus(),
        ];
          
        return $this->json($data);
    }

    #[Route('/offer/{id}', name: 'offer_delete', methods:'DELETE')]
    public function delete(int $id, PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $offer = $entityManager->getRepository(Offer::class)->find($id);
  
        if (!$offer) {
            return $this->json('No offer found for id' . $id, 404);
        }
  
        $entityManager->remove($offer);
        $entityManager->flush();
  
        return $this->json('Deleted an offer successfully with id ' . $id);
    }
}
