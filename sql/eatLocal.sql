SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+10:00";

drop database jc451631_ecommerce;

create database jc451631_ecommerce;

use jc451631_ecommerce;

create user 'jc451631'@'localhost' identified by 'Password1';

grant all privileges on jc451631_ecommerce.* to 'jc451631'@'%';

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(32) NOT NULL PRIMARY KEY,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'user',
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(64) DEFAULT null,
  `postcode` varchar(16) NOT NULL,
  `suburb` varchar(64) NOT NULL,
  `desc` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`user_id`, `fname`, `lname`, `address`, `postcode`, `suburb`, `email`, `password`, `type`, `desc`) VALUES
('scott', 'Scott', 'B', 'Milton', '4066','Milton', 'scott@gmail.com', '$2y$10$kQNhbPHJveyAMqa4beu0P..a4ZGxr3qGrbLtwpYW2XLYjoaqaVQvW', 'user', 'Scott farms produce in Milton and sells locally.'),
('james', 'James', 'Smith', 'Milton', '4000', 'Brisbane City', 'james.smith@gmail.com', '$2y$10$2i1TcHTQr0Om3FjtrCL15eDljvw8uMqMqnyJ/QXN8X/KWwOno8/qS', 'user', 'James operates out of the Brisbane CBD and sources all produce locally within Brisbane.'),
('jay', 'Jay', 'L', 'Milton', '4072', 'St. Lucia', 'jay@scott', '$2y$10$nUQImDpxXHFE5JsMisEv..29UcPvzKJ7CXM1PQD6AnEAT5c7JhZa2', 'user', 'Jay is based in the western suburbs of Brisbane and sources all her produce from organic farms on the Sunshine- and Gold Coast-hinterlands.'),
('admin', 'admin', 'admin', 'address', '95014', 'Los Angeles', 'admin@admin.com', '$2y$10$CepkuR7SOUJOJTAKOPICSuvwUb37vK873z9vdMM9bzQI02FOv/4nC', 'admin', NULL);

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `Cat_title` varchar(255) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(`Cat_title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `categories` (`Cat_title`) VALUES
('Homemade food'),
('Fruit'),
('Vegetables');


--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `seller_user_id` varchar(32) NOT NULL,
  `product_code` varchar(60) NOT NULL,
  `product_name` varchar(60) NOT NULL,
  `product_desc` tinytext NOT NULL,
  `product_img_name` varchar(60) NOT NULL,
  `qty` int(5) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(255) NOT NULL,
  `unit` varchar(32) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_seller_user_id` FOREIGN KEY (`seller_user_id`) REFERENCES `users`(user_id),
  CONSTRAINT `fk_category` FOREIGN KEY (`category`) REFERENCES `categories`(Cat_title)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `products` (`seller_user_id`, `product_code`, `product_name`, `product_desc`, `product_img_name`, `qty`, `price`, `category`, `unit`) VALUES
('scott', 'p1', 'Apples', 'apple', 'apple.jpg', 6, '6', 'Fruit', 'kg'),
('james', 'p2', 'Oranges', 'Fresh oranges grown in Queensland', 'orange.jpg', 8, '3', 'Fruit', 'kg'),
('jay', 'p3', 'Corn', 'corn', 'corn.jpg', 8, '2', 'Fruit', 'cob'),
('scott', 'p4', 'Bananas', 'Bananas', 'bananas.jpg', 8, '5', 'Fruit', 'kg'),
('james', 'p5', 'Pomegranates', 'Pomegranate', 'Pomegranate.jpg', 8, '2', 'Fruit', 'each'),
('jay', 'p6', 'Honey', 'Honey', 'honey.jpg', 8, '7', 'Fruit', '250g bottle'),
('scott', 'p7', 'Cakes', 'cake', 'cake.jpg', 8, '30', 'Homemade food', 'each'),
('james', 'p8', 'Cherries', 'cherry', 'cherry.jpg', 8, '8', 'Fruit', 'kg'),
('jay', 'p9', 'Carrots', 'carrot', 'carrot.jpg', 8, '3', 'Fruit', 'kg'),
('scott', 'Eggplants', 'Eggplant', 'Eggplant', 'eggplant.jpg', 8, '8', 'Vegetables', 'kg'),
('james', 'jam.jpg2', 'jam', 'jam', 'jam.jpg', 8, '2.00', 'Homemade food', '250g bottle'),
('jay', 'aruglua.jpg1', 'aruglua', 'aruglua', 'aruglua.jpg', 8, '2.50', 'Vegetables', 'bunch');

CREATE TABLE `product_images` (
	`image_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`product_id` int(11) NOT NULL,
	CONSTRAINT `fk__product_images__product_id` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `product_images` (`product_id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(2),
(2);

-- --------------------------------------------------------


CREATE TABLE `conversations` (
  `conversation_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `from_user_id` varchar(32) NOT NULL,
  `to_user_id` varchar(32) NOT NULL,
  `subject` varchar(1024) NOT NULL,
  CONSTRAINT fk_from_user_id FOREIGN KEY (from_user_id) REFERENCES users(user_id),
  CONSTRAINT fk_to_user_id FOREIGN KEY (to_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `conversations` (`from_user_id`, `to_user_id`, `subject`) VALUES
('scott', 'james', 'Enquiry about your produce'),
('jay', 'james', 'Is your product grown locally?');

CREATE TABLE `messages` (
  `conversation_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `time_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` varchar(4096) NOT NULL,
  `sender_is_from` bool NOT NULL,
  `isread` bool NOT NULL,
  CONSTRAINT fk_conversation_id FOREIGN KEY (conversation_id) REFERENCES conversations(conversation_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `messages` (`conversation_id`, `message`, `sender_is_from`, `isread`) VALUES
(1, 'Hi, what quantity of produce can you normally supply at a time?', 1, 0),
(2, 'Hey :) Is your produce grown locally at all times?', 1, 0),
(1, 'Hi Scott - thanks for your question. I can let you know if I can commit to regular order if you can let me know approximately what quantities you are after.', 0, 0),
(2, 'Hi Jay, thanks for your question. Yes, all my produce is only grown locally.', 0, 0);

CREATE TABLE `product_reviews` (
	`product_id` int(11) NOT NULL,
	`review_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`message` varchar(4096) NOT NULL,
	`rating` int(11) NOT NULL,
	`author_user_id` varchar(32) NOT NULL,
	CONSTRAINT fk_product_id FOREIGN KEY (product_id) REFERENCES products(id),
	CONSTRAINT fk_author_user_id FOREIGN KEY (author_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `product_reviews` (`product_id`, `message`, `rating`, `author_user_id`) VALUES
('2', 'The oranges are very sweet.', 5, 'scott'),
('2', 'The oranges are great!', 5, 'jay');

CREATE TABLE `user_reviews` (
	`user_id` varchar(32) NOT NULL,
	`review_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`message` varchar(4096) NOT NULL,
	`rating` int(11) NOT NULL,
	`author_user_id` varchar(32) NOT NULL,
	CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(user_id),
	CONSTRAINT fk_author_user_id_reviews FOREIGN KEY (author_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `buyer_user_id` varchar(32) NOT NULL,
  `status` varchar(255) NOT NULL,
  `txnid` varchar(20),
  `payment_status` varchar(25),
  `payment_amount` decimal(7,2),
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT fk_buyer_user_id FOREIGN KEY (buyer_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(10) NOT NULL,
	CONSTRAINT fk_prod_id FOREIGN KEY (product_id) REFERENCES products(id),
	CONSTRAINT fk_order_id FOREIGN KEY (order_id) REFERENCES orders(id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

COMMIT;
