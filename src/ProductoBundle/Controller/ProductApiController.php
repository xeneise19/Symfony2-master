<?php

namespace ProductoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ProductoBundle\Entity\Producto;

class ProductApiController extends Controller
{
    /**
     * @Route("products/api/product/list", name="product_api_product_list")
     */
    public function listAction()
    {
    	 $productos = $this->getDoctrine()
		    	 ->getRepository('ProductoBundle:Producto')
		    	 ->findAll();

        $response= new Response();
        $response->headers->add([
                                    'Content-Type'=>'application/json'
                                ]);
        $response->setContent(json_encode($productos));
        return $response;
    }


    /**
     * Creates a new producto entity.
     *
     * @Route("products/api/product/new", name="products_api_product_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $producto = new Producto();
        $form = $this->createForm('ProductoBundle\Form\ProductoApiType', $producto);
        $form->handleRequest($request);

	    $response= new Response();
	    $response->headers->add([
			'Content-Type'=>'application/json'
		]);
	    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($producto);
            $em->flush();
            $response->setContent(json_encode($producto));
        }else{
            $validator = $this->get('validator');
            $errors = $validator->validate($producto);

            if (count($errors) > 0) {
                $messages = [];

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
