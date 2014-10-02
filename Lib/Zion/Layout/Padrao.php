<?php

namespace Zion\Layout;

class Padrao extends \Zion\Layout\Html
{
    private $html;
    private $css;
    private $javascript;
    
    public function __construct()
    {
        $this->html = new \Zion\Layout\Html();
        $this->css = new \Zion\Layout\Css();
        $this->javascript = new \Zion\Layout\JavaScript();
    }


    public function topo()
    {

        $buffer  = false;        
        $buffer .= $this->html->abreTagAberta('!DOCTYPE html');
        $buffer .= $this->html->abreTagAberta('html', array('lang'=>'pt-BR'));
        $buffer .= $this->html->abreTagAberta('head');
            $buffer .= $this->html->abreTagAberta('meta', array('charset'=>'utf-8'));
            $this->html->abreTagAberta('meta', array('http-equiv'=>'X-UA-Compatible','content'=>'IE=edge,chrome=1')).
            $buffer .= $this->html->entreTags('title', Config::$CFG['NomeCliente']);
            $buffer .= $this->html->abreTagAberta('meta', array('name'=>'description','content'=>""));
            $buffer .= $this->html->abreTagAberta('meta', array('author'=>'description','content'=>""));
            $buffer .= $this->html->abreTagAberta('meta', array('name'=>'viewport','content'=>"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"));
            
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'stylesheet','type'=>'text/css','media'=>'screen','href'=> URL_BASE_STATIC . 'css/bootstrap.min.css'));
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'stylesheet','type'=>'text/css','media'=>'screen','href'=> URL_BASE_STATIC . 'css/font-awesome.min.css'));
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'stylesheet','type'=>'text/css','media'=>'screen','href'=> URL_BASE_STATIC . 'css/smartadmin-production.min.css'));
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'stylesheet','type'=>'text/css','media'=>'screen','href'=> URL_BASE_STATIC . 'css/smartadmin-skins.min.css'));
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'stylesheet','type'=>'text/css','media'=>'screen','href'=> URL_BASE_STATIC . 'css/smartadmin-rtl.min.css'));
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'stylesheet','type'=>'text/css','media'=>'screen','href'=> URL_BASE_STATIC . 'css/my.css'));
            
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'shortcut icon','href'=> URL_BASE_STATIC . 'img/favicon/favicon.ico','type'=>'image/x-icon'));
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'icon','href'=> URL_BASE_STATIC . 'img/favicon/favicon.ico','type'=>'image/x-icon'));
            
            //$buffer .= $this->css->abreTagAberta('link', array('rel'=>'stylesheet','href'=>'http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700'));
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'stylesheet','href'=> URL_BASE_STATIC . 'fonts/fonts.css'));
            
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'apple-touch-icon','href'=> URL_BASE_STATIC . 'img/splash/sptouch-icon-iphone.png'));
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'apple-touch-icon','sizes'=>'76x76','href'=> URL_BASE_STATIC . 'img/splash/touch-icon-ipad.png'));
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'apple-touch-icon','sizes'=>'120x120','href'=> URL_BASE_STATIC . 'img/splash/touch-icon-iphone-retina.png'));
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'apple-touch-icon','sizes'=>'152x152','href'=> URL_BASE_STATIC . 'img/splash/touch-icon-ipad-retina.png'));
                        
            $buffer .= $this->html->abreTagAberta('meta', array('name'=>'apple-mobile-web-app-capable','content'=>"yes"));
            $buffer .= $this->html->abreTagAberta('meta', array('name'=>'apple-mobile-web-app-status-bar-style','content'=>"black"));
            
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'apple-touch-startup-image','href'=> URL_BASE_STATIC . 'img/splash/ipad-landscape.png','media'=>'screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)'));
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'apple-touch-startup-image','href'=> URL_BASE_STATIC . 'img/splash/ipad-portrait.png','media'=>'screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)'));
            $buffer .= $this->css->abreTagAberta('link', array('rel'=>'apple-touch-startup-image','href'=> URL_BASE_STATIC . 'img/splash/iphone.png','media'=>'screen and (max-device-width: 320px)'));
            
        $buffer .= $this->html->fechaTag('head');               
        
        return $buffer;
    }
    
    public function menu()
    {
        
    }
    
    public function rodape()
    {
        return '<div class="page-footer">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<span class="txt-color-white">SmartAdmin WebApp © 2013-2014</span>
				</div>

				<div class="col-xs-6 col-sm-6 text-right hidden-xs">
					<div class="txt-color-white inline-block">
						<i class="txt-color-blueLight hidden-mobile">Last account activity <i class="fa fa-clock-o"></i> <strong>52 mins ago &nbsp;</strong> </i>
						<div class="btn-group dropup">
							<button class="btn btn-xs dropdown-toggle bg-color-blue txt-color-white" data-toggle="dropdown">
								<i class="fa fa-link"></i> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu pull-right text-left">
								<li>
									<div class="padding-5">
										<p class="txt-color-darken font-sm no-margin">Download Progress</p>
										<div class="progress progress-micro no-margin">
											<div class="progress-bar progress-bar-success" style="width: 50%;"></div>
										</div>
									</div>
								</li>
								<li class="divider"></li>
								<li>
									<div class="padding-5">
										<p class="txt-color-darken font-sm no-margin">Server Load</p>
										<div class="progress progress-micro no-margin">
											<div class="progress-bar progress-bar-success" style="width: 20%;"></div>
										</div>
									</div>
								</li>
								<li class="divider"></li>
								<li>
									<div class="padding-5">
										<p class="txt-color-darken font-sm no-margin">Memory Load <span class="text-danger">*critical*</span></p>
										<div class="progress progress-micro no-margin">
											<div class="progress-bar progress-bar-danger" style="width: 70%;"></div>
										</div>
									</div>
								</li>
								<li class="divider"></li>
								<li>
									<div class="padding-5">
										<button class="btn btn-block btn-default">refresh</button>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>';
    }
}
