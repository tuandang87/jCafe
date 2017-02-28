-- phpMyAdmin SQL Dump
-- version 4.4.15
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 28, 2017 at 07:00 AM
-- Server version: 5.6.34
-- PHP Version: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `codewnet_jcafet`
--

-- --------------------------------------------------------

--
-- Table structure for table `Categories`
--

CREATE TABLE IF NOT EXISTS `Categories` (
  `Id` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Image` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Categories`
--

INSERT INTO `Categories` (`Id`, `Name`, `Image`) VALUES
(1, 'Gà', 'categorie/undefined_1475595808.jpg'),
(2, 'Heo', 'categorie/undefined_1475595825.jpg'),
(3, 'Tôm', 'categorie/undefined_1475595864.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `DesksStatus`
--

CREATE TABLE IF NOT EXISTS `DesksStatus` (
  `Id` int(11) NOT NULL,
  `Session` int(11) NOT NULL,
  `Status` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `DesksStatus`
--

INSERT INTO `DesksStatus` (`Id`, `Session`, `Status`) VALUES
(1, 153, '{"0":12,"1":17,"2":5,"3":7,"4":0,"5":5,"6":5,"7":2,"8":0,"9":0,"10":0,"11":0,"12":0,"13":0,"14":0,"15":0,"16":3,"17":1,"18":0,"19":0,"20":0,"21":0,"22":0,"23":3,"24":0,"25":0,"26":0,"27":0,"28":0,"29":0,"30":0,"31":0,"32":3,"33":10,"34":0,"35":2,"36":0,"37":0,"38":1,"39":0,"40":0,"41":0,"42":0,"43":0,"44":0,"45":0,"46":0,"47":0,"48":0,"49":0,"50":5,"51":0,"52":0,"53":0,"54":0,"55":0,"56":0,"57":0,"58":0,"59":0,"60":0,"61":0,"62":0,"63":0,"64":0,"65":0,"66":0,"67":0,"68":0,"69":0,"70":0,"71":0,"72":0,"73":0,"74":0,"75":0,"76":0,"77":0,"78":0,"79":0,"80":0,"82":0,"83":0,"84":0,"85":0,"86":0,"87":0,"88":0,"89":0,"90":0,"91":0,"92":0,"93":0,"94":0,"95":0,"96":0,"97":0,"98":0,"99":0,"100":0,"101":0,"102":0,"103":5,"104":0,"105":0,"106":0,"107":0,"108":0,"109":0,"110":0,"111":0,"112":0,"113":0,"114":0,"115":0,"116":0,"117":0,"118":0,"119":0,"120":0,"121":0,"122":0,"123":0,"124":0,"125":0,"126":0,"127":0,"128":0,"129":0,"130":0,"131":0,"132":0,"133":0,"134":0,"135":0,"136":0,"137":0,"138":0,"139":0,"140":0,"141":0,"142":0,"143":0,"144":0,"145":0,"146":0,"147":0,"148":0,"149":0,"150":0,"151":0,"152":0,"153":0,"154":0,"155":0,"156":0,"157":0,"158":0,"159":0,"160":0,"161":0,"162":0,"163":0,"164":0,"165":0,"166":0,"167":0,"168":0,"169":0,"170":0,"171":0,"172":0,"173":0,"174":0,"175":0,"176":0,"177":0,"178":0,"179":0,"180":0,"181":0,"182":0,"183":0,"184":0,"185":0,"186":0,"187":0,"188":0,"189":0,"190":0,"191":0,"192":0,"193":0,"194":0,"195":0,"196":0,"197":0,"198":0,"199":0,"200":0}');

-- --------------------------------------------------------

--
-- Table structure for table `Employees`
--

CREATE TABLE IF NOT EXISTS `Employees` (
  `Id` int(11) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(256) NOT NULL DEFAULT '123',
  `AccessLevel` int(11) NOT NULL DEFAULT '0',
  `RegionNo` int(11) NOT NULL DEFAULT '0',
  `FirstName` varchar(10) DEFAULT NULL,
  `LastName` varchar(10) DEFAULT NULL,
  `Image` varchar(100) NOT NULL DEFAULT 'employee.png',
  `Email` varchar(50) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Birthday` date DEFAULT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `DesksOfEmp` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Employees`
--

INSERT INTO `Employees` (`Id`, `Username`, `Password`, `AccessLevel`, `RegionNo`, `FirstName`, `LastName`, `Image`, `Email`, `Phone`, `Birthday`, `Address`, `DesksOfEmp`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 1, 0, '', '', 'admin.png', '', '', NULL, 'Sao hỏa', '{"0":12,"1":17,"2":5,"3":7,"4":0,"5":5,"6":5,"7":2,"8":0,"9":0,"10":0,"11":0,"12":0,"13":0,"14":0,"15":0,"16":3,"17":1,"18":0,"19":0,"20":0,"21":0,"22":0,"23":3,"24":0,"25":0,"26":0,"27":0,"28":0,"29":0,"30":0,"31":0,"32":3,"33":10,"34":0,"35":2,"36":0,"37":0,"38":1,"39":0,"40":0,"41":0,"42":0,"43":0,"44":0,"45":0,"46":0,"47":0,"48":0,"49":0,"50":5}'),
(11, 'thungan', 'e10adc3949ba59abbe56e057f20f883e', 4, 0, 'Phụ', 'Thu', 'user/thungan_1471069219.png', 'phuthu@gmail.com', '098213131287', '1990-01-01', 'Địa', ''),
(12, 'daubep', 'e10adc3949ba59abbe56e057f20f883e', 5, 0, 'Pha', 'Chế', 'user/phache_1471069229.png', 'phache@gmail.com', '031231243', '1990-01-01', 'Đàng', '{"0":12,"1":17,"2":5,"3":7,"4":0,"5":5,"6":5,"7":0,"8":0,"9":0,"10":0,"11":0,"12":0,"13":0,"14":0,"15":0,"16":0,"17":0,"18":0,"19":0,"20":0,"21":0,"22":0,"23":0,"24":0,"25":0,"26":0,"27":0,"28":0,"29":0,"30":0,"31":0,"32":3,"33":8,"34":0,"35":2,"36":0,"37":0,"38":1,"39":0,"40":0,"41":0,"42":0,"43":0,"44":0,"45":0,"46":0,"47":0,"48":0,"49":0,"50":5}'),
(13, 'nguyen', 'e10adc3949ba59abbe56e057f20f883e', 2, 0, '', '', 'user/nguyen_1468843254.jpg', '', '', '1990-01-01', '', ''),
(15, 'nhanvien', 'e10adc3949ba59abbe56e057f20f883e', 3, 0, '', '', 'user/nhanvien_1468910267.png', '', '', '1990-01-01', '', '{"0":12}'),
(16, 'pv', 'e10adc3949ba59abbe56e057f20f883e', 5, 0, 'pv', 'pv', 'user/pv_1468970265.png', 'pv', 'pv', '1990-01-01', 'pv', '{"0":44,"1":24,"2":32,"3":24,"4":18,"5":17,"6":21,"7":8,"8":1,"9":12,"10":19,"11":16,"12":14,"13":18,"14":17,"15":6,"16":12,"17":5,"18":0,"19":6,"20":6,"21":7,"22":9,"23":0,"24":9,"25":0,"26":12,"27":0,"28":0,"29":5,"30":0,"31":25,"32":0,"33":0,"34":9,"35":0,"36":9,"37":0,"38":1,"39":0,"40":0,"41":0,"42":0,"43":0,"44":0,"45":0,"46":0,"47":0,"48":0,"49":0,"50":0,"51":7,"52":4,"53":5,"54":3,"55":1,"56":9,"57":3,"58":10,"59":7,"60":3,"61":4,"62":8,"63":8,"64":4,"65":1,"66":1,"67":2,"68":3,"69":0,"70":6,"71":6,"72":0,"73":1,"74":1,"75":1,"76":1,"77":0,"78":0,"79":1,"80":0,"81":0,"82":0,"83":0,"84":6,"85":0,"86":0,"87":1,"88":5,"89":3,"90":6,"91":3,"92":4,"93":6,"94":7,"95":2,"96":4,"97":5,"98":0,"99":0,"100":0,"101":0,"102":0,"103":5,"104":0,"105":0,"106":0,"107":0,"108":0,"109":0,"110":0,"111":0,"112":0,"113":0,"114":0,"115":0,"116":0,"117":0,"118":0,"119":0,"120":0,"121":0,"122":0,"123":0,"124":0,"125":0,"126":0,"127":0,"128":0,"129":0,"130":0,"131":0,"132":0,"133":0,"134":0,"135":0,"136":0,"137":0,"138":0,"139":0,"140":0,"141":0,"142":0,"143":0,"144":0,"145":0,"146":0,"147":0,"148":0,"149":0,"150":0,"151":0,"152":0,"153":0,"154":0,"155":0,"156":0,"157":0,"158":0,"159":0,"160":0,"161":0,"162":0,"163":0,"164":0,"165":0,"166":0,"167":0,"168":0,"169":0,"170":0,"171":0,"172":0,"173":0,"174":0,"175":0,"176":0,"177":0,"178":0,"179":0,"180":0,"181":0,"182":0,"183":0,"184":0,"185":0,"186":0,"187":0,"188":0,"189":0,"190":0,"191":0,"192":0,"193":0,"194":0,"195":0,"196":0,"197":0,"198":0,"199":0,"200":0}'),
(17, 'quanly', 'c8837b23ff8aaa8a2dde915473ce0991', 2, 0, '', '', 'user/quanly_1468986127.jpg', '', '', '1990-01-01', '', ''),
(18, 'nv1', 'e10adc3949ba59abbe56e057f20f883e', 3, 1, '', '', 'noavatar.png', '', '', '1990-01-01', '', '{"0": 0,"1": 0,"2": 0,"3": 0,"4": 0,"5": 0,"6": 0,"7": 0,"8": 0,"9": 0,"10": 0,"11": 0,"12": 0,"13": 0,"14": 0,"15": 0,"16": 0,"17": 0,"18": 0,"19": 0,"20": 0,"21": 0,"22": 0,"23": 0,"24": 0,"25": 0,"26": 0,"27": 0,"28": 0,"29": 0,"30": 0,"31": 0,"32": 0,"33": 0,"34": 0,"35": 0,"36": 0,"37": 0,"38": 0,"39": 0,"40": 0,"41": 0,"42": 0,"43": 0,"44": 0,"45": 0,"46": 0,"47": 0,"48": 0,"49": 0,"50": 0}'),
(19, 'nv2', 'e10adc3949ba59abbe56e057f20f883e', 3, 2, '', '', 'noavatar.png', '', '', '1990-01-01', '', '{"51":7,"52":4,"53":5,"54":3,"55":1,"56":9,"57":3,"58":10,"59":7,"60":3,"61":4,"62":8,"63":8,"64":4,"65":1,"66":1,"67":2,"68":3,"69":0,"70":6,"71":6,"72":0,"73":1,"74":1,"75":1,"76":1,"77":0,"78":0,"79":1,"80":0,"81":0,"82":0,"83":0,"84":6,"85":0,"86":0,"87":1,"88":5,"89":3,"90":6,"91":3,"92":4,"93":6,"94":7,"95":2,"96":4,"97":5,"98":0,"99":0,"100":0}'),
(20, 'nv3', 'e10adc3949ba59abbe56e057f20f883e', 3, 3, '', '', 'noavatar.png', '', '', '1990-01-01', '', '{"101":0,"102":0,"103":5,"104":0,"105":0,"106":0,"107":0,"108":0,"109":0,"110":0,"111":0,"112":0,"113":0,"114":0,"115":0,"116":0,"117":0,"118":0,"119":0,"120":0,"121":0,"122":0,"123":0,"124":0,"125":0,"126":0,"127":0,"128":0,"129":0,"130":0,"131":0,"132":0,"133":0,"134":0,"135":0,"136":0,"137":0,"138":0,"139":0,"140":0,"141":0,"142":0,"143":0,"144":0,"145":0,"146":0,"147":0,"148":0,"149":0,"150":0}'),
(21, 'nv4', 'e10adc3949ba59abbe56e057f20f883e', 3, 0, '', '', 'noavatar.png', '', '', '1990-01-01', '', ''),
(22, 'a', 'e10adc3949ba59abbe56e057f20f883e', 3, 0, 'a', 'a', 'noavatar.png', 'aa', 'a', '0000-00-00', 'a', '');

-- --------------------------------------------------------

--
-- Table structure for table `OrderItems`
--

CREATE TABLE IF NOT EXISTS `OrderItems` (
  `Id` int(11) NOT NULL,
  `ReceiptId` int(11) NOT NULL DEFAULT '0',
  `Date` datetime DEFAULT NULL,
  `ProductId` int(11) NOT NULL DEFAULT '0',
  `Price` double NOT NULL DEFAULT '0',
  `Quantity` int(11) NOT NULL DEFAULT '0',
  `Amount` double NOT NULL DEFAULT '0',
  `IsServed` tinyint(1) NOT NULL DEFAULT '0',
  `ServedTime` datetime NOT NULL DEFAULT '2016-01-01 00:00:00',
  `IsUpdated` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OrderItems`
--

INSERT INTO `OrderItems` (`Id`, `ReceiptId`, `Date`, `ProductId`, `Price`, `Quantity`, `Amount`, `IsServed`, `ServedTime`, `IsUpdated`) VALUES
(3, 3, '2016-10-06 00:02:57', 1, 120000, 1, 120000, 1, '2016-10-06 00:03:59', 0),
(4, 3, '2016-10-06 00:02:57', 2, 70000, 1, 70000, 1, '2016-10-06 00:04:00', 0),
(5, 4, '2016-10-06 00:03:04', 3, 140000, 1, 140000, 1, '2016-10-06 00:04:05', 0),
(6, 4, '2016-10-06 00:03:04', 4, 80000, 1, 80000, 1, '2016-10-06 00:04:05', 0),
(7, 5, '2016-10-06 00:03:22', 1, 120000, 2, 240000, 1, '2016-10-06 00:04:10', 0),
(8, 5, '2016-10-06 00:03:22', 3, 140000, 1, 140000, 1, '2016-10-06 00:04:10', 0),
(9, 6, '2016-10-06 07:43:54', 1, 120000, 1, 120000, 1, '2016-10-06 08:40:50', 0),
(10, 6, '2016-10-06 07:43:54', 2, 70000, 1, 70000, 1, '2016-10-06 08:40:50', 0),
(11, 7, '2016-10-06 08:41:10', 1, 120000, 1, 120000, 1, '2016-10-06 10:06:15', 0),
(12, 7, '2016-10-06 08:41:10', 2, 70000, 1, 70000, 1, '2016-10-06 09:48:31', 0),
(13, 7, '2016-10-06 08:41:10', 3, 140000, 1, 140000, 1, '2016-10-06 09:48:31', 0),
(14, 8, '2016-10-06 10:12:31', 1, 120000, 1, 120000, 1, '2016-10-06 10:12:44', 0),
(15, 8, '2016-10-06 10:12:31', 2, 70000, 2, 140000, 1, '2016-10-06 10:12:45', 0),
(16, 8, '2016-10-06 10:12:31', 3, 140000, 1, 140000, 1, '2016-10-06 10:12:45', 0),
(17, 9, '2016-10-06 10:13:11', 1, 120000, 1, 120000, 1, '2016-10-06 10:16:32', 0),
(18, 10, '2017-02-13 19:27:23', 1, 120000, 1, 120000, 1, '2017-02-14 01:09:32', 0),
(19, 11, '2017-02-14 01:06:17', 1, 120000, 1, 120000, 1, '2017-02-14 01:09:40', 0),
(20, 11, '2017-02-14 01:06:17', 2, 70000, 1, 70000, 1, '2017-02-14 01:09:40', 0),
(21, 11, '2017-02-14 01:06:17', 3, 140000, 2, 280000, 1, '2017-02-14 01:09:40', 0),
(22, 11, '2017-02-14 01:07:12', 1, 120000, 2, 240000, 1, '2017-02-14 01:09:40', 0),
(23, 12, '2017-02-15 19:27:28', 4, 80000, 2, 160000, 0, '2016-01-01 00:00:00', 0),
(24, 12, '2017-02-15 19:27:28', 13, 120000, 2, 240000, 0, '2016-01-01 00:00:00', 0),
(25, 12, '2017-02-15 19:27:28', 14, 200000, 1, 200000, 0, '2016-01-01 00:00:00', 0),
(26, 13, '2017-02-15 19:37:07', 5, 100000, 2, 200000, 0, '2016-01-01 00:00:00', 0),
(27, 14, '2017-02-22 15:30:50', 1, 120000, 1, 120000, 0, '2016-01-01 00:00:00', 0),
(28, 14, '2017-02-22 15:30:50', 2, 70000, 1, 70000, 0, '2016-01-01 00:00:00', 0),
(29, 14, '2017-02-22 15:30:50', 3, 140000, 1, 140000, 0, '2016-01-01 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Products`
--

CREATE TABLE IF NOT EXISTS `Products` (
  `Id` int(11) NOT NULL,
  `CategoryId` int(11) NOT NULL DEFAULT '1',
  `Name` varchar(100) NOT NULL,
  `Price` double NOT NULL DEFAULT '0',
  `Image` varchar(50) NOT NULL DEFAULT 'none_img',
  `IsFavorite` tinyint(1) NOT NULL DEFAULT '0',
  `Note` varchar(50) NOT NULL DEFAULT ' '
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Products`
--

INSERT INTO `Products` (`Id`, `CategoryId`, `Name`, `Price`, `Image`, `IsFavorite`, `Note`) VALUES
(1, 1, 'Đùi gà nướng', 120000, 'product/1ui-ga-nuong_1487265033.jpeg', 0, ' '),
(2, 1, 'Cơm đùi gà', 70000, 'product/1com-dui-ga_1487265076.jpeg', 0, ' '),
(3, 1, 'Đùi gà sốt chua', 140000, 'product/1ui-ga-sot-chua_1487265484.jpg', 0, ' '),
(4, 1, 'Cơm gà xối mỡ', 80000, 'product/nan_1475602490.jpg', 0, ' '),
(5, 1, 'Cơm đùi gà thơm', 100000, 'product/nan_1475602527.jpg', 0, ' '),
(6, 1, 'Gà chiên phô mai', 200000, 'product/nan_1475602554.jpg', 0, ' '),
(7, 1, 'Gà rán', 110000, 'product/nan_1475602597.jpg', 0, ' '),
(8, 2, 'Giả cầy lợn', 190000, 'product/0gia-cay-lon_1475602641.jpg', 0, ' '),
(9, 2, 'Heo sữa quây', 400000, 'categorie/undefined_1475595825.jpg', 0, ' '),
(10, 2, 'Thịt heo nướng', 120000, 'product/nan_1475602706.jpg', 0, ' '),
(11, 2, 'Thịt heo xiên', 180000, 'product/0thit-heo-xien_1475602738.jpg', 0, ' '),
(12, 2, 'Thịt heo luộc', 100000, 'product/nan_1475602782.jpg', 0, ' '),
(13, 3, 'Tôm càng sốt', 120000, 'product/nan_1475602817.jpg', 0, ' '),
(14, 3, 'Tôm hùm Canada', 200000, 'categorie/undefined_1475595864.jpg', 0, ' '),
(15, 3, 'Tôm ốp chảo', 250000, 'product/nan_1475602881.jpg', 0, ' '),
(16, 3, 'Tôm Rim', 90000, 'categorie/undefined_1475595864.jpg', 0, ' '),
(17, 2, 'Xúc xích heo', 200000, 'product/0xuc-xich-heo_1475602959.jpg', 0, ' ');

-- --------------------------------------------------------

--
-- Table structure for table `Receipts`
--

CREATE TABLE IF NOT EXISTS `Receipts` (
  `Id` int(11) NOT NULL,
  `DeskNo` smallint(11) unsigned NOT NULL DEFAULT '0',
  `EmployeeId` int(11) NOT NULL DEFAULT '1',
  `CheckOutEmpId` int(11) NOT NULL DEFAULT '0',
  `CheckInTime` datetime DEFAULT NULL,
  `CheckOutTime` datetime DEFAULT NULL,
  `SubTotal` double NOT NULL DEFAULT '0',
  `Tax` double NOT NULL DEFAULT '0',
  `Discount` double NOT NULL DEFAULT '0',
  `NumOfItems` int(11) NOT NULL DEFAULT '0',
  `ExtraPaidPerItem` double NOT NULL DEFAULT '0',
  `ExtraPaid` double NOT NULL DEFAULT '0',
  `Total` double NOT NULL DEFAULT '0',
  `Paid` double DEFAULT '0',
  `DueChange` double DEFAULT '0',
  `Status` int(11) NOT NULL DEFAULT '1' COMMENT '1: Ordered; 2:Completed; 3:Canceled;',
  `UpdatedTime` datetime DEFAULT '2016-01-01 00:00:00',
  `IsCompletedServing` tinyint(1) NOT NULL DEFAULT '0',
  `Note` varchar(150) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Receipts`
--

INSERT INTO `Receipts` (`Id`, `DeskNo`, `EmployeeId`, `CheckOutEmpId`, `CheckInTime`, `CheckOutTime`, `SubTotal`, `Tax`, `Discount`, `NumOfItems`, `ExtraPaidPerItem`, `ExtraPaid`, `Total`, `Paid`, `DueChange`, `Status`, `UpdatedTime`, `IsCompletedServing`, `Note`) VALUES
(3, 0, 1, 1, '2016-10-06 00:02:56', '2016-10-06 00:04:30', 190000, 0, 0, 2, 0, 0, 190000, 0, 0, 2, '2016-10-06 00:03:59', 1, ''),
(4, 2, 1, 1, '2016-10-06 00:03:04', '2016-10-06 00:04:34', 220000, 0, 0, 2, 0, 0, 220000, 0, 0, 2, '2016-10-06 00:04:05', 1, ''),
(5, 50, 1, 1, '2016-10-06 00:03:22', '2016-10-06 00:04:37', 380000, 0, 0, 3, 0, 0, 380000, 0, 0, 2, '2016-10-06 00:04:10', 1, ''),
(6, 0, 1, 1, '2016-10-06 07:43:53', '2016-10-06 08:40:58', 190000, 0, 0, 2, 0, 0, 190000, 0, 0, 2, '2016-10-06 08:40:50', 1, ''),
(7, 1, 1, 0, '2016-10-06 08:41:10', NULL, 330000, 0, 0, 3, 0, 0, 330000, 0, 0, 1, '2016-10-06 10:06:15', 1, ''),
(8, 3, 1, 1, '2016-10-06 10:12:31', '2016-10-06 10:12:57', 400000, 0, 0, 4, 0, 0, 400000, 0, 0, 2, '2016-10-06 10:12:45', 1, ''),
(9, 6, 1, 0, '2016-10-06 10:13:11', NULL, 120000, 0, 0, 1, 0, 0, 120000, 0, 0, 1, '2016-10-06 10:16:32', 1, ''),
(10, 32, 1, 0, '2017-02-13 19:27:23', NULL, 120000, 0, 0, 1, 0, 0, 120000, 0, 0, 1, '2017-02-14 01:09:32', 1, ''),
(11, 33, 1, 1, '2017-02-14 01:06:17', '2017-02-15 19:37:24', 710000, 0, 0, 6, 0, 0, 710000, 0, 0, 2, '2017-02-14 01:09:40', 1, ''),
(12, 23, 1, 0, '2017-02-15 19:27:28', NULL, 600000, 0, 0, 5, 0, 0, 600000, 0, 0, 1, '2016-01-01 00:00:00', 0, ''),
(13, 16, 1, 0, '2017-02-15 19:37:07', NULL, 200000, 0, 0, 2, 0, 0, 200000, 0, 0, 1, '2016-01-01 00:00:00', 0, ''),
(14, 7, 1, 0, '2017-02-22 15:30:50', NULL, 330000, 0, 0, 3, 0, 0, 330000, 0, 0, 1, '2016-01-01 00:00:00', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `Settings`
--

CREATE TABLE IF NOT EXISTS `Settings` (
  `Id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL DEFAULT 'Jerry Software Sulotion',
  `NumOfDesks` int(11) NOT NULL DEFAULT '20',
  `ExtraPaidPerItem` double NOT NULL DEFAULT '0',
  `Description` varchar(150) DEFAULT NULL,
  `Owner` varchar(30) NOT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `AccountInfo` varchar(150) DEFAULT NULL,
  `TaxCode` varchar(15) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Fax` varchar(15) DEFAULT NULL,
  `Email` varchar(100) NOT NULL DEFAULT 'admin@shopname.com',
  `Password` varchar(256) NOT NULL DEFAULT ' ',
  `SendReport` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Settings`
--

INSERT INTO `Settings` (`Id`, `Name`, `NumOfDesks`, `ExtraPaidPerItem`, `Description`, `Owner`, `Address`, `AccountInfo`, `TaxCode`, `Phone`, `Fax`, `Email`, `Password`, `SendReport`) VALUES
(1, 'Nguyên Res', 50, 0, '', '', 'Trà phú - Trà Bồng - Quảng Ngãi', '1000.511.711 Vietcomank Chi Nhánh Quảng Ngãi', '100321123', '0914977964', '0558123321', 'pdudctnguyen@gmail.com', 'd41d8cd98f00b204e9800998ecf8427e', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `DesksStatus`
--
ALTER TABLE `DesksStatus`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Employees`
--
ALTER TABLE `Employees`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `OrderItems`
--
ALTER TABLE `OrderItems`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Receipts`
--
ALTER TABLE `Receipts`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Categories`
--
ALTER TABLE `Categories`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `DesksStatus`
--
ALTER TABLE `DesksStatus`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `Employees`
--
ALTER TABLE `Employees`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `OrderItems`
--
ALTER TABLE `OrderItems`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `Products`
--
ALTER TABLE `Products`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `Receipts`
--
ALTER TABLE `Receipts`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
