<?php
if(is_writable(get_cache_path())){
	$result = empty_dir(get_cache_path());
	if($result){
		admin_tip(1, '文件缓存已经成功清空！');
	}else{
		admin_tip(0, '文件缓存清空失败！');
	}
}else{
	admin_tip(0, '系统暂不支持文件缓存！');
}
?>