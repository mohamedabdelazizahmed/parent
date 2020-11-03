<?php
namespace App\Services;

use App\ProviderFilter;
use App\User;

class ProviderService{

    public $data =  [];

    public function __construct() {
    }

    public function search(ProviderFilter $filter)
    {
        $this->readJsonFile($this->listOfFiles(['DataProviderX' ,'DataProviderY']));
        return User::filter($filter ,$this);
    }

    public function readJsonFile($paths){
        $collection = null;
        $this->data = [];
        foreach($paths as $path){
            if (file_exists($path)) {
                $collection  = json_decode(file_get_contents($path) ,true);
                array_push($this->data , collect($collection['users']));   
            }
        }
        return $this->data;
    }

    public function listOfFiles($filesName){
        $paths = [];
        foreach($filesName as $filename){
            $path  = storage_path() . "/json/${filename}.json";
            array_push($paths ,$path);
        }
        return $paths;
    }
}