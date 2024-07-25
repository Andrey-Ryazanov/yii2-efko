<?php

class UserStatePrintService
{
    private $separator;

    public function __construct(string $separator)
    {
        $this->separator = $separator;
    }

    public  function printState($users)
    {
        echo '<pre>';
        foreach ($users as $user)
        {
            echo 'ID пользователя: '.$user->getId() . $this->separator;
            echo 'Имя: '.$user->getName() . $this->separator;
            echo 'Возраст: '.$user->getAge() . $this->separator;
            echo 'Позиция: '.$user->getPosition() . $this->separator;
            echo '===================================' . $this->separator;
        }
    }

    public function getSeparator(){
        return $this->separator;
    }


}