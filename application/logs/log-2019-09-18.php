<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-09-18 21:27:41 --> Query error: ERROR:  syntax error at or near "duplicate"
LINE 1: ...NSERT into tags(tag_name) values('trừu tượng') on duplicate ...
                                                             ^ - Invalid query: INSERT into tags(tag_name) values('trừu tượng') on duplicate key update tag_name = values(tag_name)
ERROR - 2019-09-18 22:08:12 --> Query error: ERROR:  null value in column "updated_at" violates not-null constraint
DETAIL:  Failing row contains (1, dawn, upload/photos/5d82485c0209c.jpg, upload/photos/5d82485c0209c_260.jpg,upload/photos/5d82485c0209c_..., 159, 1366x768, 0, 0, 2, 0, null, 2019-09-18 22:08:12.185344, null). - Invalid query: INSERT INTO "photos" ("uid", "size", "dim", "content", "thumbnail", "title") VALUES ('2', 159.25, '1366x768', 'upload/photos/5d82485c0209c.jpg', 'upload/photos/5d82485c0209c_260.jpg,upload/photos/5d82485c0209c_315.jpg,upload/photos/5d82485c0209c_410.jpg', 'dawn')
ERROR - 2019-09-18 22:22:41 --> Query error: ERROR:  syntax error at or near "duplicate"
LINE 1: ...RT into photos_tags(tag_id, pid) values(78, 2) on duplicate ...
                                                             ^ - Invalid query: INSERT into photos_tags(tag_id, pid) values(78, 2) on duplicate key update pid = values(pid), tag_id = values(tag_id)
ERROR - 2019-09-18 22:26:01 --> Photos::delPhoto, pid: 3
