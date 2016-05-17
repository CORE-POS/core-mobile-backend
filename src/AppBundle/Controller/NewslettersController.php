<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Newsletters;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class NewslettersController extends Controller
{
    private function toJSON($obj)
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->serialize($obj, 'json');
    }

    /**
      * @Route("/newsletters/add")
      */
    public function addFlyer(Request $request)
    {
        $news = new Newsletters();

        $form = $this->createFormBuilder($news)
            ->add('name', TextType::class)
            ->add('issueDate', DateType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();

            return $this->redirectToRoute('app_newsletters_newsletter', ['id'=>$news->getId()]);
        }

        return $this->render('default/newsletter.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/newsletters")
     */
    public function listNewsletters()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle\Entity\Newsletters');
        $query = $repo->createQueryBuilder('n')
            ->orderBy('n.issueDate', 'DESC')
            ->getQuery();    
        $result = $query->getResult();

        return new Response(
            $this->toJSON($result),
            200,
            ['Content-type: application/json']
        );
    }

    /**
     * @Route("/newsletter/{id}")
     */
    public function newsletter($id)
    {
        $flyer = $this->getDoctrine()
            ->getRepository('AppBundle\Entity\Newsletters')
            ->find($id);

        $pages = $this->getDoctrine()
            ->getRepository('AppBundle\Entity\NewsletterPages')
            ->findBy(['newsletterID' => $id]);

        return new Response(
            $this->toJSON(['newsletter'=>$flyer,'pages'=>$pages]),
            200,
            ['Content-type: application/json']
        );
    }
}


