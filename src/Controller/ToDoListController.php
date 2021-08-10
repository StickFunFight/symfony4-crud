<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="Todo_List")
     */
    public function index(): Response
    {
        $task = $this->getDoctrine()->getRepository(Task::class)->findBy([],['id'=>'DESC']);
        return $this->render('index.html.twig',
        ['tasks'=>$task]);
    }

    /**
     * @Route("/create", name="create_task", methods={"POST"})
     */
    public function Create(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = new Task();
        $title = trim($request->request->get('Task-title'));
        if(!empty($title)){
            $task->setTitle($title);
            $task->setStatus(0);
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('Todo_List');
        }else{
            return $this->redirectToRoute('Todo_List');
        }
    }

    /**
     * @Route("/update/{id}", name="update_task", methods={"GET"})
     */
    public function update($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $this->getDoctrine()->getRepository(Task::class)->find($id);
        $task->setStatus(!$task->getStatus());
        $entityManager->flush();
        return $this->redirectToRoute('Todo_List');
    }

    /**
     * @Route("/delete/{id}", name="delete_task", methods={"GET"})
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $this->getDoctrine()->getRepository(Task::class)->find($id);
        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirectToRoute('Todo_List');
    }
}
