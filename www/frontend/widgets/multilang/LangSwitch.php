<?php 
namespace frontend\widgets\multilang;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class LangSwitch extends Widget
{
    public $lang = 'en-EN';
	public $icon = false;
	public $type = 1;
	public $tag;
	public $visible = false;
	public $data_placement = 'left';
	public $used_tooltip = false;
	
    public function init()
    {
		parent::init();
		
		if (empty($this->visible)) {
			return false;
		}
		
		$array_lang = [];
		
		if (!empty(Yii::$app->params['lang']) && is_array(Yii::$app->params['lang'])) {
			$array_lang = Yii::$app->params['lang'];
		}
		
		$count_lang = count($array_lang);

		if ($count_lang<2) {
			return false;
		}

		$current_lang = $this->lang;

		if (empty($current_lang)) {
			return false;
		} elseif (is_object($current_lang)) {
			$current_lang = $current_lang->value;
		}

		$path = $this->langSettings($current_lang, 1);

		if (empty($path)) {
			return false;
		}

		$name = $this->langSettings($current_lang, 2);
		
		if (empty($name)) {
			return false;
		}

		if ($this->type==1 || $this->type==3) {
		
			if (!empty($this->used_tooltip)) {
				
				$type_link = Html::img($path, [
					'class'=>'fa_menu tips',
					'data-toggle'=>'tooltip',
					'data-placement'=>$this->data_placement,
					'title'=>$name,
				]);

			} else {

				$type_link = Html::img($path, []);
				
			}

		} elseif ($this->type==2) {
			
			$type_link = $name;

		} else {
			return false;
		}

		$link = '<li class="has-submenu parent-parent-menu-item">';

		$link .= Html::tag('a', $type_link, [
			'class'=>'btn btn-lang pull-left', 
			'aria-hidden'=>'true', 
			'href'=>'#', 
			'onclick'=>'return false',
			'data-toggle'=>'dropdown',
		]);
		
		$lang_menu = '<ul class="submenu minimenu" id="switcher_menu">';
				
		foreach ($array_lang as $v) {
						
			if ($v==$current_lang) {
				continue;
			}
			
			$path_sw = $this->langSettings($v, 1);
		
			if (empty($path_sw)) {
				continue;
			}

			$name_sw = $this->langSettings($v, 2);
		
			if (empty($name_sw)) {
				continue;
			}

			if ($this->type==1) {
		
				$type_link_sw = Html::img($path_sw, [
					'title'=>$name_sw,
				]);
				
				$type_link_sw .= ' '.$name_sw;
		
			} elseif ($this->type==2) {
				
				$type_link_sw = $name_sw;
				
			} elseif ($this->type==3) {
				
				$type_link_sw = Html::img($path_sw, [
					'title'=>$name_sw,
				]);
				
			}
			
			$lang_menu .= '<li>';
			
			$lang_menu .= Html::tag('a', $type_link_sw, [
				'class'=>'btn btn-link pull-left', 
				'aria-hidden'=>'true', 
				'href'=>'#', 
				'onclick'=>'return false',
				//'title'=>$name_sw,
				'data-lang'=>$v,
			]);
			
			$lang_menu .= '</li>';
			
		}	
			
		$lang_menu .= '</ul>';	
		$lang_menu .= '</li>';

		if (!empty($this->tag)) {
		
			$link_view = '<'.$this->tag.'>';
			
			$link_view .= $link;
			
			$link_view .= $lang_menu;
			
			$link_view .= '</'.$this->tag.'>';
		
		} else {
			
			$link_view = $link;
			
			$link_view .= $lang_menu;
			
		}

		$this->icon = $link_view;
    }
	
	private function langSettings($lg, $type)
	{
		if (empty($lg) || empty($type)) {
			return false;
		}

		$data = [
			'ru-RU'=>[
				'flag'=>'/images/flags/ru-RU.png',
				'name'=>'Русский',
			],
			'en-EN'=>[
				'flag'=>'/images/flags/en-EN.png',
				'name'=>'English',
			],
		];

		if (@array_key_exists($lg , $data)) {
			
			if ($type == 1) {
			
				return $data[$lg]['flag'];
			
			} elseif ($type == 2) {
			
				return $data[$lg]['name'];
			
			} 
		} 

		return false;	
	}
	
    public function run()
    {
		if (!empty($this->icon)) {
			
			$this->view->registerCss('
				#switcher_menu{min-width:10px;}
				#switcher_menu>li>a{color: #9d9d9d !important;}
				#switcher_menu>li>a:focus,
				#switcher_menu>li>a:hover{background:transparent;color: #333333 !important;}
			');
			
			$this->view->registerJs('
				jQuery("#switcher_menu>li>a").on("click", function () {
					var lang = $(this).attr("data-lang");
					var data = {};
					var param = "'.Yii::$app->request->csrfParam.'";
					var token = "'.Yii::$app->request->getCsrfToken().'";
					data[param] = token;
					data["lang"] = lang;
	
					$.ajax({
						type: "POST",
						url: "/api/lang",
						data: data,
						success: function(d){ 
							if (d) {
								location.reload();
							}
						}
					});
				});
			', yii\web\View::POS_END); 
			
		}
		
		return $this->icon;
    }
}