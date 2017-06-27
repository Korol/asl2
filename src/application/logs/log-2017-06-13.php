<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2017-06-13 11:21:57 --> 404 Page Not Found: Faviconico/index
ERROR - 2017-06-13 17:02:16 --> 404 Page Not Found: Faviconico/index
ERROR - 2017-06-13 17:02:17 --> 404 Page Not Found: Faviconico/index
ERROR - 2017-06-13 17:02:17 --> 404 Page Not Found: Faviconico/index
ERROR - 2017-06-13 17:02:17 --> 404 Page Not Found: Faviconico/index
ERROR - 2017-06-13 17:02:32 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-06-13' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-06-13 17:02:54 --> 2017-06
ERROR - 2017-06-13 17:03:13 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-06-13' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-06-13 17:12:36 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-06-13' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
