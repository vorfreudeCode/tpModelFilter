# 说明
一个简单的基于thinkphp6的orm插件，thinkphp5可以自己尝试

不才，完全借鉴大神的代码，原项目地址： https://github.com/Tucker-Eric/EloquentFilter 

# 使用

使用composer引入

`composer require syhcode/tp-model-filter`

在console.php中加入

`'make:filter' => 'tpModelFilter\FilterCommond'`

在模型中引用`Filterable`

```php
<?php
declare (strict_types = 1);

namespace app\model;


use think\Model;
use tpModelFilter\Filterable;

/**
 * @mixin think\Model
 */

class Users extends Model
{
    use Filterable;

}

```

使用命令行   `think make:filter UsersFilter`   创建filter文件，默认格式为"模型名+Filter" ，传参会过滤空参，自动转化为驼峰写法比如`nick_name`会转化为`nickName`，调用filter里面的`nickName`方法，完全继承模型的方法

```php
<?php
namespace app\modelFilter;

use tpModelFilter\ModelFilter;

class UsersFilter extends ModelFilter {


    public function name($value){
        return $this->whereLike("name","%".$value."%");
    }

    public function nickName($value){
        return $this->where("password",$value);
    }

    
}
```

使用  `filter` 调用

```php
<?php
namespace app\controller;

use app\BaseController;
use app\model\Users;
use app\Request;

class Index extends BaseController
{
    public function index(Request $request,Users $users)
    {

        return $users->filter($request->request())->select();
    }
    
}

```

