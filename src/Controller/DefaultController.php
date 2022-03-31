<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * Lorsque nous sommes dans un Controller,
     * les fonctions sont considérées comme des actions (une fonction = une action)
     *
     * TOUTES vos annotations DOIVENT être COLLÉE aux fonctions
     * Dans les parenthèses, ce sera TOUJOURS des doubles quotes !
     *
     * Nous avons passé dans home() des objets en paramètre (Request, EntityManagerInterface)
     *      => On apppelle ça une 'injection de dépendances'
     *      => Cela permet d'injecter des objets dans notre fonction (qu'on appelle action) pour pouvoir s'en servir.
     *
     * @Route("/", name="show_home", methods={"GET|POST"})
     */
    public function home(Request $request, EntityManagerInterface $entityManager): Response
    {
        // On récupère et variabilise tous les employés inscrits en BDD grâce à $entityManager.
        // On demande le Repository de Employe::class et on utlise la methode findAll().
                # => !! Attention : On renseigne Employe::class et pas EmployeRepository dans getRepository(). !!
        $employes = $entityManager->getRepository(Employe::class)->findAll();

        // Instanciation d'un objet (ENTITÉ)
        $employe = new Employe();

        // On crée le formulaire à partir du prototype (créé en ligne de commande make:form)
        //      => Grâce au deuxième paramètre $employe dans la méthode createForm(), et à handleRequest(),
        //      notre objet Employe est automatiquement 'hydraté'.
        $form = $this->createForm(EmployeFormType::class, $employe)
            ->handleRequest($request);

        // Condition de vérification de l'état du formulaire
        //      => si le formulaire est soumis et qu'il est valide, alors le code dans le if sera exécuté.
        if($form->isSubmitted() && $form->isValid()) {

            // Cas où la valeur du champ 'poste' est null
            if($form->get('poste')->getData() === null) {
                $this->addFlash('warning', 'Vous devez renseigner un poste pour l\'employé');
                return $this->redirectToRoute('show_home');
            }

            /* Cet exemple : $form->get('prenom')->getData();
                permet de récupérer la valeur d'un input de notre formulaire. (ici le prénom)

              => Nous pouvons nous en passer ici, car nous avons plutôt utilisé l'hydratation automatique de Symfony.
            */

            // $entityManager est votre manager d'entité qui peut accéder à la BDD, et LUI SEUL.
            //      => persist() permet de créer de la persistance.
            $entityManager->persist($employe);

            // Cette ligne et instruction flush() vous permet de synchroniser vos données (de l'objet $employe) avec la BDD.
            $entityManager->flush();

            // Ici nous redirigeons l'utilisateur.
            return $this->redirectToRoute('show_home');
        } // if($form)


        // la méthode render() "regarde" toujours dans le dossier "templates"
        return $this->render('default/home.html.twig', [
            'form' => $form->createView(),
            'employes' => $employes,
        ]);
    } // fonction home()

    /**
     * Dans les paranthèses de la Route(), vous DEVEZ utiliser des doubles quotes !
     *
     * @Route("/modifier-un-employe_{id}", name="update_employe", methods={"GET|POST"})
     */
    public function updateEmploye(Employe $employe, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmployeFormType::class, $employe)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('show_home');
        }

        return $this->render('default/update_employe.html.twig', [
            'form' => $form->createView(),
            'employe' => $employe
        ]);
    }// fonction updateEmploye()

    /**
     * @Route("/supprimer-un-employe/{id}", name="delete_employe", methods={"GET"})
     */
    public function deleteEmploye(Employe $employe, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($employe);

        $entityManager->flush();

        return $this->redirectToRoute('show_home');
    }
}