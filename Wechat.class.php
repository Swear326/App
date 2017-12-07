<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/11/23
 * Time: 9:28
 **/
 class Wechat {
     //素材上传
//echo getMediaId(dirname(__FILE__)."./01.jpg",'image');
//封装
    /**
     * @param $filePath 上传媒体文件地址
     * @param $type 上传媒体文件类型 分别有图片(image) ,语音voice 视频video 缩略图 thumb
     * 图片 :2M 支持PNG\JPEG\JPG\GIF
     * 语音 :2M 播放长度不超过60s , 支持AMR\MP3 格式
     * 视频 :10MB, 支持MP4
     * 缩略图:64KB, 支持JPG格式
     */
   protected  static function getMediaId($filePath,$type){
        //设置接口
        $param =array(
            "access_token" =>getToken(),
            'type' => "$type"
        );
        $api = 'https://api.weixin.qq.com/cgi-bin/media/upload?'.http_build_query($param);
//设置提交的数据
//    $filePath = dirname(__FILE__)."/01.jpg";
        $postData = array('media' => new Curlfile($filePath)); //脚下留心, php的版本必须 高与5.5
//发送请求
        $rs = self::httpRequest($api,$postData);
        //转化为数组  decode , 是把拿到的json数据转化为php数组
        $data = json_decode($rs,true);
        //获取数据
       return isset($data['thumb_media_id'])?$data['thumb_media_id']:$data['media_id'];

    }



//$rs = httpRequest($api,$postData);
//var_dump($rs);
    /**
     * @return string 获取全局唯一的access token
     */

    protected static function getToken()
    {//设置接口
        $param = array (
            'appid' => 'wx710f9d46ac360a48',
            'secret' => 'f03abe6fbb243af093c8d81e34d5b217'
        );
        $api = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&'.http_build_query($param);
//2.请求接口
        $rs = httpRequest($api);
//3将json数据转化为数组(后面加个true就会转化为数组, 不加就是对象)
        $data = json_decode($rs,true);
//输出access_token
        return isset($data['access_token'])?$data['access_token']:$data['errcode'];
    }



    /***
     * @param  string $url
     * @param   array  null $postData(判断是否是post请求)
     * @return mixed
     */

    protected  static function httpRequest($url,$postData = null)
    {
        //初始化(curl_http)
        $ch = curl_init();
        //2.配置
        //设置请求地址
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置响应的数据流赋给变量而不是直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //发送请求
        //判断是否有post请求
        if($postData){
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
        }
        //curl注意事项，如果发送的请求是https，必须要禁止服务器端校检SSL证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        ///发送请求
        $data = curl_exec($ch);
        //关闭请求
        curl_close($ch);
        //响应数据
        return $data;
    }
 }
