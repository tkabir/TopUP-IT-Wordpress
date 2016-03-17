INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (11, 'Livejournal', 'http://www.livejournal.com/update.bml?subject={title}&event={url}', 'livejournal', '#3399ff', '#ffffff', 0)
  ON DUPLICATE KEY UPDATE
    `url` = 'http://www.livejournal.com/update.bml?subject={title}&event={url}';
INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (12, 'Odnoklassniki', 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments={title}&st._surl={url}', 'odnoklassniki', '#3399ff', '#ffffff', 0)
  ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);
INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (13, 'Linkedin', 'https://www.linkedin.com/shareArticle?mini=true&title={title}&url={url}', 'linkedin', '#3399ff', '#ffffff', 0)
  ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);
INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (14, 'Print', '#', 'print', '#3399ff', '#ffffff', 0)
  ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);
INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (15, 'Add Bookmark', '#', 'bookmark', '#3399ff', '#ffffff', 0)
  ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);
INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (16, 'Mail', '#', 'mail', '#3399ff', '#ffffff', 0)
  ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);
INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (17, 'Evernote', 'https://www.evernote.com/clip.action?url={url}&title={title}', 'evernote', '#6ba92f', '#ffffff', 0)
  ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);
INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (18, 'WhatsApp', 'whatsapp://send?text={url}', 'whatsapp', '#43C353', '#ffffff', 0)
  ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);
INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (19, 'Tumblr', 'http://www.tumblr.com/share/link?url={url}&name={title}&description={description}', 'tumblr', '#36465D', '#ffffff', 0)
  ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);