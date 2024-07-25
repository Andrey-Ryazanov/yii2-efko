<?php
class User 
{
    private $id;
    private $name;
    private $position;
    private $age;

    public function __construct(string $name, string $position, int $age)
    {
        $this->name = $name;
        $this->position = $position;
        $this->age = $age;
        $this->initId();
    }

    public function initId(): void
    {
        $this->id = md5($this->age . ':'. $this->name . ':' . $this->position);     
    }

    public function getId(): string
    {
       return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getAge(): int
    {
        return $this->age;
    }
}

