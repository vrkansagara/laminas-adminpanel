-- Doctrine Migration File Generated on 2019-09-07 10:00:01

-- Version 20190907042123
CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL, content LONGTEXT NOT NULL, status INT NOT NULL, date_created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, content LONGTEXT NOT NULL, author VARCHAR(255) NOT NULL, date_created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE post_tag (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
INSERT INTO migrations (version, executed_at) VALUES ('20190907042123', CURRENT_TIMESTAMP);

-- Version 20190907042406
CREATE INDEX date_created_index ON post (date_created);
ALTER TABLE comment ADD CONSTRAINT comment_post_id_fk FOREIGN KEY (post_id) REFERENCES post (id);
CREATE INDEX post_id_index ON comment (post_id);
ALTER TABLE post_tag ADD CONSTRAINT post_tag_post_id_fk FOREIGN KEY (post_id) REFERENCES post (id);
ALTER TABLE post_tag ADD CONSTRAINT post_tag_tag_id_fk FOREIGN KEY (tag_id) REFERENCES tag (id);
CREATE INDEX post_id_index ON post_tag (post_id);
CREATE INDEX tag_id_index ON post_tag (tag_id);
INSERT INTO migrations (version, executed_at) VALUES ('20190907042406', CURRENT_TIMESTAMP);
