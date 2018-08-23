CREATE DATABASE IF NOT EXISTS `eatlocal`;
USE `eatlocal`;

CREATE TABLE IF NOT EXISTS categories(
  id int NOT NULL AUTO_INCREMENT,
  Cat_title varchar(255) NOT NULL,
  date_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)


CREATE TABLE IF NOT EXISTS orders (
  id int NOT NULL AUTO_INCREMENT,
  product_code varchar(255) NOT NULL,
  product_name varchar(255) NOT NULL,
  product_desc varchar(255) NOT NULL,
  price int(10) NOT NULL,
  units int(5) NOT NULL,
  total int(15) NOT NULL,
  email varchar(255) NOT NULL,
  date_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)


CREATE TABLE IF NOT EXISTS products (
  id int NOT NULL AUTO_INCREMENT,
  product_code varchar(60) NOT NULL,
  product_name varchar(60) NOT NULL,
  product_desc tinytext NOT NULL,
  product_img_name varchar(60) NOT NULL,
  qty int(5) NOT NULL,
  price decimal(10,2) NOT NULL,
  category varchar(60) NOT NULL,
  date_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)


CREATE TABLE IF NOT EXISTS users (
  id int NOT NULL AUTO_INCREMENT,
  fname varchar(255) NOT NULL,
  lname varchar(255) NOT NULL,
  address varchar(255) NOT NULL,
  city varchar(100) NOT NULL,
  pin int(6) NOT NULL,
  email varchar(255) NOT NULL,
  password varchar(15) NOT NULL,
  type varchar(20) NOT NULL DEFAULT 'user',
  date_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)



INSERT INTO categories (Cat_title) VALUES
('Homemade food'),
('Fruits'),
('Vegetables');




INSERT INTO orders (product_code, product_name, product_desc, price, units, total, email) VALUES
('p1', 'Apple', 'apple', 20, 3, 60, 'admin@admin.com'),
('p3', 'Corn', 'corn', 29, 1, 29, 'admin@admin.com'),
('p1', 'Apple', 'apple', 20, 1, 20,  'admin@admin.com'),
('p1', 'Apple', 'apple', 20, 1, 20,  'admin@admin.com'),
('p1', 'Apple', 'apple', 20, 1, 20,  'admin@admin.com');




INSERT INTO products (product_code, product_name, product_desc, product_img_name, qty, price, category) VALUES
('p1', 'Apple', 'apple', 'apple.jpg', 20, '20.00', 'fruits'),
('p2', 'Orange', 'orange', 'orange.jpg', 7, '25.00', 'fruits'),
('p3', 'Corn', 'corn', 'corn.jpg', 33, '29.00', 'fruits'),
('p4', 'Ananas', 'Ananas', 'Ananas.jpg', 34, '19.00', 'fruits'),
('p5', 'Pomegranate', 'Pomegranate', 'Pomegranate.jpg', 34, '37.00', 'fruits'),
('p6', 'Honey', 'Honey', 'Honey.jpg', 34, '47.00', 'fruits'),
('p7', 'cake', 'cake', 'cake.jpg', 34, '55.00', 'fruits'),
('p8', 'cherry', 'cherry', 'cherry.jpg', 34, '48.00', 'fruits'),
('p9', 'rrush', 'rrush', 'rrush.jpg', 34, '60.00', 'fruits');



INSERT INTO users (fname, lname, address, city, pin, email, password, type) VALUES
('admin', 'admin', 'address', 'California', 95014, 'admin@admin.com', 'admin', 'admin');