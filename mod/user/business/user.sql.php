<?php
$sql["add_member"] = "
      INSERT INTO
         `tbl_member`
      SET
         `username` = '%s',
         `password` = '%s',
         `email` = '%s'
   ";

$sql["get_member_id_by_email"] = "
   SELECT
      `id`
   FROM
      `tbl_member`
   WHERE
      `email` = '%s'
";

$sql["get_member_id_by_phone_username"] = "
   SELECT
      `id`
   FROM
      `tbl_member`
   WHERE
      `username` = '%s'
   OR
      `phone` = '%s'
";

$sql["get_user_by_user_name"] = "
   SELECT
       `id`,
       `username`,
       `password`,
       `id_login`,
       `first_name`,
       `last_name`,
       `phone`,
       `email`,
       `address`,
       `city`,
       `postcode`,
       `country`,
       `notes`,
       `status`,
       `confirm_email`,
       `imei`,
       `regis_from`,
       `promo_code`,
       `facebook_id`
   FROM
      `tbl_member`
   WHERE
      `username` = '%s'
";

$sql["update_phone_by_username"] = "
   UPDATE
      `tbl_member`
   SET
      `phone` = '%s'
   WHERE
      `username` = '%s'
";

?>