<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="NewsletterPages")
 */
class NewsletterPages
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Newsletters")
     */
    private $newsletterID;

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

    /**
     * Set newsletterID
     *
     * @param integer $newsletterID
     *
     * @return NewsletterPages
     */
    public function setNewsletterID($newsletterID)
    {
        $this->newsletterID = $newsletterID;

        return $this;
    }

    /**
     * Get newsletterID
     *
     * @return integer
     */
    public function getNewsletterID()
    {
        return $this->newsletterID;
    }
}
