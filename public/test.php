<?php


class testAbs
{
    protected $data;
    protected $title;

    protected $model;

    public function handel(){
        var_dump($this->getTitle());

        $this->model::saveData($this->getTitle());
    }

    public function getTitle(){
        return $this->title;
    }

}


class testChildAbs extends testAbs
{
    protected $title = '影片';
    protected $model = TestModel::class;
    
}


class TestModel 
{
    


    static function saveData($title){
        echo 1111;
        var_dump($title . '333');
    }
}

$test1 = new testChildAbs();

echo $test1->handel();


var_dump('1');