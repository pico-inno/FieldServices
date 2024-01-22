<?php

namespace App\Datatables;

trait datatable
{
    //from livewire
    protected $queryString = ['search', 'perPage'];

    //customize

    public $search= null;
    public $perPage = 15;
    public $aviablePerPages=[
        '15','30','45','55'
    ];


    public $sortField='id';
    public $sortAsc=false;
    /**
     * sort By Column field
     *
     * @param  string $field
     * @return void
     */
    public function sortBy(String $field){
        if($this->sortField === $field){
            $this->sortAsc=!$this->sortAsc;
        }else{
            $this->sortAsc=true;
        }
        $this->sortField=$field;
    }
}
