
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `securedrop`
--
CREATE DATABASE IF NOT EXISTS `securedrop` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `securedrop`;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `keyid` int(1) NOT NULL AUTO_INCREMENT,
  `secret` text NOT NULL,
  PRIMARY KEY (`keyid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `fileid` int(10) NOT NULL AUTO_INCREMENT,
  `filename` text NOT NULL,
  `expiry` int(15) NOT NULL,
  `timestamp` int(15) NOT NULL,
  `accesskey` text NOT NULL,
  `userid` text NOT NULL,
  `accesscount` int(10) NOT NULL,
  `filesize` int(20) NOT NULL,
  `encrypted` int(1) NOT NULL,
  PRIMARY KEY (`fileid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userid` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(99) NOT NULL,
  `password` varchar(99) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
