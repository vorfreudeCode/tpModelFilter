<?php
namespace tpModelFilter;

use think\db\Builder;

abstract class ModelFilter
{
    protected $query;
    protected $input;
    protected $blacklist=[]; //不需要转化的参数
    public function __construct($query,$input)
    {
        $this->query = $query;
        $this->input = $this->removeEmptyInput($input);
    }

    public function __call($method, $args)
    {
        $resp = call_user_func_array([$this->query, $method], $args);

        // Only return $this if query builder is returned
        // We don't want to make actions to the builder unreachable
        return $resp instanceof Builder ? $this : $resp;
    }

    /**
     * 转化所有的参数
     */
    public function filterInput()
    {
        foreach ($this->input as $key => $val) {

            $method = $this->camelClass($key);

            if ($this->methodIsCallable($method)) {
                $this->{$method}($val);
            }
        }
    }


    /**
     * 执行
     * @return mixed
     */
    public function handle()
    {
        if (method_exists($this, 'setup')) {
            $this->setup();
        }

        $this->filterInput();


        return $this->query;
    }

    /**
     * 删除无效参数
     * @param $input
     * @return array
     */
    public function removeEmptyInput($input)
    {
        $filterableInput = [];

        foreach ($input as $key => $val) {
            if ($val !== '' && $val !== null) {
                $filterableInput[$key] = $val;
            }
        }

        return $filterableInput;
    }

    /**
     * 过滤黑名单字段
     * @param $method
     * @return bool
     */
    public function methodIsBlacklisted($method)
    {
        return in_array($method, $this->blacklist, true);
    }

    public function methodIsCallable($method)
    {
        return ! $this->methodIsBlacklisted($method) &&
            method_exists($this, $method) &&
            ! method_exists(ModelFilter::class, $method);
    }

    /**
     * 转为驼峰
     * @param $uncamelized_words
     * @param string $separator
     * @return string
     */
    private function camelClass($uncamelized_words,$separator='_')
    {
        $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }



}