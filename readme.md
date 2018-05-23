
1、composer update 
2、.env數據庫配置 ，加上：
		
  API_STANDARDS_TREE=vnd

	API_PREFIX=api	

  API_VERSION=v1
		
  API_DEBUG=true

4、php artisan key:generate 

功能：

登錄、註冊、登出、獲取個人資料、修改個人資料api,  swagger












Laravel

```
ALTER TABLE `member` MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
```
