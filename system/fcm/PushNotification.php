<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ali_Ai
 * Date: 9/15/2018
 * Time: 9:26 PM
 *
 * Learn from: www.androidhive.info
 */

class PushNotification
{
    // push message title
    private $title;
    private $message;
    private $image;
    // push message payload
    private $data;
    // flag indicating whether to show the push
    // notification or not
    // this flag will be useful when perform some opertation
    // in background when push is recevied
    private $is_background;

    function __construct()
    {

    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setImage($imageUrl)
    {
        $this->image = $imageUrl;
    }

    public function setPayload($data)
    {
        $this->data = $data;
    }

    public function setIsBackground($is_background)
    {
        $this->is_background = $is_background;
    }

    public function getPushNotification()
    {
        $res = array();
        $res['data']['title'] = $this->title;
        $res['data']['message'] = $this->message;
        $res['data']['image'] = $this->image;
        $res['data']['payload'] = $this->data;
        $res['data']['is_background'] = $this->is_background;
        $res['data']['timestamp'] = getCurrentDateTime();
        return $res;
    }
}