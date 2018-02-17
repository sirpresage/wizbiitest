<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document
 */
class Measure
{
    
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
     * @Assert\Choice({"profile", "recruiter", "visitor", "wizbii_employee"})
     * Wizbii creator type. Mandatory for mobile hits. 
     * For web hits, this value can be read from wizbii cookies (not implemented yet)
     */
    protected $wct;
    
    /**
     * @MongoDB\Field(type="string")
     * Wizbii user id. Mandatory for mobile hits. 
     * For web hits, this value can be read from wizbii cookies (not implemented yet)
     */
    protected $wui;
    
    /**
     * @MongoDB\Field(type="string")
     * Wizbii uniq user id. Mandatory for mobile hits. 
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
     * Screen name of the screenview hit.
     */
    protected $sn;
    

