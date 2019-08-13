ALTER TABLE `data`
ADD COLUMN `timelimit_begin`  int(11) UNSIGNED NULL DEFAULT NULL AFTER `partition_by`,
ADD COLUMN `timelimit_end`  int(11) UNSIGNED NULL DEFAULT NULL AFTER `timelimit_begin`;
