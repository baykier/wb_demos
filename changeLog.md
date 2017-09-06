变更历史
=======

v1.2.0
------

- 签名字典排序算法由按照**value**排序改为按照**key**排序
- *composer.json*加入php扩展openssl依赖(ext-openssl)

v1.1.0
------

- 修复`\Wb\Request`类中biz_data中的参数不能为0的bug(isset($reuqest->param) param 不能为0)
- 修改`\Wb\Request`类中 访问不存在的biz_data的业务参数抛出异常为返回空字符串
- 变更历史记录文件新增(changeLog.md)

v1.0.0
------

- 项目初始化