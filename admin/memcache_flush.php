<?php
if($memcache_handle){
	$result = $memcache_handle->flush();
	if($result){
		admin_tip(1, 'memcache已经成功清空！');
	}else{
		admin_tip(0, 'memcache清空失败！');
	}
}else{
	admin_tip(0, '系统暂不支持memcache！');
}
?>