-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2018 at 03:04 PM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(6) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `Visibility` tinyint(1) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(1) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `Name`, `Description`, `parent`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(1, 'Phones', 'All type of Phones  ', 0, 0, 0, 0),
(3, 'Men wear', 'all types of Clothes', 0, 0, 0, 0),
(4, 'Women Wear', '', 0, 0, 0, 0),
(5, 'shoes', '', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `com_ID` int(11) NOT NULL,
  `comment` text CHARACTER SET utf8 NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `Com_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `itemID` int(11) NOT NULL,
  `itmPhoto` varchar(255) NOT NULL,
  `itemName` varchar(255) NOT NULL,
  `descItem` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `item_date` date NOT NULL,
  `Image` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `Approve` tinyint(1) NOT NULL DEFAULT '0',
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`itemID`, `itmPhoto`, `itemName`, `descItem`, `Price`, `item_date`, `Image`, `status`, `Approve`, `Cat_ID`, `Member_ID`, `tags`) VALUES
(1, '95336_ADIDAS-ADD2604N-BLUE-SNEAKER.jpg', 'blue original Shoes ', 'Model 2018 New Shoes offer 50% sale', '350', '2018-11-13', '', '1', 1, 5, 1, 'offer, Model 2018, New'),
(2, '33863_04333776_zi_black.jpg', 'Classic Shoes', 'very good for toxic wear , like new \r\nPrice is non- Negotiable,\r\nContact Number :- 012 000 000 00\r\n ', '500', '2018-11-13', '', '2', 1, 5, 1, 'classic shoes'),
(3, '24496_04388514_zi_vintage_green_outdoor_green_metallic_field.jpg', ' Navy sneakers', 'color navy , Model 2018, used shoes , \r\nin a good condition ', '250', '2018-11-13', '', '3', 1, 5, 1, 'Model 2018'),
(4, '31234_adys700142_etribekase,p_bcm_frt2.jpg', 'black sneakers', 'Black sneakers like new ', '300', '2018-11-13', '', '2', 1, 5, 1, 'sinkers '),
(5, '49647_DP1116201719171540M.jpg', 'Nike sneakers', 'new model 2018 Nike Original ', '1000', '2018-11-13', '', '1', 1, 5, 1, 'Model 2018, new'),
(6, '71729_tp1023-7-foot-locker-blue-original-imaf6jsd5c8zzhvu.jpeg', 'light-blue Sneakers ', 'new model 2018  new , high copy shoes ', '400', '2018-11-13', '', '1', 1, 5, 1, 'Model 2018, new'),
(7, '29174_415G6HWFx7L._AC_US200_.jpg', 'Tunics Long Sleeve', '????Fabric: 35% cotton & 60% polyester & 5% spandex, The material is high elastic knitting and form fitting, very soft and gentle next to your skin\r\n', '766', '2018-11-13', '', '1', 1, 4, 1, 'women'),
(8, '929_51iIZfnTsGL._AC_US200_.jpg', 'Pullover Sweatshirt ', 'This is a super soft material. Wear with black leggings and boots for the perfect winter outfit.\r\nThis sweatershirts tops is lightweight, versatile styles and very fashionable for ladies\r\n', '398', '2018-11-13', '', '1', 1, 4, 1, 'women wear, newStyle'),
(9, '58856_41Ug2RnXBSL._AC_US200_.jpg', 'Famulily Women\'s', 'Fabric:Cotton Blend,lightweight and cool soft,comfortable to wear\r\nThis extra long tunic features long sleeve,turtleneck,patchwork pullovers,elbow patchs,loose over sized tops shirts\r\nLightweight and two tone color block hem on bottom and cuffsc with a cozy cowl neck and trendy suede elbow patchs\r\n', '400', '2018-11-13', '', '1', 1, 4, 1, 'Women\'s'),
(10, '77084_61LNa+W9t-L._AC_US200_.jpg', 'Hibelle Women\'s', 'Paisley Shirt: 94%polyester, 6%spandex. The fabric is very soft, lightweight and stretchy,easy to wear,no wrinkles.\r\n', '140', '2018-11-13', '', '1', 1, 4, 1, 'Women\'s'),
(11, '52594_41U6x-QEdCL._AC_US200_.jpg', 'XueYin Men\'s ', 'Imported, Designed and Produced by XueYin\'s garment factory\r\nXueYin Brand has registered and was protected by amazon. Trademarks:86853317. All rights been reserved.\r\n', '700', '2018-11-13', '', '1', 1, 3, 1, 'Model 2018, new, men\'s'),
(12, '54030_51++BjfpBpL._AC_US200_.jpg', 'Clearance ', 'Shirt Material:Polyester Fiber☆☆☆☆Men\'s Slim Fit Tuetle neck Long Sleeve Muscle Tee T-shirt Casual Tops Blouse Fashion Men Long Sleeve Printed Slim Fit Casual T-shirts Shirt TopMen\'s Long Sleeve Hipster Hip Hop Mens Casual Long Sleeve Shirt Business Slim Fit Shirt Printed Blouse Top Casual Long Sleeve Shirt Slim Fit Shirt V Neck Patchwork Blouse Top Men Long Sleeve Designed Lapel Cardigan Sweatshirt Tops Jacket Coat Outwear\r\n', '300', '2018-11-13', '', '1', 1, 3, 1, 'Model 2018, new,men\'s'),
(13, '67693_519310fXg4L._AC_US200_.jpg', 'XueYin Men\'s  ', 'XueYin Brand has registered and was protected by amazon. Trademarks:86853317. All rights been reserved.\r\n', '600', '2018-11-13', '', '1', 1, 3, 1, 'men\'s'),
(14, '80421_41U+Fucf26L._AC_US218_.jpg', 'Huawei Mate SE', 'With a 5.93” 18: 9 edge-to-edge all-screen design, high screen-to-body ratio and 2160 x 1080 FHD+ resolution, the HUAWEI FullView display on the Huawei mate SE brings you an immersive visual experience\r\n', '3000', '2018-11-13', '', '2', 1, 1, 1, 'Huawei, '),
(17, '94139_41pchcdpyNL._AC_US218_.jpg', 'Essential Phone', 'The Essential Phone is expertly crafted using titanium and ceramic, with an edge-to-edge Full Display and uses the Qualcomm Snapdragon 835\r\n', '5000', '2018-11-13', '', '1', 1, 1, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'to Identify user',
  `Username` varchar(255) NOT NULL COMMENT 'username to Login',
  `avatar` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL COMMENT 'password to login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `RegStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'Pending Approval',
  `RegDate` date NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `avatar`, `Password`, `Email`, `FullName`, `RegStatus`, `RegDate`, `GroupID`) VALUES
(1, 'admin', '17880_39514764_10216355807875081_3993194484237074432_n.jpg', '8cb2237d0679ca88db6464eac60da96345513964', 'admin@admin.com', 'michael adel', 1, '2018-11-13', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`com_ID`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`itemID`),
  ADD KEY `Cat_ID` (`Cat_ID`),
  ADD KEY `Member_ID` (`Member_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `com_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `itemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'to Identify user', AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
