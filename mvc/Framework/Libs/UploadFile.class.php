<?php
namespace Libs;

//1.定义类
class UploadFile
{
    /**
     * 存放错误信息
     * @var array
     */
    private static $message = array();

    /**
     * 获取错误信息
     * @return string
     */
    public static function getMessage()
    {
        return isset(self::$message[0]) ? self::$message[0] : '';
    }

    /**
     * 上传文件
     * @param  array $image 固定5个值（error、name、size、tmp_name、type）
     * @return string|bool  成功-新文件名称,失败-false
     */
    public static function upload($image)
    {
        //1.检测是否存在错误
        if (!self::checkError($image['error'])) return false;
        //2.限制上传文件大小
        if (!self::checkSize($image['size'])) return false;
        //3.检测是否真实图片类型
        if (!self::checkType($image['tmp_name'])) return false;
        //4.移动文件
        return self::moveFile($image['name'], $image['tmp_name']);
    }

    /**
     * 检查是否存在错误
     * @param  int  $error 错误代码
     * @return bool
     */
    private static function checkError($error)
    {
        //1.是否存在错误
        if ($error != 0) {
            self::$message[] = '上传有误';
            return false;
        }
        return true;
    }

    /**
     * 限制上传文件大小
     * @param  int  $size  文件大小
     * @return bool
     */
    private static function checkSize($size)
    {
        //2.限制上传文件大小
        if ($size > 1024*1024*2) {
            self::$message[] = '上传文件过大只能2M';
            return false;
        }
        return true;
    }

    /**
     * 检测文件真实类型
     * @param  string $tmp_name 临时文件
     * @return bool
     */
    private static function checkType($tmp_name)
    {
        //3.检测文件真实类型
        //3.1开启php_file_info.dll扩展
        //3.2获取资源
        $rs = finfo_open(FILEINFO_MIME_TYPE);
        //3.3获取真实的后缀
        $realType = finfo_file($rs, $tmp_name);
        //3.4判断
        $allowType = array('images/png', 'images/jpeg', 'images/gif');
        if (!in_array($realType, $allowType)) {
            self::$message[] = '只能上传png、jpg、gif格式文件';
            return false;
        }
        return true;
    }

    /**
     * 移动文件
     * @param  string $name     原文件名
     * @param  string $tmp_name 临时文件
     * @return sting|bool
     */
    private static function moveFile($name, $tmp_name)
    {
        //4.移动临时文件，move_uploaded_file（旧文件，新文件）
        $new_name =  uniqid() . mt_rand(100, 999) . strrchr($name, '.');
        if (move_uploaded_file($tmp_name, './Public/uploads/' . $new_name)) {
            return $new_name;
        } else {
            return false;
        }
    }
}
