<?php
require './Wechat.class.php';
/**
 * wechat php test
 */

//define your token
define("TOKEN", "zhoujiachun");
$wechatObj = new wechatCallbackapiTest();
//验证服务器是否响应,仅开启开发者模式时使用一次

// $wechatObj->valid();//
$wechatObj->responseMsg();


class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        //和post一样获取数据, 只不过他可以获取xml数据
        //接受微信服务器提交过来的先xml报文

        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)) {
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            //安全处理:禁止引用外部xml实体, 防止xxe漏洞

            libxml_disable_entity_loader(true);
            //将xml数据转化为对象
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;  //那个用户发送的,发送者

            $toUsername = $postObj->ToUserName;  //发送给哪个用户,接受者

            $keyword = trim($postObj->Content); //发送的内容(可能是图片,文字, 视频等)
            //被动回复文本xml报文
            //根据消息xml的MsgTypg参数判断请求文件类型
            switch ($postObj->MsgType) {
                case 'event':
                    if ($postObj->Event == 'subscribe') {
                        $this->sendText($fromUsername, $toUsername, '感谢关注');
                    }elseif ($postObj ->Event == 'CLICK'&& $postObj ->EventKey == 'V1001_TODAY_MUSIC')
                    {
                        $this-> sendMusic($fromUsername,$toUsername,'好听的','音乐');
                    }
                    else {
                        //后期项目封装可能需要修改数据库
                    }
                    break;
                case 'text':
                    if ($keyword == '单图文'){
//                        $this->sendText($fromUsername, $toUsername, '单图文');
                                    $this->sendImageArticle($fromUsername,$toUsername, array(
                                        array(
                                            'title'=>'单图文',
                                            'desc'=>'hahah',
                                            'picurl'=>'http://39.108.129.80/img/b1.jpg',
                                            'url' => 'http://baidu.com'
                                        )
                                    ));
                    }
                    elseif($keyword =='多图文')
                    {
                        $this->sendImageArticle($fromUsername,$toUsername,array(
                            array(
                                'title'=>'多图文1',
                                'desc'=>'hahah',
                                'picurl'=>'http://39.108.129.80/img/noe.png',
                                'url' => 'http://baidu.com'
                            ),
                            array(
                                'title'=>'多图文2',
                                'desc'=>'hahah',
                                'picurl'=>'http://39.108.129.80/img/two.png',
                                'url' => 'http://baidu.com'
                            ),
                            array(
                                'title'=>'多图文3',
                                'desc'=>'hahah',
                                'picurl'=>'http://39.108.129.80/img/three.png',
                                'url' => 'http://baidu.com'
                            )
                        ));
                    }elseif ($keyword == '发送图片'){
                        $this->sendImage($fromUsername, $toUsername);
                    }
                    else{
                        $this->sendText($fromUsername, $toUsername, '文本消息');
                    }

                    break;
                case 'image':
//                    $this->sendText($fromUsername, $toUsername, '图片消息');
                    $this->sendImage($fromUsername,$toUsername);
                    break;
                case 'voice':
                    $this-> sendMusic($fromUsername,$toUsername,'好听的','音乐');
//                    $this->sendText($fromUsername, $toUsername, '语音消息');
                    break;
                case 'video':
//                    $this->sendText($fromUsername, $toUsername, '视频消息');
                    $this->sendVideo($fromUsername,$toUsername,'hahah','lail');
                    break;
                case 'shortvideo':
                    $this->sendText($fromUsername, $toUsername, '小视频消息');
                    break;
                case 'location':
                    $this->sendText($fromUsername, $toUsername, '位置消息');
                    break;
                case 'link':
                    $this->sendText($fromUsername, $toUsername, '链接消息');
                    break;
                default:
                    $this->sendText($fromUsername, $toUsername, '...');
                    break;
            }
        } else {
            echo "";
            exit;
        }
    }

    /** //恢复文字消息
     * @param $fromUsername //接受消息的用户
     * @param $toUsername // 发送消息的用户
     * @param $content //内容
     * @return string
     */
    private function sendText($fromUsername, $toUsername, $content)
    {

        $textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $content);
        echo $resultStr;
    }

    private function sendImage($fromUsername,$toUsername)
    {

        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[image]]></MsgType>
            <Image>
            <MediaId><![CDATA[%s]]></MediaId>
            </Image>
            </xml>";
        $media_id = 'w0EIIgtM4xazFAc42LZWRdHkxktvSxRyt-hvRVCZ7Wo9E0uxifT4Ps_V0d79NslL';
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $media_id);
        echo $resultStr;
    }

    private function sendImageArticle($fromUsername,$toUsername,$arr = array())
    {
//        if(!$arr){
//            $arr = array(
//                array('title'=>'图片1','dec' =>'图片2')
//            );
//        }
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>%s</ArticleCount>
                    <Articles>";
                    foreach ($arr as $img ) {
                        $textTpl .= "<item>
                    <Title><![CDATA[{$img['title']}]]></Title> 
                    <Description><![CDATA[{$img['desc']}]]></Description>
                    <PicUrl><![CDATA[{$img['picurl']}]]></PicUrl>
                    <Url><![CDATA[{$img['url']}]]></Url>
                    </item>";
                    }
        $textTpl .=" </Articles>
                    </xml>";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), count($arr));
        echo $resultStr;
    }

    /**
     * @param $fromUsername
     * @param $toUsername
     * @param $title
     * @param $desc
     */
    private function sendVideo($fromUsername,$toUsername,$title,$desc)
    {

        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
<Video>
<MediaId><![CDATA[%s]]></MediaId>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
</Video> 
</xml>";
        $media_id = 'kTKMTEhF1P8fibUldTPGYBR9KdrnsDb2jJl1ig6Up0J0BP98zfVqoFp2PaxzeuV6';
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $media_id, $title, $desc);
        echo $resultStr;
    }

    private function sendMusic($fromUsername,$toUsername,$title,$desc)
    {

        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
<Music>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<MusicUrl><![CDATA[%s]]></MusicUrl>
<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
</Music>
</xml>";
        $music_url='http://39.108.129.80/music.mp3';
        $media_id = 'oH51ALrwm1IVhlak3GK7tCLczu4bx3FZkQaTZeoyWGSNHOH0nM_bhl_BPp3C0Mov';
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $title, $desc,$music_url,$music_url,$media_id);
        echo $resultStr;
    }



    private function checkSignature()
    {
        // you must define TOKEN by yourself
        //检查token是否存在
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}

?>