ALTER TABLE `users`
ADD `failed_attempt_count` tinyint NOT NULL DEFAULT '0',
COMMENT=''; 
