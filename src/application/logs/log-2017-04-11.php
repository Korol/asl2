<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2017-04-11 16:53:50 --> 2017-04-11
ERROR - 2017-04-11 16:55:05 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 16:57:45 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 6 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 16:57:54 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 16:57:58 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 16:58:03 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 16:58:06 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 16:58:11 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 16:58:18 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 16:59:55 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:00:17 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:00:53 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:01:13 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:01:33 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:03:03 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-03' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:03:24 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-03' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:03:37 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-03' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:04:31 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:04:43 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-12' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:04:49 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-06' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:04:56 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-03' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:04:59 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-07-03' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:05:05 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-02-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:05:10 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:21:54 --> Query error: Unknown column 'c.Is.Deleted' in 'where clause' - Invalid query: SELECT es2c.ID as 'es2cID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es2c`.`EmployeeSiteID` = `es`.`ID` AND `es`.`IsDeleted` = 0 AND `es`.`EmployeeID`=25 AND `es`.`SiteID`=12
WHERE `c`.`Is`.`Deleted` =0
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:21:55 --> Query error: Unknown column 'c.Is.Deleted' in 'where clause' - Invalid query: UPDATE `ci_sessions` SET `timestamp` = 1491920515
WHERE `c`.`Is`.`Deleted` =0
AND `id` = '64d60d70e1534b5202a962ce3ccb7d67e5d3515c'
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:28:30 --> 2017-04
ERROR - 2017-04-11 17:28:43 --> 2017-05
ERROR - 2017-04-11 17:28:56 --> 2017-07
ERROR - 2017-04-11 17:29:02 --> 2017-03
ERROR - 2017-04-11 17:29:07 --> 2017-01
ERROR - 2017-04-11 17:29:12 --> 2017-04
ERROR - 2017-04-11 17:29:16 --> 2017-05
ERROR - 2017-04-11 17:29:19 --> 2017-04
ERROR - 2017-04-11 17:30:55 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:31:10 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-12' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:31:16 --> SELECT c.ID as 'CustomerID', `c`.`FName`, `c`.`SName`
FROM `assol_customer` AS `c`
INNER JOIN `assol_employee_site_customer` AS `es2c` ON `es2c`.`CustomerID` = `c`.`ID` AND `es2c`.`IsDeleted`=0
INNER JOIN `assol_employee_site` AS `es` ON `es`.`EmployeeID` = 25 AND `es`.`IsDeleted` = 0 AND `es2c`.`EmployeeSiteID` = `es`.`ID`
WHERE   (
`c`.`IsDeleted` =0
OR '2017-04-11' <= DATE(c.`DateRemove`)
 )
GROUP BY `c`.`ID`
ORDER BY `c`.`SName` ASC, `c`.`FName` ASC
ERROR - 2017-04-11 17:31:28 --> 2017-04
ERROR - 2017-04-11 17:31:34 --> 2017-01
ERROR - 2017-04-11 17:31:38 --> 2017-05
ERROR - 2017-04-11 17:31:42 --> 2017-07
ERROR - 2017-04-11 17:31:46 --> 2017-01
