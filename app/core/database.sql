create database if not exists blog;

use blog;

create table if not exists users (
  user_id int auto_increment not null,
  user_name varchar(150) not null,
  user_email varchar(255) not null,
  user_password varchar(255) not null,
  user_active tinyint not null default(1),
  primary key(user_id)
);

create table if not exists posts (
  post_id int auto_increment not null,
  post_title varchar(255) not null,
  post_content text not null,
  post_userId int not null,
  post_countView int not null default(0),
  post_active tinyint not null default(1),
  primary key(post_id),
  foreign key(post_userId) references users(user_id) on delete cascade
);

create table if not exists reviews (
  review_id int auto_increment not null,
  review_postId int not null,
  review_auth tinyint not null default(0),
  review_message text,
  primary key(review_id),
  foreign key(review_postId) references posts(post_id) on delete cascade
);

create table if not exists views (
  view_postId int not null,
  view_userId int not null,
  primary key(view_postId,view_userId),
  foreign key(view_postId) references posts(post_id) on delete cascade,
  foreign key(view_userId) references users(user_id) on delete cascade
);

create table if not exists admins (
  admin_id int auto_increment not null,
  admin_name varchar(150) not null,
  admin_email varchar(255) not null,
  admin_password varchar(255) not null,
  primary key(admin_id)
);

insert into admins
(admin_name,admin_email,admin_password)
values
("Admin01","admin@gmail.com","$2y$10$Z00y4ueUTvZLl4/1RIGTy.5FPLMmf6e5Ka/N9b6bthzkEXelHgeVy");