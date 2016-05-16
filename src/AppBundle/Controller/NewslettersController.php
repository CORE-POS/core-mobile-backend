<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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


