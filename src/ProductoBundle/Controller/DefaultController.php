<?php

namespace ProductoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/products/list", name="listOfProducts")
     */
    public function indexAction()
    {
        if(isset($_GET['busqueda']))
        {
            $busqueda = $_GET['busqueda'];
            $repository = $this->getDoctrine()
                ->getRepository('ProductoBundle:Producto');

            $query = $repository->createQueryBuilder('p')
                ->where('p.name LIKE :nombre')
                ->setParameter('nombre', '%'.$busqueda.'%')
                ->orderBy('p.name', 'ASC')
                ->getQuery();
            $productos = $query->getResult();
        }else
        {
    	   $productos = $this->getDoctrine()
		    	 ->getRepository('ProductoBundle:Producto')
		    	 ->findAll();
        }
    	return $this->render('ProductoBundle:Default:index.html.twig' ,['productos'=> $productos]);
    }
    
}
