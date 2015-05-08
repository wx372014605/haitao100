<?php
/**
 * 网站通用分页类
 * @author zjq
 * @version 1.1
 */
require_once ('mysql.php');

class Paging{
	//默认配置
	private $record_count = 1;//总的记录条数
	public $page_size = 10;//每页显示的数据条数
	public $page = 1;//当前页数
	public $page_num = 5;//最多显示的分页数字个数
	private $page_count = 1;//总页数
	public $first_page = "首页";//首页文字
	public $show_first = true;//是否显示首页
	public $pre_page = "上一页";//上一页文字
	public $show_pre = true;//是否显示上一页
	public $next_page = "下一页";//下一页文字
	public $show_next = true;//是否显示下一页
	public $last_page = "尾页";//尾页文字
	public $show_last = true;//是否显示尾页
	public $show_num = true;//是否显示数字页数
	public $show_tip = true;//是否显示页数提示
	private $mysql_handle;//数据库连接句柄
	
	//初始化
	function __construct(){
		global $mysql_handle;
		$this->mysql_handle = $mysql_handle;
		//获取当前页(页数不能小于1)
		$this->page = isset($_GET["page"])?intval($_GET["page"]):1;
		if($this->page<1){
			$this->page = 1;
		}
	}
	
	//获取url参数
	function getUrlParameter($str){
		$arr = explode("=", $str);//按=号切分
		$parameter_name = $arr[0];//获取当前的参数名称
		$parameter_url=$_SERVER['QUERY_STRING'];
		//判断原来的参数里面是否存在该参数
		if(strpos($parameter_url, $parameter_name)===false){
			if($parameter_url==""){
				$parameter_url .= $str;
			}else{
				$parameter_url .= "&".$str;
			}
		}else{
			$parameter_url = preg_replace("/$parameter_name=\d+/", $str, $parameter_url);
		}
		return $parameter_url;
	}
	
	//获取分页之后的数据记录,参数：数据库语句
    function get_record($sql){
		//获取记录总数
		//$count_sql = "select count(1) as record_count ".stristr($sql,"from");
		//消除字段中带from字符产生的bug
		$count_sql = preg_replace("/^[\s\S]*?[\s\r\n]from[\s\r\n]/i", "select count(1) as record_count from ", $sql);
		$count_arr = $this->mysql_handle->getRow($count_sql);
		$this->record_count = intval($count_arr['record_count']);
		//获取总页数
		$this->page_count = ceil($this->record_count/$this->page_size);
		if($this->page_count<1){
			$this->page_count = 1;
		}
		//当前页不能大于总页数
		$this->page = $this->page > $this->page_count ? $this->page_count : $this->page;
		//获取分页的数据库语句
		$sql .= " limit ".($this->page-1)*$this->page_size.",".$this->page_size;
		//返回查询结果
		$result_arr = $this->mysql_handle->getRs($sql);
		return $result_arr;
	}
	
	//获取总的页数
	function get_page_count(){
		return $this->page_count;
	}
	
	//获取总的记录条数
	function get_record_count(){
		return $this->record_count;
	}
	
	//获取分页html代码
	function get_page(){
		$page_html = '';
		$step = floatval($this->page_num)/2;//显示页数的一半，用作程序判断
		$pre = ($this->page-1)>0?($this->page-1):1;//上一页
		$next = ($this->page+1)<$this->page_count?($this->page+1):$this->page_count;//下一页
		
		/****分页容器****/
		$page_html .= '<div id="page_main">';
		
		/****首页、上一页****/
		if($this->page<=1){
			if($this->show_first){
				$page_html .= '<span class="first_page"><i></i>'.$this->first_page.'</span>';
			}
			if($this->show_pre){
				$page_html .= '<span class="pre_page"><i></i>'.$this->pre_page.'</span>';
			}
		}else{
			if($this->show_first){
				$page_html .= '<a id="first_page" class="first_page" href="?'.$this->getUrlParameter('page=1').'" page="'.$pre.'"><i></i>'.$this->first_page.'</a>';
			}
			if($this->show_pre){
				$page_html .= '<a id="pre_page" class="pre_page" href="?'.$this->getUrlParameter('page='.$pre).'" page="'.$pre.'"><i></i>'.$this->pre_page.'</a>';
			}
		}
		
		/****分页数字****/
		if($this->show_num){
			if($this->page<=$step){
				$page_end = min($this->page_num,$this->page_count);
				for($i=1;$i<=$page_end;$i++){
					if($i==$this->page){
						$page_html .= '<span id="current_page">'.$i.'</span>';
					}else{
						$page_html .= '<a href="?'.$this->getUrlParameter('page='.$i).'" page="'.$i.'" class="page_num">'.$i.'</a>';
					}
				}
			}else if($this->page>=$this->page_count-$step){
				$page_start = max($this->page_count-$this->page_num+1,1);
				for($i=$page_start;$i<=$this->page_count;$i++){
					if($i==$this->page){
						$page_html .= '<span id="current_page">'.$i.'</span>';
					}else{
						$page_html .= '<a href="?'.$this->getUrlParameter('page='.$i).'" page="'.$i.'" class="page_num">'.$i.'</a>';
					}
				}
			}else{
				$page_start = floor($this->page-$step+1);
				$page_end = $page_start + $this->page_num;
				for($i=$page_start;$i<$page_end;$i++){
					if($i==$this->page){
						$page_html .= '<span id="current_page">'.$i.'</span>';
					}else{
						$page_html .= '<a href="?'.$this->getUrlParameter('page='.$i).'" page="'.$i.'" class="page_num">'.$i.'</a>';
					}
				}
			}
		}
		/****分页数字****/
		
		/****尾页、下一页****/
		if($this->page>=$this->page_count){
			if($this->show_next){
				$page_html .= '<span class="next_page"><i></i>'.$this->next_page.'</span>';
			}
			if($this->show_last){
				$page_html .= '<span class="last_page"><i></i>'.$this->last_page.'</span>';
			}
		}else{
			if($this->show_next){
				$page_html .= '<a id="next_page" class="next_page" href="?'.$this->getUrlParameter('page='.$next).'" page="'.$next.'"><i></i>'.$this->next_page.'</a>';
			}
			if($this->show_last){
				$page_html .= '<a id="last_page" class="last_page" href="?'.$this->getUrlParameter('page='.$this->page_count).'" page="'.$this->page_count.'"><i></i>'.$this->last_page.'</a>';
			}
		}
		
		/****页数提示****/
		if($this->show_tip){
			$page_html .= '<span id="page_tip">第<font class="now_page">'.$this->page.'</font>/<font class="totle_page">'.$this->page_count.'</font>页</span>';
		}
		
		$page_html .= '</div>';
		return $page_html;
	}
}
?>