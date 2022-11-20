<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;

#[Route('/program/', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', ['programs' => $programs]);
    }

    #[Route('{id<\d+>}', methods: ['GET'], name: 'show')]
    public function show(int $id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findOneById($id);
        if (!$program) {
            throw $this->createNotFoundException(
                'Pas de série avec l\'id : ' . $id
            );
        }
        $seasons = $program->getSeasons();
        return $this->render('program/show.html.twig', ['program' => $program, 'seasons' => $seasons]);
    }

    #[Route('{programId<\d+>}/season/{seasonId<\d+>}', methods: ['GET'], name: 'season_show')]
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findOneById($programId);
        if (!$program) {
            throw $this->createNotFoundException(
                'Pas de série avec l\'id : ' . $programId
            );
        }

        $seasons = $program->getSeasons();
        $comparison = new Comparison('id', '=', $seasonId);
        $criteria = new Criteria();
        $criteria->where($comparison);
        $season = $seasons->matching($criteria)->current();
        if (!$season) {
            throw $this->createNotFoundException(
                'La série n\'a pas de saison avec l\'id : ' . $seasonId
            );
        }

        $episodes = $season->getEpisodes();
        return $this->render('program/season_show.html.twig', ['program' => $program, 'season' => $season, 'episodes' => $episodes]);
    }
}
