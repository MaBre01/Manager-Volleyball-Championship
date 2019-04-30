<?php


namespace App\Controller;


use App\Entity\Championship;
use App\Exception\ChampionshipNotFound;
use App\Form\EditChampionship;
use App\Form\EditChampionshipType;
use App\Repository\ChampionshipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChampionshipController extends AbstractController
{
    /**
     * @Route("/championship", name="list_championship")
     */
    public function listChampionship(ChampionshipRepository $championshipRepository): Response
    {
        return $this->render('championship/list.html.twig', [
            "championships" => $championshipRepository->getAll()
        ]);
    }

    /**
     * @Route("/championship/new", name="add_championship")
     * @Route("/championship/{championshipId}/edit", name="edit_championship")
     */
    public function editChampionship(Request $request, ChampionshipRepository $championshipRepository, int $championshipId = 0): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
            $editForm = $this->createForm(
                EditChampionshipType::class,
                new EditChampionship($championship->getId(), $championship->getName())
            );
            $editMode = true;
        }
        catch(ChampionshipNotFound $exception){
            $editForm = $this->createForm(
                EditChampionshipType::class,
                new EditChampionship(0, null)
            );

            $editMode = false;
        }

        $editForm->handleRequest( $request );

        if( $editForm->isSubmitted() && $editForm->isValid() ){
            $editChampionship = $editForm->getData();

            if( $editMode ){
                $championship->rename( $editChampionship->name );
            }
            else{
                $championship = Championship::create($editChampionship);
            }

            $championshipRepository->save($championship);

            return $this->redirectToRoute('page_championship', [
               'championshipId' => $championship->getId()
            ]);
        }

        return $this->render('championship/edit.html.twig', [
            "editChampionshipForm" => $editForm->createView(),
            "editMode" => $editMode
        ]);
    }

    /**
     * @Route("/championship/{championshipId}", name="page_championship")
     */
    public function pageChampionship(int $championshipId, ChampionshipRepository $championshipRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
        }
        catch(ChampionshipNotFound $exception){
            return $this->redirectToRoute('list_championship');
        }

        return $this->render('championship/page.html.twig',[
            'championship' => $championship
        ]);
    }
}