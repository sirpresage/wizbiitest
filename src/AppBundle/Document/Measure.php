<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document
 */
class Measure
{
    
    function __construct($params) {
        foreach($params as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="int")
     * @Assert\NotBlank()
     * @Assert\EqualTo(1)
     * Version of the API. Only 1 is currently supported
     */
    protected $v;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     * @Assert\Choice({"pageview", "screenview"})
     * Hit type
     */
    protected $t;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\Url(
     *    message = "The url '{{ value }}' is not a valid url",
     * )
     * Document location. A valid URI representing the current page
     */
    protected $dl;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\Url(
     *    message = "The url '{{ value }}' is not a valid url",
     * )
     * Document referer. A valid URI representing the traffic source
     */
    protected $dr;
    
    /**
     * @MongoDB\Field(type="string")
     * @Assert\Expression(
     *     "value in ['profile', 'recruiter', 'visitor', 'wizbii_employee'] or !this.getIsMobile()",
     * )
     * Wizbii creator type. Mandatory for mobile hits. 
     * For web hits, this value can be read from wizbii cookies (not implemented yet)
     */
    protected $wct;
    
    /**
     * @MongoDB\Field(type="string")
     * @Assert\Expression(
     *     "value != '' or !this.getIsMobile()",
     * )
     * Wizbii user id. Mandatory for mobile hits. 
     * For web hits, this value can be read from wizbii cookies (not implemented yet)
     */
    protected $wui;
    
    /**
     * @MongoDB\Field(type="string")
     * @Assert\Expression(
     *     "value != '' or this.getIsMobile()",
     * )
     * Wizbii uniq user id. Mandatory for web hits. 
     * For web hits, this value can be read from wizbii cookies (not implemented yet)
     */
    protected $wuui;
    
    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     * @Assert\Regex("/^UA-\d{4}-\d{1}$/")
     * Tracking id. The tracking ID / web property ID. The format is UA-XXXX-Y. All collected data is associated by this ID.
     */
    protected $tid;
    
    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     * @Assert\Choice({"web", "apps", "backend"})
     * Data source of the hit.
     */
    protected $ds;
    
    /**
     * @MongoDB\Field(type="string")
     * @Assert\Expression(
     *     "value != '' or !(this.getT() == 'screenview' and this.getIsMobile())",
     * )
     * Screen name of the screenview hit. Mandatory for screenviews on mobile.
     */
    protected $sn;

    /**
     * @var boolean
     */
    protected $isMobile;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set v
     *
     * @param int $v
     * @return $this
     */
    public function setV($v)
    {
        $this->v = $v;
        return $this;
    }

    /**
     * Get v
     *
     * @return int $v
     */
    public function getV()
    {
        return $this->v;
    }

    /**
     * Set t
     *
     * @param string $t
     * @return $this
     */
    public function setT($t)
    {
        $this->t = $t;
        return $this;
    }

    /**
     * Get t
     *
     * @return string $t
     */
    public function getT()
    {
        return $this->t;
    }

    /**
     * Set dl
     *
     * @param string $dl
     * @return $this
     */
    public function setDl($dl)
    {
        $this->dl = $dl;
        return $this;
    }

    /**
     * Get dl
     *
     * @return string $dl
     */
    public function getDl()
    {
        return $this->dl;
    }

    /**
     * Set dr
     *
     * @param string $dr
     * @return $this
     */
    public function setDr($dr)
    {
        $this->dr = $dr;
        return $this;
    }

    /**
     * Get dr
     *
     * @return string $dr
     */
    public function getDr()
    {
        return $this->dr;
    }

    /**
     * Set wct
     *
     * @param string $wct
     * @return $this
     */
    public function setWct($wct)
    {
        $this->wct = $wct;
        return $this;
    }

    /**
     * Get wct
     *
     * @return string $wct
     */
    public function getWct()
    {
        return $this->wct;
    }

    /**
     * Set wui
     *
     * @param string $wui
     * @return $this
     */
    public function setWui($wui)
    {
        $this->wui = $wui;
        return $this;
    }

    /**
     * Get wui
     *
     * @return string $wui
     */
    public function getWui()
    {
        return $this->wui;
    }

    /**
     * Set wuui
     *
     * @param string $wuui
     * @return $this
     */
    public function setWuui($wuui)
    {
        $this->wuui = $wuui;
        return $this;
    }

    /**
     * Get wuui
     *
     * @return string $wuui
     */
    public function getWuui()
    {
        return $this->wuui;
    }

    /**
     * Set tid
     *
     * @param string $tid
     * @return $this
     */
    public function setTid($tid)
    {
        $this->tid = $tid;
        return $this;
    }

    /**
     * Get tid
     *
     * @return string $tid
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * Set ds
     *
     * @param string $ds
     * @return $this
     */
    public function setDs($ds)
    {
        $this->ds = $ds;
        return $this;
    }

    /**
     * Get ds
     *
     * @return string $ds
     */
    public function getDs()
    {
        return $this->ds;
    }

    /**
     * Set sn
     *
     * @param string $sn
     * @return $this
     */
    public function setSn($sn)
    {
        $this->sn = $sn;
        return $this;
    }

    /**
     * Get sn
     *
     * @return string $sn
     */
    public function getSn()
    {
        return $this->sn;
    }

     /**
     * Set isMobile
     *
     * @param boolean $isMobile
     * @return $this
     */
    public function setIsMobile($isMobile)
    {
        $this->isMobile = $isMobile;
        return $this;
    }

    /**
     * Get isMobile
     *
     * @return boolean $isMobile
     */
    public function getIsMobile()
    {
        return $this->isMobile;
    }

}
