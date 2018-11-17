<?php

namespace ProductoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ProductoBundle\Entity\Category;


class CategoryApiController extends Controller
{
    /**
     * @Route("categories/api/category/list", name="categories_api_category_list")
     */
    public function listAction()
    {
    	 $categories = $this->getDoctrine()
		    	 ->getRepository('ProductoBundle:Category')
		    	 ->findAll();

        $response= new Response();
        $response->headers->add([
                                    'Content-Type'=>'application/json'
                                ]);
        $response->setContent(json_encode($categories));
        return $response;
    }

    /**
     * Creates a new category entity.
     *
     * @Route("categories/api/category/new", name="categories_api_category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('ProductoBundle\Form\CategoryApiType', $category);
        $form->handleRequest($request);

        $response= new Response();
        $response->headers->add([
                                    'Content-Type'=>'application/json'
                                ]);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $response->setContent(json_encode($category));
        }else{

            $validator = $this->get('validator');
            $errors = $validator->validate($category);

            if (count($errors) > 0) {
                $messages=[];

                foreach ($errors as $violation) {
                    $messages[$violation->getPropertyPath()][] = $violation->getMessage();
                }

                $response->setContent(json_encode($messages));
        	}

        	$response->setStatusCode(400);

        }

        return $response;
    }
}
