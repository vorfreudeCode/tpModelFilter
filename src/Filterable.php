<?php
namespace tpModelFilter;

trait Filterable
{

    /**
     * 创建filter对象
     * @param $query
     * @param array $input
     * @param null $filter
     * @return mixed
     */
    public function scopeFilter($query,array $input,$filter=null){

        if($filter !== null){
            $class = "\\app\\modelFilter\\".$filter;
        }else{
            $class = "\\app\\modelFilter\\".__CLASS__."Filter";
        }
        $modelFilter = new $class($query,$input);

        return $modelFilter->handle();
    }



}