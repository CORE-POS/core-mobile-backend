<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="SalesFlyerPages")
 * @ORM\HasLifecycleCallbacks
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
     * @param UploadedFile $file
     *
     * @return SalesFlyerPages
     */
    public function setUrl(UploadedFile $url)
    {
        $this->url = $url;

        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }

        return $this;
    }

    /**
     * Get url
     *
     * @return UploadedFile
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function getAbsolutePath()
    {
        return null === $this->url
            ? null
            : $this->getUploadRootDir().'/'.$this->url;
    }

    public function getWebPath()
    {
        return null === $this->url
            ? null
            : $this->getUploadDir().'/'.$this->url;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getUrl()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getUrl()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getUrl()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getUrl()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->url = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }
}

