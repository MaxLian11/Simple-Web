-- choose database
use registration;

-- Maximum two blogs per day

DELIMITER &&
CREATE DEFINER = `john`@`localhost` TRIGGER Two_Blogs_Per_Day
BEFORE INSERT ON `registration`.`blog` FOR EACH ROW
BEGIN
      DECLARE rowcount INT;
      SELECT COUNT(*) INTO rowcount 
      FROM blog
      WHERE user_id = NEW.user_id AND date = CURDATE();
      IF (rowcount >= 2) THEN
         signal sqlstate '45000' set message_text = 'You can not post more than two belogs a day! Please try tomorrow.';
      END IF;
END &&
DELIMITER ;


-- No comments allowed to own blog

DELIMITER $$
CREATE DEFINER = `john`@`localhost` TRIGGER No_Comments_Own_Blog
BEFORE INSERT ON `registration`.`comment` FOR EACH ROW
BEGIN
      DECLARE rowcount INT;
      SELECT COUNT(*) INTO rowcount 
      FROM blog
      WHERE blog_id = NEW.blog_id AND user_id = NEW.user_id;
      IF (rowcount > 0) THEN
         signal sqlstate '45000' set message_text = 'You can not leave comments to your own blog.';
      END IF;
END $$
DELIMITER ;


-- Maximum one comment per blog

DELIMITER $$
CREATE DEFINER = `john`@`localhost` TRIGGER One_Comment_Per_Blog
BEFORE INSERT ON `registration`.`comment` FOR EACH ROW
BEGIN
      DECLARE rowcount INT;
      SELECT COUNT(*) INTO rowcount 
      FROM comment
      WHERE blog_id = NEW.blog_id AND user_id = new.user_id;
      IF (rowcount >= 1) THEN
         signal sqlstate '45000' set message_text = 'You can not leave more than one comment per blog.';
      END IF;
END $$
DELIMITER ;


-- Maximum three comments per day

DELIMITER $$
CREATE DEFINER = `john`@`localhost` TRIGGER Three_Comments_Per_Day
BEFORE INSERT ON `registration`.`comment` FOR EACH ROW
BEGIN
      DECLARE rowcount INT;
      SELECT COUNT(*) INTO rowcount 
      FROM comment
      WHERE user_id = NEW.user_id AND date = CURDATE();
      IF (rowcount >= 3) THEN
         signal sqlstate '45000' set message_text = 'You can not leave more than 3 comments per day! Please try tomorrow.';
      END IF;
END $$
DELIMITER ;
