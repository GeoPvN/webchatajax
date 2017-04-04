<?php

class detectDevice{
    
    public $userAgent;
    public $os;
    public $browser;
    public $osList;
    public $browserList;
    
    public function detectDevice(){
        
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        
        $this->osList    = array(   '/windows nt 10/i'      =>  'windows',
                                    '/windows nt 6.3/i'     =>  'windows',
                                    '/windows nt 6.2/i'     =>  'windows',
                                    '/windows nt 6.1/i'     =>  'windows',
                                    '/windows nt 6.0/i'     =>  'windows',
                                    '/windows nt 5.2/i'     =>  'windows',
                                    '/windows nt 5.1/i'     =>  'windows',
                                    '/windows xp/i'         =>  'windows',
                                    '/windows nt 5.0/i'     =>  'windows',
                                    '/windows me/i'         =>  'windows',
                                    '/win98/i'              =>  'windows',
                                    '/win95/i'              =>  'windows',
                                    '/win16/i'              =>  'windows',
                                    '/macintosh|mac os x/i' =>  'apple',
                                    '/mac_powerpc/i'        =>  'apple',
                                    '/linux/i'              =>  'linux',
                                    '/ubuntu/i'             =>  'linux',
                                    '/iphone/i'             =>  'apple',
                                    '/ipod/i'               =>  'apple',
                                    '/ipad/i'               =>  'apple',
                                    '/android/i'            =>  'android',
                                    '/blackberry/i'         =>  'mobile',
                                    '/webos/i'              =>  'mobile');
        
        $this->browserList  =  array('/msie/i'       =>  'ie',
                                     '/firefox/i'    =>  'firefox',
                                     '/safari/i'     =>  'safari',
                                     '/chrome/i'     =>  'chrome',
                                     '/edge/i'       =>  'ie',
                                     '/opera/i'      =>  'opera',
                                     '/netscape/i'   =>  'unknow',
                                     '/maxthon/i'    =>  'unknow',
                                     '/konqueror/i'  =>  'unknow',
                                     '/mobile/i'     =>  'unknow');
                                
        
    }
    
    public function getOS(){
        
        $this->os  = "unknow";
        
        foreach ($this->osList as $regex => $value) {
        
            if (preg_match($regex, $this->userAgent)) {
                $this->os    =   $value;
            }
        
        }
        
        return $this->os;
    }
    
    
    public function getBrowser() {

        $this->browser  = "unknow";

        foreach ($this->browserList as $regex => $value) {
    
            if (preg_match($regex, $this->userAgent)) {
                $this->browser    =   $value;
            }
    
        }
    
        return $this->browser;

    }
}

?>