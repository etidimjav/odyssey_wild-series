<?php

namespace App\Controller;

use App\Entity\Program;
use App\Repository\CategoryRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("", name="index")
    */
    public function index() :Response
    {
        $programs = $this->getDoctrine()
          ->getRepository(Program::class)
          ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
            'No program found in program\'s table.'
            );
        }

        return $this->render(
            'wild/index.html.twig',
            ['programs' => $programs]
        );
    }

    /**
    * Getting a program with a formatted slug for title
    *
    * @param string $slug The slugger
    * @Route("/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="show")
    * @return Response
    */
    public function show(?string $slug):Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
        ]);
    }

    /**
    * Getting programs from one category
    *
    * @param string $categoryName The category
    * @Route("/category/{categoryName}", name="show_category")
    * @return Response
    */
    public function showByCategory(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository):Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No categoryName has been sent to find program\'s.');
        }
        
        $category = $categoryRepository
            ->findOneBy(['name' => mb_strtolower($categoryName)]);
        $programs = $programRepository
            ->findBy(['category' => $category], ['id' => 'DESC'], 3);

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program for '.$categoryName.' name, found in program\'s table.'
            );
        }

        return $this->render('wild/category.html.twig', [
            'programs' => $programs,
            'categoryName' => $categoryName
        ]);
    }

    /**
    * Getting seasons from one program
    *
    * @param string $programName The program
    * @Route("/{programName}", name="show_program")
    * @return Response
    */
    public function showByProgram(string $programName, ProgramRepository $programRepository, SeasonRepository $seasonRepository):Response
    {
        if (!$programName) {
            throw $this
                ->createNotFoundException('No programName has been sent to find season\'s.');
        }
        
        $programTitle = ucwords(str_replace('-', ' ', $programName));
        $program = $programRepository
            ->findOneBy(['title' => mb_strtolower($programTitle)]);
        $seasons = $seasonRepository
            ->findBy(['program' => $program], ['id' => 'DESC']);

        if (!$seasons) {
            throw $this->createNotFoundException(
                'No season for '.$programName.' name, found in season\'s table.'
            );
        }

        return $this->render('wild/programs.html.twig', [
            'seasons' => $seasons,
            'program' => $program
        ]);
    }

    /**
    * Getting episodes from one program and one season
    *
    * @param string $programName The program
    * @param integer $seasonNumber The season number
    * @Route("/{programName}/{seasonNumber}", name="show_season")
    * @return Response
    */
    public function showByProgramAndSeason(string $programName, int $seasonNumber, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository):Response
    {
        if (!$programName || !$seasonNumber) {
            throw $this
                ->createNotFoundException('No programName has been sent to find season\'s.');
        }
        
        $programTitle = ucwords(str_replace('-', ' ', $programName));
        $program = $programRepository
            ->findOneBy(['title' => mb_strtolower($programTitle)]);
        $season = $seasonRepository
            ->findOneBy(['number' => $seasonNumber]);
        $episodes = $episodeRepository
            ->findBy(['season' => $season], ['id' => 'ASC']);

        if (!$episodes) {
            throw $this->createNotFoundException(
                'No episode for '.$programName.' name and '.$seasonNumber.' season number, found in episode\'s table.'
            );
        }

        return $this->render('wild/seasons.html.twig', [
            'episodes' => $episodes,
            'programName' => $programName,
            'seasonNumber' => $seasonNumber,
        ]);
    }
}