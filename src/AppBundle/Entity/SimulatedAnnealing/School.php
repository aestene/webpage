<?php
namespace AppBundle\Entity\SimulatedAnnealing;

class School
{
    private $name;
    private $assistants;//An associative array. Key = weekday, Value = list of assistants. "Tuesday => [assistant1, assistant2, ...]
    private $capacity;//An associative array. Key = weekday, Value = capacity. "Wednesday" => 4.

    /**
     * School constructor.
     * @param capacity
     * @param name
     */
    public function __construct($capacity, $name)
    {
        $this->assistants = array();
        $this->capacity = $capacity;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function deepCopy(){
        $copy = new School($this->capacity, $this->name);
        $copyAssistants = array();
        foreach($this->assistants as $day => $assistant){
            if(!array_key_exists($day, $copyAssistants)){
                 $copyAssistants[$day]=array();
            }
            foreach($assistant as $ass){
                $copyAssistants[$day][] = $ass->deepCopy($copy);
            }
        }
        $copy->setAssistants($copyAssistants);
        return $copy;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    public function addAssistant($assistant, $day){
        if(!array_key_exists($day, $this->assistants)){
            $this->assistants[$day]=array();
        }
        array_push($this->assistants[$day], $assistant);
    }

    /**
     * @return mixed
     */
    public function getAssistants()
    {
        return $this->assistants;
    }

    /**
     * @param mixed $assistants
     */
    public function setAssistants($assistants)
    {
        $this->assistants = $assistants;
    }

    public function removeAssistant(Assistant $assistant, $day){
        if(($key = array_search($assistant, $this->assistants[$day])) !== false) {
            unset($this->assistants[$day][$key]);
        }
    }

    /**
     * @return mixed
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param mixed $capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }



}