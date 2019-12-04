# D^3CTF 2019 Showhub

## 题目情况

| Name | Description | Score|Solved|
| ------ | ------ | ---- | ---- |
| Showhub | Showhub is a fashion-focused community built on a self-developed framework.Download this framework here | 880.7 |  2  |

## 如何启动

```shell
cd htdocs/
composer install
docker-compose up -d
```

## 出题思路以及Write Up：

### insert on duplicate key update 注入

题目给出了框架部分的源码，只有基本的 MVC 的实现和用户注册登录的逻辑代码。简单审计一下应该就可以发现在`Model::prepareUpdate`和`Model::prepareInsert`这两个方法中存在`格式化字符串SQL注入`

```php
        static private function ($baseSql, $args)
    {
        $i = 0;
        if (!empty($args)) {
            foreach ($args as $column => $value) {
                $value = addslashes($value);
                if ($value !== null) {
                    if ($i !== count($args) - 1) {
                        $baseSql = sprintf($baseSql, "`$column`,%s", "'$value',%s");
                    } else {
                        $baseSql = sprintf($baseSql, "`$column`", "'$value'");
                    }
                }
                $i++;
            }
        }

        return $baseSql;
    }

    static private function prepareUpdate($baseSql, $args)
    {
        $i = 0;
        if (!empty($args)) {
            foreach ($args as $column => $value) {
                $value = addslashes($value);
                if ($value !== null) {
                    if ($i !== count($args) - 1) {
                        $baseSql = sprintf($baseSql, "`$column`='$value',%s");
                    } else {
                        $baseSql = sprintf($baseSql, "`$column`='$value'");
                    }
                }
                $i++;
            }
        }

        return $baseSql;
    }
```

而只有`prepareInsert`方法在用户注册时被触发了，那么我们就拥有了一个`insert`注入。这时候大多数人第一时间的想法都是通过`insert`时间盲注注出管理员密码。然而管理员的密码强度足够，并不能根据其hash值推出明文。

这时候就涉及到了一个比较冷门的`insert`注入技巧，就是 `insert on duplicate key update` ，它能够让我们在新插入的一个数据和原有数据发生重复时，修改原有数据。那么我们通过这个技巧修改管理员的密码即可。

payload：`admin%1$',0x60) on duplicate key update password=0x38643936396565663665636164336332396133613632393238306536383663663063336635643561383661666633636131323032306339323361646336633932#`

### HTTP走私

成为管理员之后，还需要满足`Client-IP` 为内网 IP。因为这里的`Client-IP`头是反代层面设置的（set $Client-IP $remote_addr）, 所以无法通过前端修改请求头来伪造。

这时可以从服务器返回的`Server`头中发现，反代是`ATS7.1.2` 那么应该很敏感的想到通过`HTTP走私` 来绕过反代，规避反代设置`Client-IP`。这里需要构造两次走私，一次是访问`/WebConsole`拿到执行命令的接口，一次是访问接口执行命令，构造走私`payload`的过程很有意思，但是嘴上说起来就索然无味了，所以我这里就直接放出我最终的`payload`，不再多说这部分都有哪些坑了，真正有兴趣的同学强烈建议先别看`payload`，自己动手实践一下。

[payload](./smuggling_payload)

### 拓展

在当前题目的环境基础上进行少量修改，走私的情况就会发生微小的变化，可能会导致部分`payload`失效。探究这些变化发生的原因，可以帮助你更深入的理解`HTTP走私`，也可能会帮助你发现一些有趣的特性~，欢迎并期待各位师傅随时找我探讨。

1. 在`htdocs/Controllers/WebConsoleController.php`将判断内网ip的代码改成直接与`"127.0.0.1"`进行比较(不影响我给出的`payload`，但影响网上流传的部分`payload`)

2. 尝试修改`ats-etc/trafficserver/remap.config` 内的配置，使`ATS` 直接反代`Apache`(影响我上面给出的`payload`)

## 感谢

感谢@spine、@Alias、@Annevi 、@E99plant在出题过程中对我的帮助。
