<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Team;

class TeamController extends AbstractController
{
    #[Route('/team', name: 'team_index', methods:'GET')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        $teams = $doctrine
            ->getRepository(Team::class)
            ->findAll();
  
        $data = [];
  
        foreach ($teams as $team) {
            $data[] = [
                'id' => $team->getId(),
                'logo' => $team->getLogo(),
                'name' => $team->getName(),
                'money' => $team->getMoney(),
                'country' => $team->getCountry(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/team', name: 'team_new', methods:'POST')]
    public function new(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $team = new Team();
        $team->setLogo($request->request->get('logo'));
        $team->setName($request->request->get('name'));
        $team->setMoney((int) $request->request->get('money'));
        $team->setCountry($request->request->get('country'));

        $entityManager->persist($team);
        $entityManager->flush();

        return $this->json('Created new team successfully with id ' . $team->getId());
    }

    #[Route('/team', name: 'team_edit', methods:'POST')]
    public function show(int $id, PersistenceManagerRegistry $doctrine): Response
    {
        $team = $doctrine
            ->getRepository(Team::class)
            ->find($id);

        if (!$team) {
            return $this->json('No team found for id ' . $id, 404);
        }

        $data = [
            'id' => $team->getId(),
            'logo' => $team->getLogo(),
            'name' => $team->getName(),
            'money' => $team->getMoney(),
            'country' => $team->getCountry(),
        ];

        return $this->json($data);
    }

    #[Route('/team/{id}', name: 'team_edit', methods:'PUT')]
    public function edit(Request $request, int $id, PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $team = $entityManager->getRepository(Team::class)->find($id);
  
        if (!$team) {
            return $this->json('No team found for id ' . $id, 404);
        }
        
        $content = json_decode($request->getContent());
        
        if (isset($content->logo)) {
            $team->setLogo($content->logo);
        }

        if (isset($content->name)) {
            $team->setName($content->name);
        }
        if (isset($content->money)) {
            $team->setMoney((int) $content->money);
        }

        if (isset($content->country)) {
            $team->setCountry($content->country);
        }
        $entityManager->flush();
  
        $data = [
            'id' => $team->getId(),
            'logo' => $team->getLogo(),
            'name' => $team->getName(),
            'money' => $team->getMoney(),
            'country' => $team->getCountry(),
        ];

        return $this->json($data);
    }

    #[Route('/team/{id}', name: 'team_delete', methods:'DELETE')]
    public function delete(int $id, PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $team = $entityManager->getRepository(Team::class)->find($id);
  
        if (!$team) {
            return $this->json('No team found for id ' . $id, 404);
        }
  
        $entityManager->remove($team);
        $entityManager->flush();
  
        return $this->json('Deleted a team successfully with id ' . $id);
    }
}
