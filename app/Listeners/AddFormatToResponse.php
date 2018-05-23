<?php
namespace App\Listeners;

use Dingo\Api\Event\ResponseWasMorphed;

class AddFormatToResponse
{
    public function handle(ResponseWasMorphed $event)
    {
        if($event->response->getStatusCode() == 200){
            $content = $event->response->getContent();
            if($content == ""){
                $content = array();
            }
            if(isset($content['data'])){
                $content = $content['data'];
            }
            if(isset($content->data)){
                $content = $content->data;
            }
            $event->response->setContent([
                'success' => true,
                'error_code' => 0,
                'data'=>$content ,
                'error_msg' => ''
            ]);
        }
    }
}