create database if not exists blog;

use blog;

create table if not exists users (
  user_id int auto_increment not null,
  user_name varchar(150) not null,
  user_email varchar(255) not null,
  user_password varchar(255) not null,
  primary key(user_id)
);