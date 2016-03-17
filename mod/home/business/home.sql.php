<?php
   $sql['get_menu_list'] = "
      SELECT 
			menuId AS id, 
			displayMenu AS name
		FROM 
			menu_language 
		WHERE 
			languageId = '%s'
   ";

   $sql['get_count']="
      SELECT 
         count(a.infoId) as total
      FROM info_detail a
      WHERE 
         languageId = '%s'
      AND 
         menuId = '%s'
      AND 
         MONTH(dateChange) like '%%%s%%'
      AND 
         YEAR(dateChange) = '%s'
   ";
	
	$sql['get_list']="
		SELECT 
			a.infoId,
			LOWER(postTitle) AS infoTitleUrl,
			a.infoTitle, 
         infoDetail,
			date_format(a.dateInsert,'%%W, %%d %%M %%Y %%H:%%i') as dateInsert,
			date_format(a.dateChange,'%%W, %%d %%M %%Y %%H:%%i') as dateChange,
			b.realName
		FROM info_detail a
		LEFT JOIN user b ON a.userId = b.userId
		WHERE 
			languageId = '%s'
		AND 
			menuId = '%s'
		AND 
			MONTH(dateChange) like '%%%s%%'
		AND 
			YEAR(dateChange) = '%s'
		ORDER BY a.dateChange DESC
      LIMIT %s, %s
	";
	
	$sql['get_link'] = "
		SELECT a.year, a.month, a.count, b.infoId, b.postTitle, b.infoTitle FROM (SELECT 
			YEAR(dateChange) AS year,
			MONTH(dateChange) AS month,
			count(infoId) AS count
		FROM info_detail
		GROUP BY 
		MONTH(dateChange)
		ORDER BY dateChange DESC
		) a LEFT JOIN 
		(SELECT  YEAR(dateChange) AS year,
			MONTH(dateChange) AS month,
			infoTitle,
			postTitle,
			infoId
		FROM 
			info_detail 
		WHERE 
			MONTH(dateChange) = '%s'
		AND 
			YEAR(dateChange) = '%s'
		AND 
			menuId = '%s'
		ORDER BY dateChange DESC
		) b ON a.year = b.year AND a.month = b.month
	";
	
	$sql['get_last_month_year'] = "
		SELECT 
			MONTH(dateChange) AS month, 
			YEAR(dateChange) AS year 
		FROM info_detail 
		%s
		ORDER BY dateChange 
		DESC LIMIT 0,1
	";
	
	$sql['get_url'] = "
		SELECT 
			`name`,
			`url`
		FROM 
			links
		ORDER BY `name`
	";
?>
