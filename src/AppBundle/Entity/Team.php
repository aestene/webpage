<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="Team")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\TeamRepository")
 */
class Team {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
	/**
     * @ORM\Column(type="string", length=250)
     */
    protected $name;
	
	/**
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="teams")
     **/
	protected $department;
	
	/**
     * @ORM\ManyToMany(targetEntity="Subforum", mappedBy="teams")
     **/
	protected $subforums;
	
	public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
		$this->subforums = new \Doctrine\Common\Collections\ArrayCollection();
    }

	
	public function __toString()
	{
		return (string) $this->getName();
	}
	

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
     * Set name
     *
     * @param string $name
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set department
     *
     * @param \AppBundle\Entity\Department $department
     * @return Team
     */
    public function setDepartment(\AppBundle\Entity\Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return \AppBundle\Entity\Department
     */
    public function getDepartment()
    {
        return $this->department;
    }



    /**
     * Add subforums
     *
     * @param \AppBundle\Entity\Subforum $subforums
     * @return Team
     */
    public function addSubforum(\AppBundle\Entity\Subforum $subforums)
    {
        $this->subforums[] = $subforums;

        return $this;
    }

    /**
     * Remove subforums
     *
     * @param \AppBundle\Entity\Subforum $subforums
     */
    public function removeSubforum(\AppBundle\Entity\Subforum $subforums)
    {
        $this->subforums->removeElement($subforums);
    }

    /**
     * Get subforums
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubforums()
    {
        return $this->subforums;
    }
	
	// Used for unit testing 
	public function fromArray($data = array())
    {
        foreach ($data as $property => $value) {
            $method = "set{$property}";
            $this->$method($value);
        }
    }
	
}
