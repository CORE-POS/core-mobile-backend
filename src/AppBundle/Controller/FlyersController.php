<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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


