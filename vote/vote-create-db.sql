Create User 'vote'@'localhost' Identified by 'hidden_pass_here';
Create Database vote character set utf8;
Grant ALL PRIVILEGES ON vote.* TO 'vote'@'localhost';
FLUSH PRIVILEGES;

