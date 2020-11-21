use `registration`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `comment`;
CREATE TABLE comment(
    comment_id INT(11) NOT NULL AUTO_INCREMENT,
    comment VARCHAR(250),
    date DATE,
    reaction VARCHAR(20),
    user_id INT(11),
    blog_id INT(11),
    PRIMARY KEY(comment_id),
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(blog_id) REFERENCES blog(blog_id)
);

DROP TABLE IF EXISTS `blog`;
CREATE TABLE blog(
    blog_id INT(11) NOT NULL AUTO_INCREMENT,
    subject VARCHAR(100),
    description VARCHAR(1000),
    tags VARCHAR(100),
    user_id INT(11),
    date DATE,
    PRIMARY KEY(blog_id),
    FOREIGN KEY(user_id) REFERENCES users(id)
);

DROP TABLE IF EXISTS `hobbies`;
CREATE TABLE hobbies(
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11),
    hobby varchar(50),
    PRIMARY KEY(id),
    CONSTRAINT fk_hobby FOREIGN KEY(user_id) REFERENCES users(id)
);

DROP TABLE IF EXISTS `follows`;
CREATE TABLE follows(
    user_id INT(11) NOT NULL,
    follower_id INT(11) NOT NULL,
    PRIMARY KEY(user_id, follower_id),
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(follower_id) REFERENCES users(id)
);