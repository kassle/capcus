--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `code` varchar(128) NOT NULL,
  `owner` varchar(256) NOT NULL,
  `create_time` timestamp NOT NULL,
  `source` varchar(2560) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
