<?php
namespace AppBundle\Controller;

use AppBundle\Entity\SalesFlyers;
use AppBundle\Entity\SalesFlyerPages;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FlyersController extends Controller
{
    private function toJSON($obj)
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->serialize($obj, 'json');
    }

    /**
      * @Route("/flyers/add")
      */
    public function addFlyer(Request $request)
    {
        $flyer = new SalesFlyers();

        $form = $this->createFormBuilder($flyer)
            ->add('name', TextType::class)
            ->add('startDate', DateType::class)
            ->add('endDate', DateType::class)
            ->add('save', SubmitType::class, ['label'=>'Create Flyer'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($flyer);
            $em->flush();

            return $this->redirectToRoute('app_flyers_flyer', ['id'=>$flyer->getId()]);
        }

        return $this->render('default/flyer.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/flyers")
     */
    public function listFlyers()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle\Entity\SalesFlyers');
        $query = $repo->createQueryBuilder('f')
            ->where(':now BETWEEN f.startDate AND f.endDate')
            ->setParameter('now', date('Y-m-d'))
            ->getQuery();    
        $result = $query->getResult();

        return new Response(
            $this->toJSON($result),
            200,
            ['Content-type: application/json']
        );
    }

    /**
      * @Route("/flyers/pages")
      */
    public function addPages(Request $request)
    {
        $pages = new SalesFlyerPages();
        $form = $this->createFormBuilder($pages)
            ->add('salesFlyerID', EntityType::class, ['class'=>'AppBundle:SalesFlyers', 'choice_label'=>'name', 'label'=>'Flyer'])
            ->add('url', null, ['label'=>'Image File'])
            ->add('save', SubmitType::class, ['label'=>'Add Page'])
            ->getForm();
        $form->handleRequest($request);
        
        return $this->render('default/flyer.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/flyer/{id}")
     */
    public function flyer($id)
    {
        $flyer = $this->getDoctrine()
            ->getRepository('AppBundle\Entity\SalesFlyers')
            ->find($id);

        $pages = $this->getDoctrine()
            ->getRepository('AppBundle\Entity\SalesFlyerPages')
            ->findBy(['salesFlyerID' => $id]);

        return new Response(
            $this->toJSON(['flyer'=>$flyer,'pages'=>$pages]),
            200,
            ['Content-type: application/json']
        );
    }
}


