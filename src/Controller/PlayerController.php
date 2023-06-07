<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Player;
use App\Entity\Team;

class PlayerController extends AbstractController
{
    #[Route('/player', name: 'player_index', methods:'GET')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        $players = $doctrine
            ->getRepository(Player::class)
            ->findAll();
  
        $data = [];
  
        foreach ($players as $player) {
            $data[] = [
                'id' => $player->getId(),
                'name' => $player->getName(),
                'surname' => $player->getSurname(),
                'country' => $player->getCountry(),
                'age' => $player->getAge(),
                'value' => $player->getValue(),
                'team_id' => $player->getTeam() ? $player->getTeam()->getId() : null,
            ];
        }
  
        return $this->json($data);
    }

    #[Route('/player', name: 'player_new', methods:'POST')]
    public function new(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
  
        $player = new Player();
        $player->setName($request->request->get('name'));
        $player->setSurname($request->request->get('surname'));
        $player->setCountry($request->request->get('country'));
        $player->setAge($request->request->get('age'));
        $player->setValue($request->request->get('value'));
  
        if ($request->request->has('team_id')) {
            $team = $entityManager->getRepository(Team::class)->find($request->request->get('team_id'));
            if (!$team) {
                return $this->json('Team not found for id ' . $request->request->get('team_id'), 404);
            }
            $player->setTeam($team);
        }
  
        $entityManager->persist($player);
        $entityManager->flush();

        return $this->json('Created new player successfully with id ' . $player->getId());
    }

    #[Route('/player/{id}', name: 'player_show', methods:'GET')]
    public function show(int $id, PersistenceManagerRegistry $doctrine): Response
    {
        $player = $doctrine
            ->getRepository(Player::class)
            ->find($id);
  
        if (!$player) {
            return $this->json('No player found for id' . $id, 404);
        }
  
        $data = [
            'id' => $player->getId(),
            'name' => $player->getName(),
            'surname' => $player->getSurname(),
            'country' => $player->getCountry(),
            'age' => $player->getAge(),
            'value' => $player->getValue(),
            'team_id' => $player->getTeam() ? $player->getTeam()->getId() : null,
        ];
          
        return $this->json($data);
    }

    #[Route('/player/{id}', name: 'player_edit', methods:'PUT')]
    public function edit(Request $request, int $id, PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $player = $entityManager->getRepository(Player::class)->find($id);
  
        if (!$player) {
            return $this->json('No player found for id' . $id, 404);
        }
        
        $content = json_decode($request->getContent());
        
        if (isset($content->name)) {
            $player->setName($content->name);
        }
        if (isset($content->surname)) {
            $player->setSurname($content->surname);
        }
        if (isset($content->country)) {
            $player->setCountry($content->country);
        }
        if (isset($content->age)) {
            $player->setAge($content->age);
        }
        if (isset($content->value)) {
            $player->setValue($content->value);
        }
        if (isset($content->team_id)) {
            $team = $entityManager->getRepository(Team::class)->find($content->team_id);
            $player->setTeam($team);
        }
        
        $entityManager->flush();
  
        $data = [
            'id' => $player->getId(),
            'name' => $player->getName(),
            'surname' => $player->getSurname(),
            'country' => $player->getCountry(),
            'age' => $player->getAge(),
            'value' => $player->getValue(),
            'team_id' => $player->getTeam() ? $player->getTeam()->getId() : null,
        ];
          
        return $this->json($data);
    }

    #[Route('/player/{id}', name: 'player_delete', methods:'DELETE')]
    public function delete(int $id, PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $player = $entityManager->getRepository(Player::class)->find($id);
  
        if (!$player) {
            return $this->json('No player found for id' . $id, 404);
        }
  
        $entityManager->remove($player);
        $entityManager->flush();
  
        return $this->json('Deleted a player successfully with id ' . $id);
    }
}
