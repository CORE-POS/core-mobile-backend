<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="SalesFlyerPages")
 */
class SalesFlyerPages
{
    /**
     * @ORM\Column(type="integer", options={"default"="nextval('salesflyerpages_id_seq'::regclass)"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="SalesFlyers")
     */
    private $salesFlyerID;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $url;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set salesFlyerID
     *
     * @param integer $salesFlyerID
     *
     * @return SalesFlyerPages
     */
    public function setSalesFlyerID($salesFlyerID)
    {
        $this->salesFlyerID = $salesFlyerID;

        return $this;
    }

    /**
     * Get salesFlyerID
     *
     * @return integer
     */
    public function getSalesFlyerID()
    {
        return $this->salesFlyerID;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return SalesFlyerPages
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
