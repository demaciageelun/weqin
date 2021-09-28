<?php

return [
    '4.0.0' => function () {
        $installSql = file_get_contents(__DIR__ . '/forms/install/install.sql');
        sql_execute($installSql, true, false);
    },

    '4.0.1' => function () {
    },

    '4.0.2' => function () {
    },

    '4.0.3' => function () {
    },

    '4.0.4' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_delivery` ADD COLUMN `is_goods`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否商品信息' AFTER `deleted_at`;
ALTER TABLE `zjhj_bd_mch` ALTER column `user_id` SET DEFAULT '0';
ALTER TABLE `zjhj_bd_lottery` ADD COLUMN `buy_goods_id` int(11) NOT NULL COMMENT '购买商品id' AFTER `code_num`;
ALTER TABLE `zjhj_bd_bargain_banner` ADD COLUMN `deleted_at` timestamp NOT NULL AFTER `created_at`;
EOF;
        sql_execute($sql);
    },

    '4.0.5' => function () {
    },

    '4.0.7' => function () {
    },

    '4.0.8' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_banner`
ADD COLUMN `open_type` varchar(65) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '打开方式' AFTER `page_url`,
ADD COLUMN `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '导航参数' AFTER `open_type`;
alter table `zjhj_bd_core_action_log` modify column `before_update` LONGTEXT;
alter table `zjhj_bd_core_action_log` modify column `after_update` LONGTEXT;

ALTER TABLE `zjhj_bd_user` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_user_info` ADD INDEX `user_id`(`user_id`);
ALTER TABLE `zjhj_bd_option` ADD INDEX `name`(`name`);
ALTER TABLE `zjhj_bd_option` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_order_detail` ADD INDEX `order_id`(`order_id`);
ALTER TABLE `zjhj_bd_order` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_order` ADD INDEX `user_id`(`user_id`);
ALTER TABLE `zjhj_bd_user_card` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_user_card` ADD INDEX `user_id`(`user_id`);
ALTER TABLE `zjhj_bd_goods` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_goods` ADD INDEX `goods_warehouse_id`(`goods_warehouse_id`);
ALTER TABLE `zjhj_bd_goods` ADD INDEX `sign`(`sign`);
ALTER TABLE `zjhj_bd_goods_member_price` ADD INDEX `goods_attr_id`(`goods_attr_id`);
ALTER TABLE `zjhj_bd_goods_share` ADD INDEX `goods_attr_id`(`goods_attr_id`);
ALTER TABLE `zjhj_bd_goods_attr` ADD INDEX `goods_id`(`goods_id`);
ALTER TABLE `zjhj_bd_goods_share` ADD INDEX `goods_id`(`goods_id`);
ALTER TABLE `zjhj_bd_goods_cat_relation` ADD INDEX `goods_warehouse_id`(`goods_warehouse_id`);
EOF;
        sql_execute($sql);
    },

    '4.0.9' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_admin_info` ADD COLUMN `is_default`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否使用默认权限';
alter table `zjhj_bd_core_action_log` add `remark` varchar(255) not null default '';
EOF;
        sql_execute($sql);
    },

    '4.0.10' => function () {
    },

    '4.0.11' => function () {
    },

    '4.0.12' => function () {
        $sql = <<<EOF
alter table `zjhj_bd_mall_members` add `bg_pic_url` varchar(255) not null;
EOF;
        sql_execute($sql);
    },
    '4.0.13' => function () {
        $sql = <<<EOF
alter table `zjhj_bd_pintuan_order_relation` add `robot_id` int(11) not null default 0;
CREATE TABLE `zjhj_bd_pintuan_robots` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `nickname` varchar(65) NOT NULL DEFAULT '' COMMENT '机器人昵称', `avatar` varchar(255) NOT NULL DEFAULT '', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },

    '4.0.14' => function () {
    },

    '4.0.15' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_diy_form` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `form_data` longtext NOT NULL, `created_at` datetime NOT NULL, `is_delete` tinyint(1) NOT NULL, `updated_at` datetime NOT NULL, `deleted_at` datetime NOT NULL, PRIMARY KEY (`id`), KEY `user_id` (`user_id`) USING BTREE, KEY `mall_id` (`mall_id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='diy表单信息';
EOF;
        sql_execute($sql);
    },

    '4.0.16' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_booking_setting` ADD COLUMN `goods_poster` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '自定义海报' AFTER `payment_type`;
ALTER TABLE `zjhj_bd_integral_mall_setting` ADD COLUMN `goods_poster` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '自定义海报' AFTER `send_type`;
ALTER TABLE `zjhj_bd_lottery_setting` ADD COLUMN `goods_poster` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '自定义海报' AFTER `send_type`;
ALTER TABLE `zjhj_bd_miaosha_setting` ADD COLUMN `goods_poster` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '自定义海报' AFTER `send_type`;
ALTER TABLE `zjhj_bd_pintuan_setting` ADD COLUMN `goods_poster` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '自定义海报' AFTER `send_type`;
ALTER TABLE `zjhj_bd_step_setting` ADD COLUMN `goods_poster` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '自定义海报' AFTER `is_territorial_limitation`, ADD COLUMN `step_poster` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '步数海报' AFTER `goods_poster`;
EOF;
        sql_execute($sql);
    },

    '4.0.17' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_goods_warehouse` MODIFY COLUMN `detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '商品详情，图文';
EOF;
        sql_execute($sql);
    },

    '4.0.18' => function () {
    },

    '4.0.19' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_lottery_setting` ADD COLUMN `is_sms` tinyint(1) NOT NULL DEFAULT 0 COMMENT '开启短信提醒' AFTER `goods_poster`, ADD COLUMN `is_mail` tinyint(1) NOT NULL DEFAULT 0 COMMENT '开启邮件提醒' AFTER `is_sms`, ADD COLUMN `is_print` tinyint(1) NOT NULL DEFAULT 0 COMMENT '开启打印' AFTER `is_mail`;
EOF;
        sql_execute($sql);
    },

    '4.0.20' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_attachment_storage` ADD COLUMN `user_id` int NOT NULL DEFAULT 1 COMMENT '存储设置所属账号';
ALTER TABLE `zjhj_bd_admin_info` ADD COLUMN `secondary_permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '二级权限';
CREATE TABLE IF NOT EXISTS `zjhj_bd_bonus_captain` ( `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT , `mall_id` int(11) NOT NULL , `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '队长姓名' , `mobile` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '队长手机' , `user_id` int(11) NOT NULL , `all_bonus` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '累计分红' , `total_bonus` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '已分红' , `expect_bonus` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '预计分红，未到账分红' , `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' , `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述' , `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '-1重新申请未提交 0--申请中 1--成功 2--失败 3--处理中' , `all_member` int(11) NOT NULL DEFAULT 0 COMMENT '团员数量' , `created_at` timestamp NOT NULL , `updated_at` timestamp NOT NULL , `deleted_at` timestamp NOT NULL , `apply_at` timestamp NULL DEFAULT NULL , `is_delete` tinyint(1) NOT NULL DEFAULT 0 , PRIMARY KEY (`id`), INDEX `user_id` (`user_id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='团队分红队长表' AUTO_INCREMENT=1;
CREATE TABLE IF NOT EXISTS `zjhj_bd_bonus_captain_log` ( `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT , `mall_id` int(11) NOT NULL , `handler` int(11) NOT NULL DEFAULT 0 COMMENT '操作人' , `user_id` int(11) NOT NULL COMMENT '队长' , `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '事件名' , `content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '记录信息' , `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP , `is_delete` tinyint(1) NOT NULL DEFAULT 0 , PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='队长操作日志表' AUTO_INCREMENT=1;
CREATE TABLE IF NOT EXISTS `zjhj_bd_bonus_captain_relation` ( `id` int(11) NOT NULL AUTO_INCREMENT , `captain_id` int(11) NOT NULL COMMENT '队长id' , `user_id` int(11) NOT NULL COMMENT '团队id' , `is_delete` tinyint(1) NOT NULL DEFAULT 0 , `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP , `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , PRIMARY KEY (`id`), INDEX `user_id` (`user_id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
CREATE TABLE IF NOT EXISTS `zjhj_bd_bonus_cash` ( `id` int(11) NOT NULL AUTO_INCREMENT , `mall_id` int(11) NOT NULL , `user_id` int(11) NOT NULL , `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '订单号' , `price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '提现金额' , `service_charge` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '提现手续费（%）' , `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '提现方式 auto--自动打款 wechat--微信打款 alipay--支付宝打款 bank--银行转账 balance--打款到余额' , `extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '额外信息 例如微信账号、支付宝账号等' , `status` int(11) NOT NULL DEFAULT 0 COMMENT '提现状态 0--申请 1--同意 2--已打款 3--驳回' , `is_delete` int(11) NOT NULL DEFAULT 0 , `created_at` datetime NOT NULL , `updated_at` datetime NOT NULL , `deleted_at` datetime NOT NULL , `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL , PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='提现记录表' AUTO_INCREMENT=1;
CREATE TABLE IF NOT EXISTS `zjhj_bd_bonus_cash_log` ( `id` int(11) NOT NULL AUTO_INCREMENT , `mall_id` int(11) NOT NULL , `user_id` int(11) NOT NULL , `type` int(11) NOT NULL DEFAULT 1 COMMENT '类型 1--收入 2--支出' , `price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '变动佣金' , `desc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL , `custom_desc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL , `is_delete` int(11) NOT NULL DEFAULT 0 , `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' , `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' , `deleted_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' , PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
CREATE TABLE IF NOT EXISTS `zjhj_bd_bonus_order_log` ( `id` int(11) NOT NULL AUTO_INCREMENT , `mall_id` int(11) NOT NULL DEFAULT 0 , `order_id` int(11) NOT NULL DEFAULT 0 COMMENT '订单ID' , `from_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '下单用户ID' , `to_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '受益用户ID' , `price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '订单商品实付金额' , `bonus_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '分红金额' , `fail_bonus_price` decimal(10,2) NULL DEFAULT 0.00 COMMENT '失败分红金额' , `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '0预计分红，1完成分红，2分红失败' , `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , `is_delete` tinyint(2) NOT NULL DEFAULT 0 , `remark` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注' , `bonus_rate` tinyint(4) NOT NULL DEFAULT 0 COMMENT '下单时的分红比例%' , PRIMARY KEY (`id`), UNIQUE INDEX `order_id` (`order_id`) USING BTREE , INDEX `from_user_id` (`from_user_id`) USING BTREE , INDEX `to_user_id` (`to_user_id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;
CREATE TABLE IF NOT EXISTS `zjhj_bd_bonus_setting` ( `id` int(11) NOT NULL AUTO_INCREMENT , `mall_id` int(11) NOT NULL , `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL , `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL , `created_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间' , `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间' , `is_delete` int(11) NOT NULL DEFAULT 0 COMMENT '是否删除 0--未删除 1--已删除' , `deleted_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '删除时间' , PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='团队分红设置' AUTO_INCREMENT=1;
EOF;
        sql_execute($sql);
    },

    '4.0.21' => function () {
        $sql = <<<EOF
alter table `zjhj_bd_mch_setting` add `is_web_service` tinyint(1) NOT NULL default 0;
alter table `zjhj_bd_mch_setting` add `web_service_url` varchar(255) NOT NULL default '';
alter table `zjhj_bd_mch_setting` add `web_service_pic` varchar(255) NOT NULL default '';
EOF;
        sql_execute($sql);
    },

    '4.0.22' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_user_card` MODIFY COLUMN `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `data`, MODIFY COLUMN `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `start_time`;
EOF;
        sql_execute($sql);
    },

    '4.0.24' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_order` ADD COLUMN `customer_name` VARCHAR(65) DEFAULT '' NULL COMMENT '京东商家编号' AFTER `send_time`;
ALTER TABLE `zjhj_bd_order_refund` ADD COLUMN `customer_name` VARCHAR(65) DEFAULT '' NULL COMMENT '京东商家编号' AFTER `send_time`, ADD COLUMN `merchant_customer_name` VARCHAR(65) DEFAULT '' NULL COMMENT '商家京东商家编号' AFTER `confirm_time`;
EOF;
        sql_execute($sql);
    },

    '4.0.25' => function () {
    },

    '4.0.26' => function () {
    },

    '4.0.27' => function () {
    },

    '4.0.28' => function () {
    },

    '4.0.30' => function () {
        $sql = <<<EOF
alter table zjhj_bd_mall_member_orders change detail detail MEDIUMTEXT;
CREATE TABLE `zjhj_bd_scan_code_pay_activities` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `name` varchar(255) NOT NULL DEFAULT '' COMMENT '活动名称', `start_time` timestamp NOT NULL COMMENT '活动开始时间', `end_time` timestamp NOT NULL COMMENT '活动结束时间', `send_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1.赠送所有规则|2.赠送满足最高规则', `rules` text COMMENT '买单规则', `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_scan_code_pay_activities_groups` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL DEFAULT '', `activity_id` int(11) NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_scan_code_pay_activities_groups_level` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `group_id` int(11) NOT NULL, `level` int(11) NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_scan_code_pay_activities_groups_rules` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `group_id` int(11) NOT NULL, `rules_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1.赠送规则|2.优惠规则', `consume_money` decimal(10,2) NOT NULL COMMENT '单次消费金额', `send_integral_num` int(11) NOT NULL COMMENT '赠送积分', `send_integral_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1.固定值|2.百分比', `send_money` decimal(10,2) NOT NULL COMMENT '赠送余额', `preferential_money` decimal(10,2) NOT NULL COMMENT '优惠金额', `integral_deduction` int(11) NOT NULL COMMENT '积分抵扣', `integral_deduction_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1.固定值|2.百分比', `is_coupon` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可使用优惠券', `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_scan_code_pay_activities_groups_rules_cards` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `group_rule_id` int(11) NOT NULL, `card_id` int(11) NOT NULL, `send_num` int(11) NOT NULL COMMENT '赠送数量', `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_scan_code_pay_activities_groups_rules_coupons` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `group_rule_id` int(11) NOT NULL, `coupon_id` int(11) NOT NULL, `send_num` int(11) NOT NULL COMMENT '赠送数量', `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_scan_code_pay_orders` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `order_id` int(11) NOT NULL, `activity_preferential_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '活动优惠价格', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_scan_code_pay_setting` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `is_scan_code_pay` tinyint(1) NOT NULL DEFAULT '0', `payment_type` text NOT NULL, `is_share` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启分销', `is_sms` tinyint(1) NOT NULL DEFAULT '0', `is_mail` tinyint(1) NOT NULL DEFAULT '0', `share_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1.百分比|2.固定金额', `share_commission_first` decimal(10,2) NOT NULL DEFAULT '0.00', `share_commission_second` decimal(10,2) NOT NULL DEFAULT '0.00', `share_commission_third` decimal(10,2) NOT NULL DEFAULT '0.00', `poster` mediumtext NOT NULL COMMENT '自定义海报', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

ALTER TABLE `zjhj_bd_bonus_captain` ADD COLUMN `level` int(11) NOT NULL DEFAULT 0 COMMENT '会员等级:0. 普通成员 关联等级表' AFTER `remark`;
CREATE TABLE `zjhj_bd_bonus_members` ( `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT , `mall_id` int(11) NOT NULL , `level` int(11) UNSIGNED NOT NULL COMMENT '等级' , `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '等级名称' , `auto_update` tinyint(1) NOT NULL COMMENT '是否开启自动升级' , `update_type` int(11) NOT NULL DEFAULT 0 COMMENT '升级条件类型' , `update_condition` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '升级条件' , `rate` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '分红比例' , `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态 0--禁用 1--启用' , `created_at` timestamp NOT NULL , `updated_at` timestamp NOT NULL , `deleted_at` timestamp NOT NULL , `is_delete` tinyint(1) NOT NULL DEFAULT 0 , PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1 ROW_FORMAT=DYNAMIC ;
EOF;
        sql_execute($sql);
    },

    '4.0.32' => function () {
    },

    '4.0.34' => function () {
        $sql = <<<EOF
alter table `zjhj_bd_goods_cats` add `is_show` tinyint(1) default '1';
EOF;
        sql_execute($sql);
    },

    '4.0.35' => function () {
    },

    '4.0.36' => function () {
    },

    '4.0.37' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_user_info` ADD INDEX `parent_id`(`parent_id`);
ALTER TABLE `zjhj_bd_booking_setting` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_share` ADD INDEX `mall_id`(`mall_id`), ADD INDEX `is_delete`(`is_delete`);

ALTER TABLE `zjhj_bd_lottery_setting`
ADD COLUMN `cs_status`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否开启客服提示' AFTER `is_print`,
ADD COLUMN `cs_prompt_pic`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '客服提示图片' AFTER `cs_status`,
ADD COLUMN `cs_wechat`  longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '客服微信号' AFTER `cs_prompt_pic`,
ADD COLUMN `cs_wechat_flock_qrcode_pic`  longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '微信群' AFTER `cs_wechat`;

alter table zjhj_bd_printer_setting add store_id int(11) default '0' not null;
alter table zjhj_bd_pintuan_goods_groups add group_num int(11) default '0' not null;
alter table zjhj_bd_mall add expired_at TIMESTAMP default '0000-00-00 00:00:00' not null;
EOF;
        sql_execute($sql);
    },

    '4.0.38' => function () {
    },

    '4.0.39' => function () {
    },

    '4.0.40' => function () {
    },

    '4.1.0' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_integral_mall_setting` MODIFY COLUMN `send_type` longtext NOT NULL COMMENT '发货方式';
ALTER TABLE `zjhj_bd_lottery_setting` MODIFY COLUMN `send_type` longtext NOT NULL COMMENT '发货方式';
ALTER TABLE `zjhj_bd_miaosha_setting` MODIFY COLUMN `send_type` longtext NOT NULL;
ALTER TABLE `zjhj_bd_pintuan_setting` MODIFY COLUMN `send_type` longtext NOT NULL COMMENT '发货方式';
ALTER TABLE `zjhj_bd_pond_setting` MODIFY COLUMN `send_type` longtext NOT NULL COMMENT '发货方式';
ALTER TABLE `zjhj_bd_scratch_setting` MODIFY COLUMN `send_type` longtext NOT NULL COMMENT '发货方式';
ALTER TABLE `zjhj_bd_step_setting` MODIFY COLUMN `send_type` longtext NOT NULL COMMENT '发货方式';
ALTER TABLE `zjhj_bd_mch_setting` MODIFY COLUMN `send_type` longtext NOT NULL COMMENT '发货方式';
ALTER TABLE `zjhj_bd_order` ADD COLUMN `distance` INT DEFAULT -1 NULL COMMENT '同城配送距离，-1不在范围内，正数为距离KM' AFTER `auto_sales_time`, ADD COLUMN `city_mobile` VARCHAR(100) DEFAULT '' NULL COMMENT '同城配送联系方式' AFTER `distance`;
CREATE TABLE `zjhj_bd_city_delivery_setting` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT '0', `key` varchar(60) DEFAULT NULL, `value` text, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, `deleted_at` timestamp NULL DEFAULT NULL, `is_delete` tinyint(2) DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zjhj_bd_aliapp_config`;
CREATE TABLE `zjhj_bd_aliapp_config` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `appid` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `app_private_key` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `alipay_public_key` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `cs_tnt_inst_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '', `cs_scene` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '', `app_aes_secret` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '内容加密AES密钥', `transfer_app_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '打款到用户app_id', `transfer_app_private_key` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '打款到用户app_private_key', `transfer_alipay_public_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, `transfer_appcert` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '应用公钥证书', `transfer_alipay_rootcert` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '支付宝根证书', `created_at` timestamp(0) NOT NULL, `updated_at` timestamp(0) NOT NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_aliapp_template` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `tpl_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `tpl_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '', `created_at` timestamp(0) NULL DEFAULT NULL, `updated_at` timestamp(0) NULL DEFAULT NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_bdapp_config` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `created_at` timestamp(0) NULL DEFAULT NULL, `updated_at` timestamp(0) NULL DEFAULT NULL, `app_id` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, `app_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, `app_secret` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, `pay_dealid` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, `pay_public_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, `pay_private_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, `pay_app_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;
CREATE TABLE `zjhj_bd_bdapp_order` ( `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '订单号', `bd_user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '', `bd_order_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '百度平台订单ID', `bd_refund_batch_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '百度平台退款批次号', `bd_refund_money` int(11) NOT NULL DEFAULT 0, `refund_money` decimal(10, 2) NOT NULL DEFAULT 0.00, `is_refund` tinyint(4) NOT NULL DEFAULT 0, `created_at` timestamp(0) NOT NULL, `updated_at` timestamp(0) NOT NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '百度订单号与商城订单号关联表' ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_bdapp_template` ( `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `tpl_name` varchar(65) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '', `tpl_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '', `created_at` timestamp(0) NOT NULL, `updated_at` timestamp(0) NOT NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_ttapp_config` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `mch_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户号', `app_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, `app_secret` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, `pay_app_secret` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, `pay_app_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, `alipay_app_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, `alipay_public_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, `alipay_private_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, `created_at` timestamp(0) NULL DEFAULT NULL, `updated_at` timestamp(0) NULL DEFAULT NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;
CREATE TABLE `zjhj_bd_ttapp_template` ( `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `tpl_name` varchar(65) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '', `tpl_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '', `created_at` timestamp(0) NOT NULL, `updated_at` timestamp(0) NOT NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
EOF;
        sql_execute($sql);
    },

    '4.1.1' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_address` ADD COLUMN `latitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '经度' AFTER `deleted_at`, ADD COLUMN `longitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '纬度' AFTER `latitude`, ADD COLUMN `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '位置' AFTER `longitude`;
EOF;
        sql_execute($sql);
    },

    '4.1.2' => function () {
    },

    '4.1.3' => function () {
    },

    '4.1.4' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_bonus_order_log` CHANGE `bonus_rate` `bonus_rate` VARCHAR(32) DEFAULT '0'  NOT NULL   COMMENT '下单时的分红比例%';
EOF;
        sql_execute($sql);
    },

    '4.1.5' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_order` ADD COLUMN `location` varchar(255) NULL, ADD COLUMN `city_name` varchar(255) NULL, ADD COLUMN `city_info` varchar(255) NULL;
ALTER TABLE `zjhj_bd_order` CHANGE COLUMN `is_offline` `send_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '配送方式：0--快递配送 1--到店自提 2--同城配送';
CREATE TABLE `zjhj_bd_city_deliveryman` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT '0', `mch_id` int(11) NOT NULL DEFAULT '0', `name` varchar(255) NOT NULL COMMENT '配送员名称', `mobile` varchar(255) NOT NULL COMMENT '联系方式', `is_delete` tinyint(1) NOT NULL, `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },

    '4.1.6' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_topic` ADD COLUMN `pic_list`  longtext NULL, ADD COLUMN `detail`  longtext NULL, ADD COLUMN `abstract`  varchar(255) NOT NULL DEFAULT '' COMMENT '摘要';
EOF;
        sql_execute($sql);
    },

    '4.1.7' => function () {
        $sql = <<<EOF
alter table `zjhj_bd_pintuan_order_relation` add cancel_status tinyint(1) not NULL default '0' COMMENT '拼团订单取消状态:0.未取消|1.超出拼团总人数取消';
EOF;
        sql_execute($sql);
    },

    '4.1.8' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_advance_banner` ( `id` int(11) NOT NULL AUTO_INCREMENT, `banner_id` int(11) NOT NULL, `mall_id` int(11) NOT NULL, `is_delete` tinyint(1) NOT NULL, `created_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP, `deleted_at` timestamp NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='预售轮播图';
CREATE TABLE `zjhj_bd_advance_goods` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `goods_id` int(11) NOT NULL, `mall_id` int(11) NOT NULL, `ladder_rules` varchar(4096) NOT NULL DEFAULT '' COMMENT '阶梯规则', `deposit` decimal(10,2) NOT NULL DEFAULT '0.00', `swell_deposit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '定金膨胀金', `start_prepayment_at` timestamp NOT NULL COMMENT '预售开始时间', `end_prepayment_at` timestamp NOT NULL COMMENT '预售结束时间', `pay_limit` int(11) NOT NULL COMMENT '尾款支付时间 -1:无限制', `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE `zjhj_bd_advance_goods_attr` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `deposit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品所需定金', `swell_deposit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '定金膨胀金', `goods_id` int(11) NOT NULL, `goods_attr_id` int(11) NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', `advance_num` int(11) NOT NULL DEFAULT '0' COMMENT '预约数量', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE `zjhj_bd_advance_order` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `goods_id` int(11) NOT NULL COMMENT '商品ID', `goods_attr_id` int(11) NOT NULL COMMENT '规格ID', `goods_num` int(11) NOT NULL DEFAULT '0', `order_id` int(11) NOT NULL DEFAULT '0', `order_no` varchar(255) NOT NULL DEFAULT '0', `advance_no` varchar(255) NOT NULL COMMENT '定金订单号', `deposit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '定金', `swell_deposit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '膨胀金', `is_cancel` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1取消', `cancel_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `is_refund` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1退款', `is_delete` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1删除', `is_pay` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否支付：0.未支付|1.已支付', `is_recycle` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加入回收站 0.否|1.是', `pay_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付方式：1.在线支付 2.货到付款 3.余额支付', `pay_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注', `auto_cancel_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '自动取消时间', `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `goods_info` longtext NOT NULL, `token` varchar(32) NOT NULL, `order_token` varchar(32) DEFAULT NULL, `preferential_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '活动优惠金额', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_advance_order_submit_result` ( `id` int(11) NOT NULL AUTO_INCREMENT, `token` varchar(32) NOT NULL, `data` longtext, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_advance_setting` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `is_advance` tinyint(1) NOT NULL DEFAULT '1', `payment_type` text NOT NULL, `deposit_payment_type` varchar(255) NOT NULL DEFAULT '', `is_share` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启分销', `is_sms` tinyint(1) NOT NULL DEFAULT '0', `is_mail` tinyint(1) NOT NULL DEFAULT '0', `is_print` tinyint(1) NOT NULL DEFAULT '0', `is_territorial_limitation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启区域允许购买', `goods_poster` longtext NOT NULL, `send_type` varchar(255) NOT NULL DEFAULT '' COMMENT '发货方式', `over_time` int(11) NOT NULL DEFAULT '0' COMMENT '未支付定金订单超时时间', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
EOF;
        sql_execute($sql);
    },

    '4.1.9' => function () {
    },

    '4.1.10' => function () {
    },

    '4.1.11' => function () {
        $sql = <<<EOF
-- 首页接口索引优化
ALTER TABLE `zjhj_bd_goods_cats` ADD INDEX `index1`(`is_delete`,`status`,`is_show`,`mch_id`,`mall_id`);
ALTER TABLE `zjhj_bd_goods_member_price` ADD INDEX `index1`(`is_delete`,`goods_id`,`level`);
ALTER TABLE `zjhj_bd_miaosha_goods` ADD INDEX `index1`(`is_delete`,`open_date`,`open_time`);
ALTER TABLE `zjhj_bd_mall_goods` ADD INDEX `index1`(`goods_id`);
ALTER TABLE `zjhj_bd_miaosha_goods` ADD INDEX `index2`(`is_delete`,`goods_id`);
ALTER TABLE `zjhj_bd_goods` ADD INDEX `index1`(`mall_id`,`is_delete`,`sign`,`status`,`goods_warehouse_id`);
ALTER TABLE `zjhj_bd_order` ADD INDEX `index1`(`mall_id`,`is_delete`,`is_pay`,`pay_type`,`cancel_status`);
ALTER TABLE `zjhj_bd_goods` ADD INDEX `index2`(`is_delete`,`mall_id`,`status`);

-- 超级会员卡
CREATE TABLE `zjhj_bd_vip_card` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `name` varchar(255) NOT NULL DEFAULT '' COMMENT '会员卡名称', `cover` varchar(2048) NOT NULL DEFAULT '' COMMENT '卡片样式', `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:指定商品类别 1:指定商品 2:全场通用', `type_info` varchar(2048) NOT NULL DEFAULT '', `discount` decimal(11,1) NOT NULL DEFAULT '0.0' COMMENT '折扣', `is_discount` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:关闭 1开启', `is_free_delivery` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:不包邮 1:包邮', `status` tinyint(1) NOT NULL DEFAULT '0', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_vip_card_appoint_goods` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `goods_id` int(11) NOT NULL, `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_vip_card_cards` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `detail_id` int(11) NOT NULL COMMENT 'vip卡id', `card_id` int(11) NOT NULL COMMENT '卡券id', `send_num` int(11) NOT NULL COMMENT '赠送数量', `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
CREATE TABLE `zjhj_bd_vip_card_coupons` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `detail_id` int(11) NOT NULL, `coupon_id` int(11) NOT NULL, `send_num` int(11) NOT NULL COMMENT '赠送数量', `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
CREATE TABLE `zjhj_bd_vip_card_detail` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `vip_id` int(11) NOT NULL, `name` varchar(255) NOT NULL COMMENT '标题', `cover` varchar(2048) NOT NULL DEFAULT '' COMMENT '子卡封面', `expire_day` int(11) NOT NULL, `price` decimal(10,2) NOT NULL, `num` int(11) NOT NULL DEFAULT '0' COMMENT '库存', `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序', `send_integral_num` int(11) NOT NULL DEFAULT '0' COMMENT '积分赠送', `send_integral_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '积分赠送类型 1.固定值|2.百分比', `send_balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '赠送余额', `title` varchar(255) NOT NULL DEFAULT '' COMMENT '使用说明', `content` varchar(2048) NOT NULL DEFAULT '' COMMENT '使用内容', `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:正常 1：停发', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_vip_card_discount` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `order_id` int(11) NOT NULL, `order_detail_id` int(11) NOT NULL, `main_id` int(11) NOT NULL DEFAULT '0', `main_name` varchar(255) NOT NULL DEFAULT '' COMMENT '主卡名称', `detail_id` int(11) NOT NULL DEFAULT '0', `detail_name` varchar(255) NOT NULL DEFAULT '' COMMENT '子卡名称', `discount_num` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '折扣', `discount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '折扣优惠', `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_vip_card_order` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `order_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `main_id` int(11) NOT NULL COMMENT '主卡id', `main_name` varchar(255) NOT NULL DEFAULT '' COMMENT '主卡名称', `detail_id` int(11) NOT NULL, `detail_name` varchar(255) NOT NULL DEFAULT '' COMMENT '子卡名称', `price` decimal(10,2) NOT NULL COMMENT '购买价格', `expire` int(11) NOT NULL COMMENT '有效期', `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未售 1已售', `all_send` varchar(2048) NOT NULL DEFAULT '', `is_admin_add` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否后台添加', `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP, `updated_at` timestamp NULL DEFAULT NULL, `deleted_at` timestamp NULL DEFAULT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
CREATE TABLE `zjhj_bd_vip_card_setting` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `is_vip_card` tinyint(1) NOT NULL DEFAULT '0', `payment_type` text NOT NULL, `is_share` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启分销', `is_sms` tinyint(1) NOT NULL DEFAULT '0', `is_mail` tinyint(1) NOT NULL DEFAULT '0', `is_agreement` tinyint(1) NOT NULL DEFAULT '0', `agreement_title` varchar(255) NOT NULL DEFAULT '', `agreement_content` text NOT NULL, `is_buy_become_share` tinyint(1) NOT NULL DEFAULT '0' COMMENT '购买成为分销商 0:关闭 1开启', `share_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1.百分比|2.固定金额', `share_commission_first` decimal(10,2) NOT NULL DEFAULT '0.00', `share_commission_second` decimal(10,2) NOT NULL DEFAULT '0.00', `share_commission_third` decimal(10,2) NOT NULL DEFAULT '0.00', `form` text NOT NULL, `rules` text NOT NULL COMMENT '允许的插件', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
CREATE TABLE `zjhj_bd_vip_card_user` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `main_id` int(11) NOT NULL DEFAULT '0', `detail_id` int(11) NOT NULL, `image_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:指定商品类别 1:指定商品 2:全场通用', `image_type_info` varchar(2048) NOT NULL DEFAULT '', `image_discount` decimal(11,1) NOT NULL DEFAULT '0.0' COMMENT '折扣', `image_is_free_delivery` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:不包邮 1:包邮', `image_main_name` varchar(255) NOT NULL DEFAULT '' COMMENT '主卡名称', `image_name` varchar(255) NOT NULL COMMENT '名称', `all_send` varchar(2048) NOT NULL DEFAULT '' COMMENT '所有赠送信息', `data` longtext COMMENT '额外信息字段', `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, PRIMARY KEY (`id`), KEY `mall_id` (`mall_id`) USING BTREE, KEY `user_id` (`user_id`) USING BTREE ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
EOF;
        sql_execute($sql);
    },

    '4.1.14' => function () {
    },


    '4.1.16' => function () {
    },

    '4.1.17' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_bargain_order` CHANGE `created_at` `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `bargain_goods_data`;
EOF;
        sql_execute($sql);
    },

    '4.2.0' => function () {
        $sql = <<<EOF
alter table `zjhj_bd_goods` add `confine_order_count` int(11) not NULL default '-1';
alter table `zjhj_bd_cart` add `attr_info` text;
alter table `zjhj_bd_order_refund` add `refund_time` TIMESTAMP not NULL default '0000-00-00 00:00:00';
alter table `zjhj_bd_order_refund` add `is_refund` tinyint(1) not NULL default '2' COMMENT '是否打款，2代表旧数据';
alter table `zjhj_bd_order_detail` add `goods_no` varchar(60) not NULL default '' comment '商品货号';
CREATE TABLE `zjhj_bd_order_detail_express` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `mch_id` int(11) NOT NULL, `order_id` int(11) NOT NULL COMMENT '订单ID', `express` varchar(65) NOT NULL DEFAULT '', `send_type` tinyint(1) NOT NULL COMMENT '1.快递|2.其它方式', `express_no` varchar(255) NOT NULL DEFAULT '', `merchant_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '商家留言', `express_content` varchar(255) NOT NULL DEFAULT '' COMMENT '物流内容', `is_delete` tinyint(4) NOT NULL DEFAULT '0', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_order_detail_express_relation` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `mch_id` int(11) NOT NULL, `order_id` int(11) NOT NULL, `order_detail_id` int(11) NOT NULL, `order_detail_express_id` int(11) NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_order_comments_templates` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `mch_id` int(11) NOT NULL DEFAULT '0', `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '模板类型:1.好评|2.中评|3.差评', `title` varchar(65) NOT NULL DEFAULT '' COMMENT '标题', `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

ALTER TABLE `zjhj_bd_goods` ADD COLUMN `is_area_limit` TINYINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '是否单独区域购买' AFTER `confine_order_count`, ADD COLUMN `area_limit` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL AFTER `is_area_limit`;
ALTER TABLE `zjhj_bd_lottery_log` ADD INDEX `lottery_id` ( `lottery_id` ) USING BTREE, ADD INDEX `user_id` ( `user_id` ) USING BTREE;
ALTER TABLE `zjhj_bd_attachment_group` ADD COLUMN `is_recycle` TINYINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '是否加入回收站 0.否|1.是' AFTER `deleted_at`, ADD COLUMN `type` TINYINT ( 2 ) NOT NULL DEFAULT 0 COMMENT '0 图片 1商品' AFTER `is_recycle`;
ALTER TABLE `zjhj_bd_attachment` ADD COLUMN `is_recycle` TINYINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '是否加入回收站 0.否|1.是' AFTER `is_delete`;
EOF;
        sql_execute($sql);
    },

    '4.2.1' => function () {
    },

    '4.2.2' => function () {
    },

    '4.2.3' => function () {
        $sql = <<<EOF
ALTER TABLE zjhj_bd_order_refund ADD reality_refund_price DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0' COMMENT '商家实际退款金额';
EOF;
        sql_execute($sql);
    },

    '4.2.4' => function () {
        $sql = <<<EOF
ALTER TABLE zjhj_bd_order_refund ADD reality_refund_price DECIMAL ( 10, 2 ) NOT NULL DEFAULT '0' COMMENT '商家实际退款金额';
EOF;
        sql_execute($sql);
    },

    '4.2.5' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_quick_share_goods` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `goods_id` int(11) NOT NULL DEFAULT '0', `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态', `share_text` varchar(255) NOT NULL COMMENT '分享文本', `share_pic` longtext NOT NULL COMMENT '素材图片', `material_sort` int(11) NOT NULL DEFAULT '0' COMMENT '素材排序', `is_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶', `material_video_url` varchar(255) NOT NULL DEFAULT '' COMMENT '动态视频', `material_cover_url` varchar(255) NOT NULL DEFAULT '' COMMENT '视频封面', `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, PRIMARY KEY (`id`), KEY `goods_id` (`goods_id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_quick_share_setting` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发圈对象 仅素材 1全部商品', `goods_poster` longtext NOT NULL, `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },

    '4.2.8' => function () {
        $sql = <<<EOF
ALTER TABLE zjhj_bd_order_refund ADD merchant_express_content VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '物流内容';
EOF;
        sql_execute($sql);
    },

    '4.2.9' => function () {
        $sql = <<<EOF
ALTER TABLE zjhj_bd_goods_card_relation ADD num INT ( 11 ) NOT NULL DEFAULT 1 COMMENT '卡券数量';
EOF;
        sql_execute($sql);
    },


    '4.2.10' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_order_detail_express` ADD `customer_name` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '京东物流编号';

CREATE TABLE `zjhj_bd_gift_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT '0', `user_id` int(11) NOT NULL DEFAULT '0', `num` int(11) NOT NULL DEFAULT '0' COMMENT '礼物总数', `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `is_delete` tinyint(1) NOT NULL DEFAULT '0', `is_confirm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '送礼状态：0.未完成送礼|1.已完成送礼', `type` varchar(60) NOT NULL COMMENT '送礼方式', `open_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开奖时间', `open_num` int(11) NOT NULL DEFAULT '0' COMMENT '开奖所需人数', `open_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0一人拿奖，1多人各领一份奖', `bless_word` varchar(200) NOT NULL COMMENT '祝福语', `auto_refund_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '自动退款时间', `is_pay` tinyint(1) NOT NULL DEFAULT '0', `order_id` int(11) NOT NULL DEFAULT '0', `is_cancel` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE `zjhj_bd_gift_lottery` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT '0', `send_order_id` int(11) NOT NULL DEFAULT '0', `user_id` int(11) NOT NULL DEFAULT '0', `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `is_delete` tinyint(1) NOT NULL DEFAULT '0', `is_prize` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未中，1中奖', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `zjhj_bd_gift_open_result` ( `id` int(11) NOT NULL AUTO_INCREMENT, `token` varchar(32) NOT NULL, `data` longtext, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE `zjhj_bd_gift_order` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT '0', `order_no` varchar(255) NOT NULL DEFAULT '', `goods_id` int(11) NOT NULL DEFAULT '0', `goods_attr_id` int(11) NOT NULL DEFAULT '0', `num` int(11) NOT NULL DEFAULT '0', `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '商城订单ID', `type` varchar(60) NOT NULL COMMENT '送礼方式', `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `is_delete` tinyint(1) NOT NULL DEFAULT '0', `user_order_id` int(11) NOT NULL DEFAULT '0', `is_refund` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款，前端显示超时', `buy_order_detail_id` int(11) NOT NULL DEFAULT '0' COMMENT '买礼物的商城订单详情id', `gift_id` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE `zjhj_bd_gift_order_submit_result` ( `id` int(11) NOT NULL AUTO_INCREMENT, `token` varchar(32) NOT NULL, `data` longtext, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE `zjhj_bd_gift_send_order` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT '0', `mch_id` int(11) NOT NULL DEFAULT '0', `user_id` int(11) NOT NULL DEFAULT '0', `gift_id` int(11) NOT NULL DEFAULT '0' COMMENT 'gift_log的id', `order_no` varchar(60) NOT NULL DEFAULT '', `total_price` decimal(10,2) NOT NULL COMMENT '订单总金额(含运费)', `total_pay_price` decimal(10,2) NOT NULL COMMENT '实际支付总费用(含运费）', `is_pay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支付：0.未支付|1.已支付', `pay_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付方式：1.在线支付 2.货到付款 3.余额支付', `pay_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '支付时间', `is_refund` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未退款，1已退款', `is_confirm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '送礼状态：0.未完成送礼|1.已完成送礼', `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `is_delete` tinyint(1) NOT NULL DEFAULT '0', `support_pay_types` text NOT NULL COMMENT '支持的支付方式，空表示支持系统设置支持的所有方式', `token` varchar(32) NOT NULL, `total_goods_price` decimal(10,2) NOT NULL DEFAULT '0.00', `total_goods_original_price` decimal(10,2) NOT NULL DEFAULT '0.00', `member_discount_price` decimal(10,2) NOT NULL DEFAULT '0.00', `use_user_coupon_id` int(11) NOT NULL DEFAULT '0', `coupon_discount_price` decimal(10,2) NOT NULL DEFAULT '0.00', `use_integral_num` int(11) NOT NULL DEFAULT '0', `integral_deduction_price` decimal(10,2) NOT NULL DEFAULT '0.00', `is_cancel` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE `zjhj_bd_gift_send_order_detail` ( `id` int(11) NOT NULL AUTO_INCREMENT, `send_order_id` int(11) NOT NULL, `goods_id` int(11) NOT NULL, `goods_attr_id` int(11) NOT NULL DEFAULT '0', `goods_info` longtext COMMENT '购买商品信息', `num` int(11) NOT NULL, `unit_price` decimal(10,2) NOT NULL COMMENT '商品单价', `total_original_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品原总价(优惠前)', `total_price` decimal(10,2) NOT NULL COMMENT '商品总价(优惠后)', `member_discount_price` decimal(10,2) NOT NULL DEFAULT '0.00', `is_refund` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未退款，1已退款', `refund_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '售后状态 0--未售后 1--售后中 2--售后结束', `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `is_delete` tinyint(1) NOT NULL DEFAULT '0', `receive_num` int(11) NOT NULL DEFAULT '0' COMMENT '已领取数量', `refund_price` decimal(10,2) NOT NULL DEFAULT '0.00', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE `zjhj_bd_gift_setting` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT '0', `title` varchar(200) NOT NULL, `type` varchar(200) NOT NULL DEFAULT '[]' COMMENT '玩法', `auto_refund` int(11) NOT NULL DEFAULT '0' COMMENT '自动退款天数', `auto_remind` int(11) NOT NULL DEFAULT '0' COMMENT '送礼失败提醒天数', `bless_word` varchar(200) NOT NULL COMMENT '祝福语', `ask_gift` varchar(200) NOT NULL COMMENT '求礼物', `is_share` tinyint(1) NOT NULL DEFAULT '0', `is_sms` tinyint(1) NOT NULL DEFAULT '0', `is_mail` tinyint(1) NOT NULL DEFAULT '0', `is_print` tinyint(1) NOT NULL DEFAULT '0', `payment_type` text NOT NULL, `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `is_delete` tinyint(1) NOT NULL DEFAULT '0', `poster` longtext NOT NULL COMMENT '海报', `background` varchar(200) NOT NULL DEFAULT '[]' COMMENT '自定义背景', `theme` text NOT NULL COMMENT '主题色', `send_type` varchar(200) NOT NULL DEFAULT '[]', `explain` text NOT NULL COMMENT '规则说明', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE `zjhj_bd_gift_user_order` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT '0', `user_id` int(11) NOT NULL DEFAULT '0', `gift_id` int(11) NOT NULL DEFAULT '0', `is_turn` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否转赠0未转1已转', `turn_no` varchar(255) NOT NULL DEFAULT '' COMMENT '转赠码', `turn_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '被转赠用户ID', `is_receive` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未领取，1已领取', `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `is_delete` tinyint(1) NOT NULL DEFAULT '0', `is_win` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未中，1已中', `token` varchar(32) NOT NULL DEFAULT '', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
EOF;
        sql_execute($sql);
    },

    '4.2.11' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_booking_setting`
MODIFY COLUMN `created_at`  timestamp NOT NULL AFTER `form_data`,
MODIFY COLUMN `updated_at`  timestamp NOT NULL AFTER `created_at`;

ALTER TABLE `zjhj_bd_pond_log_coupon_relation`
MODIFY COLUMN `created_at`  timestamp NOT NULL AFTER `is_delete`,
MODIFY COLUMN `deleted_at`  timestamp NOT NULL AFTER `created_at`;


ALTER TABLE `zjhj_bd_pond_order`
MODIFY COLUMN `created_at`  timestamp NOT NULL AFTER `order_id`;

ALTER TABLE `zjhj_bd_scratch_log`
MODIFY COLUMN `deleted_at`  timestamp NOT NULL AFTER `is_delete`;

ALTER TABLE `zjhj_bd_address`
MODIFY COLUMN `created_at`  timestamp NOT NULL AFTER `is_delete`;
EOF;
        sql_execute($sql);
    },

    '4.2.12' => function () {
    },

    '4.2.13' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_footprint_data_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `key` varchar(60) NOT NULL, `value` varchar(60) NOT NULL, `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `is_delete` tinyint(1) NOT NULL DEFAULT '0', `statistics_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上一次统计的时间', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_footprint_goods_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT '0', `user_id` int(11) NOT NULL DEFAULT '0', `goods_id` int(11) NOT NULL DEFAULT '0', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

ALTER TABLE  `zjhj_bd_formid` ADD INDEX `user_id`(`user_id`);
ALTER TABLE  `zjhj_bd_formid` ADD INDEX `created_at`(`created_at`);
ALTER TABLE  `zjhj_bd_formid` ADD INDEX `remains`(`remains`);

ALTER TABLE  `zjhj_bd_goods_attr` ADD INDEX `goods_id`(`goods_id`);
ALTER TABLE  `zjhj_bd_goods_attr` ADD INDEX `is_delete`(`is_delete`);

ALTER TABLE  `zjhj_bd_user_identity` ADD INDEX `user_id`(`user_id`);

ALTER TABLE  `zjhj_bd_check_in_user` ADD INDEX `user_id`(`user_id`);
ALTER TABLE  `zjhj_bd_check_in_user` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_check_in_user` ADD INDEX `is_delete`(`is_delete`);

ALTER TABLE  `zjhj_bd_attachment` ADD INDEX `attachment_group_id`(`attachment_group_id`);
ALTER TABLE  `zjhj_bd_attachment` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_attachment` ADD INDEX `mch_id`(`mch_id`);
ALTER TABLE  `zjhj_bd_attachment` ADD INDEX `type`(`type`);

ALTER TABLE  `zjhj_bd_attachment_group` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_attachment_group` ADD INDEX `mch_id`(`mch_id`);
ALTER TABLE  `zjhj_bd_attachment_group` ADD INDEX `type`(`type`);

ALTER TABLE  `zjhj_bd_balance_log` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_balance_log` ADD INDEX `user_id`(`user_id`);
ALTER TABLE  `zjhj_bd_balance_log` ADD INDEX `type`(`type`);

ALTER TABLE  `zjhj_bd_bargain_user_order` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_bargain_user_order` ADD INDEX `user_id`(`user_id`);
ALTER TABLE  `zjhj_bd_bargain_user_order` ADD INDEX `bargain_order_id`(`bargain_order_id`);

ALTER TABLE  `zjhj_bd_bonus_captain_log` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_bonus_captain_log` ADD INDEX `user_id`(`user_id`);

ALTER TABLE  `zjhj_bd_cart` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_cart` ADD INDEX `user_id`(`user_id`);

ALTER TABLE  `zjhj_bd_check_in_sign` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_check_in_sign` ADD INDEX `user_id`(`user_id`);

ALTER TABLE  `zjhj_bd_check_in_user_remind` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_check_in_user_remind` ADD INDEX `user_id`(`user_id`);
ALTER TABLE  `zjhj_bd_check_in_user_remind` ADD INDEX `is_remind`(`is_remind`);

ALTER TABLE  `zjhj_bd_core_queue_data` ADD INDEX `queue_id`(`queue_id`);
ALTER TABLE  `zjhj_bd_core_queue_data` ADD INDEX `token`(`token`);

ALTER TABLE  `zjhj_bd_coupon_mall_relation` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_coupon_mall_relation` ADD INDEX `user_coupon_id`(`user_coupon_id`);

ALTER TABLE  `zjhj_bd_goods_cats` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_goods_cats` ADD INDEX `mch_id`(`mch_id`);
ALTER TABLE  `zjhj_bd_goods_cats` ADD INDEX `parent_id`(`parent_id`);

ALTER TABLE  `zjhj_bd_goods_cat_relation` ADD INDEX `cat_id`(`cat_id`);

ALTER TABLE  `zjhj_bd_goods_member_price` ADD INDEX `goods_id`(`goods_id`);

ALTER TABLE  `zjhj_bd_integral_log` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_integral_log` ADD INDEX `user_id`(`user_id`);
ALTER TABLE  `zjhj_bd_integral_log` ADD INDEX `type`(`type`);

ALTER TABLE  `zjhj_bd_integral_mall_goods_attr` ADD INDEX `goods_id`(`goods_id`);
ALTER TABLE  `zjhj_bd_integral_mall_goods_attr` ADD INDEX `goods_attr_id`(`goods_attr_id`);

ALTER TABLE  `zjhj_bd_lottery_log` ADD INDEX `mall_id`(`mall_id`);

ALTER TABLE  `zjhj_bd_mall_goods` ADD INDEX `mall_id`(`mall_id`);

ALTER TABLE  `zjhj_bd_mall_setting` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_mall_setting` ADD INDEX `key`(`key`);

ALTER TABLE  `zjhj_bd_miaosha_goods` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_miaosha_goods` ADD INDEX `goods_id`(`goods_id`);
ALTER TABLE  `zjhj_bd_miaosha_goods` ADD INDEX `goods_warehouse_id`(`goods_warehouse_id`);

ALTER TABLE  `zjhj_bd_option` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_option` ADD INDEX `mch_id`(`mch_id`);
ALTER TABLE  `zjhj_bd_option` ADD INDEX `group`(`group`);
ALTER TABLE  `zjhj_bd_option` ADD INDEX `name`(`name`);

ALTER TABLE  `zjhj_bd_order` ADD INDEX `order_no`(`order_no`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `is_pay`(`is_pay`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `is_send`(`is_send`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `is_sale`(`is_sale`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `is_confirm`(`is_confirm`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `is_recycle`(`is_recycle`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `token`(`token`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `is_comment`(`is_comment`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `status`(`status`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `sale_status`(`sale_status`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `sign`(`sign`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `clerk_id`(`clerk_id`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `store_id`(`store_id`);
ALTER TABLE  `zjhj_bd_order` ADD INDEX `cancel_status`(`cancel_status`);

ALTER TABLE  `zjhj_bd_order_detail_express` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_order_detail_express` ADD INDEX `mch_id`(`mch_id`);
ALTER TABLE  `zjhj_bd_order_detail_express` ADD INDEX `order_id`(`order_id`);
ALTER TABLE  `zjhj_bd_order_detail_express` ADD INDEX `send_type`(`send_type`);

ALTER TABLE  `zjhj_bd_order_pay_result` ADD INDEX `order_id`(`order_id`);

ALTER TABLE  `zjhj_bd_payment_order` ADD INDEX `payment_order_union_id`(`payment_order_union_id`);
ALTER TABLE  `zjhj_bd_payment_order` ADD INDEX `order_no`(`order_no`);
ALTER TABLE  `zjhj_bd_payment_order` ADD INDEX `is_pay`(`is_pay`);
ALTER TABLE  `zjhj_bd_payment_order` ADD INDEX `pay_type`(`pay_type`);

ALTER TABLE  `zjhj_bd_payment_order_union` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_payment_order_union` ADD INDEX `user_id`(`user_id`);
ALTER TABLE  `zjhj_bd_payment_order_union` ADD INDEX `order_no`(`order_no`);
ALTER TABLE  `zjhj_bd_payment_order_union` ADD INDEX `is_pay`(`is_pay`);
ALTER TABLE  `zjhj_bd_payment_order_union` ADD INDEX `pay_type`(`pay_type`);

ALTER TABLE  `zjhj_bd_pond_log` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_pond_log` ADD INDEX `pond_id`(`pond_id`);
ALTER TABLE  `zjhj_bd_pond_log` ADD INDEX `user_id`(`user_id`);
ALTER TABLE  `zjhj_bd_pond_log` ADD INDEX `status`(`status`);
ALTER TABLE  `zjhj_bd_pond_log` ADD INDEX `type`(`type`);
ALTER TABLE  `zjhj_bd_pond_log` ADD INDEX `goods_id`(`goods_id`);
ALTER TABLE  `zjhj_bd_pond_log` ADD INDEX `order_id`(`order_id`);

ALTER TABLE  `zjhj_bd_quick_share_goods` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_quick_share_goods` ADD INDEX `goods_id`(`goods_id`);
ALTER TABLE  `zjhj_bd_quick_share_goods` ADD INDEX `status`(`status`);
ALTER TABLE  `zjhj_bd_quick_share_goods` ADD INDEX `is_top`(`is_top`);

ALTER TABLE  `zjhj_bd_share` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_share` ADD INDEX `user_id`(`user_id`);

ALTER TABLE  `zjhj_bd_share_cash_log` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_share_cash_log` ADD INDEX `user_id`(`user_id`);
ALTER TABLE  `zjhj_bd_share_cash_log` ADD INDEX `type`(`type`);

ALTER TABLE  `zjhj_bd_share_order` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_share_order` ADD INDEX `order_id`(`order_id`);
ALTER TABLE  `zjhj_bd_share_order` ADD INDEX `order_detail_id`(`order_detail_id`);
ALTER TABLE  `zjhj_bd_share_order` ADD INDEX `user_id`(`user_id`);
ALTER TABLE  `zjhj_bd_share_order` ADD INDEX `first_parent_id`(`first_parent_id`);
ALTER TABLE  `zjhj_bd_share_order` ADD INDEX `second_parent_id`(`second_parent_id`);
ALTER TABLE  `zjhj_bd_share_order` ADD INDEX `third_parent_id`(`third_parent_id`);

ALTER TABLE  `zjhj_bd_share_setting` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_share_setting` ADD INDEX `key`(`key`);

ALTER TABLE  `zjhj_bd_shopping_buys` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_shopping_buys` ADD INDEX `order_id`(`order_id`);
ALTER TABLE  `zjhj_bd_shopping_buys` ADD INDEX `user_id`(`user_id`);

ALTER TABLE  `zjhj_bd_template_record` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_template_record` ADD INDEX `user_id`(`user_id`);
ALTER TABLE  `zjhj_bd_template_record` ADD INDEX `status`(`status`);

ALTER TABLE  `zjhj_bd_user` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_user` ADD INDEX `mch_id`(`mch_id`);
ALTER TABLE  `zjhj_bd_user` ADD INDEX `username`(`username`);
ALTER TABLE  `zjhj_bd_user` ADD INDEX `access_token`(`access_token`);

ALTER TABLE  `zjhj_bd_user_coupon_auto` ADD INDEX `user_coupon_id`(`user_coupon_id`);
ALTER TABLE  `zjhj_bd_user_coupon_auto` ADD INDEX `auto_coupon_id`(`auto_coupon_id`);

ALTER TABLE  `zjhj_bd_user_coupon_center` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE  `zjhj_bd_user_coupon_center` ADD INDEX `user_coupon_id`(`user_coupon_id`);
ALTER TABLE  `zjhj_bd_user_coupon_center` ADD INDEX `user_id`(`user_id`);

ALTER TABLE  `zjhj_bd_user_info` ADD INDEX `platform_user_id`(`platform_user_id`);
ALTER TABLE  `zjhj_bd_user_info` ADD INDEX `temp_parent_id`(`temp_parent_id`);
EOF;
        sql_execute($sql);
    },

    '4.2.14' => function () {
    },

    '4.2.15' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_lottery`
MODIFY COLUMN `start_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间' AFTER `stock`,
MODIFY COLUMN `end_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间' AFTER `start_at`;
EOF;
        sql_execute($sql);
    },

    '4.2.17' => function () {
    },

    '4.2.18' => function () {
    },

    '4.2.19' => function () {
    },

    '4.2.20' => function () {
        $sql = <<<EOF
alter table `zjhj_bd_order_detail_express` add `express_single_id` int(11) not null default 0 comment '电子面单ID';

ALTER TABLE `zjhj_bd_goods_cats`
ADD COLUMN `advert_open_type`  varchar(65) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '打开方式' AFTER `is_show`,
ADD COLUMN `advert_params`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '导航参数' AFTER `advert_open_type`;
EOF;
        sql_execute($sql);
    },

    '4.2.21' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_order_detail` ADD COLUMN `form_data` longtext NULL COMMENT '自定义表单提交的数据' AFTER `goods_no`;
ALTER TABLE `zjhj_bd_order_detail` ADD COLUMN `form_id` int(11) NOT NULL DEFAULT 0 COMMENT '自定义表单的id' AFTER `form_data`;
ALTER TABLE `zjhj_bd_coupon_auto_send` ADD COLUMN `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '领取人 0--所有人 1--指定用户', ADD COLUMN `user_list` longtext NULL COMMENT '指定用户id列表';
ALTER TABLE `zjhj_bd_share` ADD COLUMN `level` int(11) NOT NULL DEFAULT 0 COMMENT '分销商等级', ADD COLUMN `level_at` timestamp NULL DEFAULT '' COMMENT '成为分销商等级时间', ADD COLUMN `delete_first_show` tinyint(1) NOT NULL DEFAULT 0 COMMENT '删除后是否第一次展示';
CREATE TABLE `zjhj_bd_share_level` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `level` int(11) NOT NULL DEFAULT '1' COMMENT '分销等级1~100', `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分销等级名称', `condition_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '升级条件：1--下线用户数|2--累计佣金|3--已提现佣金', `condition` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '下线用户数（人）|累计佣金数（元）|已提现佣金数（元）', `price_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '分销佣金类型：1--百分比|2--固定金额', `first` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '一级分销佣金数（元）', `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用', `is_delete` tinyint(1) NOT NULL DEFAULT '0', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `second` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '二级分销佣金数（元）', `third` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '三级分销佣金数（元）', `is_auto_level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用自动升级', `rule` varchar(255) NOT NULL DEFAULT '' COMMENT '等级说明', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_goods` ADD COLUMN `form_id` int NOT NULL DEFAULT 0 COMMENT '自定义表单id 0--表示默认表单 -1--表示不使用表单';
ALTER TABLE `zjhj_bd_goods_share` ADD COLUMN `level` int(11) NOT NULL DEFAULT 0 COMMENT '分销商等级';
ALTER TABLE `zjhj_bd_pintuan_goods_share` ADD COLUMN `level` int(11) NOT NULL DEFAULT 0 COMMENT '分销商等级';
CREATE TABLE `zjhj_bd_form` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT '0', `mch_id` int(11) NOT NULL DEFAULT '0', `name` varchar(255) NOT NULL DEFAULT '', `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用', `data` longtext NOT NULL COMMENT '表单内容', `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认', `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_user_coupon` ADD COLUMN `discount_limit` decimal(10,2) NULL DEFAULT NULL COMMENT '折扣优惠上限';
ALTER TABLE `zjhj_bd_coupon` ADD COLUMN `discount_limit` decimal(10,2) NULL DEFAULT NULL COMMENT '折扣优惠上限';
ALTER TABLE `zjhj_bd_vip_card_setting` ADD COLUMN `share_level` text COMMENT '分销等级';
EOF;
        sql_execute($sql);
    },

    '4.2.22' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_goods_warehouse` MODIFY COLUMN `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '商品名称' AFTER `mall_id`;
ALTER TABLE `zjhj_bd_share` ADD COLUMN `level` int(11) NOT NULL DEFAULT 0 COMMENT '分销商等级', ADD COLUMN `level_at` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '成为分销商等级时间', ADD COLUMN `delete_first_show` tinyint(1) NOT NULL DEFAULT 0 COMMENT '删除后是否第一次展示';
EOF;
        sql_execute($sql);
    },

    '4.2.23' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_form` ADD COLUMN `value` longtext NOT NULL;
EOF;
        sql_execute($sql);
    },

    '4.2.24' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_form` MODIFY COLUMN `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '表单内容';
EOF;
        sql_execute($sql);
    },

    '4.2.25' => function () {
    },

    '4.2.27' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_order_detail` MODIFY COLUMN `form_id` int(11) NULL DEFAULT 0 COMMENT '自定义表单的id' AFTER `form_data`;
EOF;
        sql_execute($sql);
    },

    '4.2.28' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_wxapp_subscribe` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `tpl_name` varchar(65) NOT NULL DEFAULT '', `tpl_id` varchar(255) NOT NULL DEFAULT '', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信订阅消息';
EOF;
        sql_execute($sql);
    },

    '4.2.29' => function () {
    },

    '4.2.30' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_core_template` ( `id` int(11) NOT NULL AUTO_INCREMENT, `template_id` int(11) NOT NULL DEFAULT '0' COMMENT '模板id', `name` varchar(255) NOT NULL DEFAULT '' COMMENT '模板名称', `author` varchar(255) NOT NULL DEFAULT '' COMMENT '作者', `price` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '价格', `pics` longtext NOT NULL, `data` longtext NOT NULL COMMENT '数据', `order_no` varchar(255) NOT NULL DEFAULT '' COMMENT '订单号', `version` varchar(255) NOT NULL DEFAULT '' COMMENT '版本号', `type` varchar(255) NOT NULL DEFAULT '' COMMENT 'home--首页布局 diy--DIY模板', `detail` longtext NOT NULL, `is_delete` tinyint(1) NOT NULL, `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_core_template_edit` ( `id` int(11) NOT NULL AUTO_INCREMENT, `template_id` int(11) NOT NULL DEFAULT '0' COMMENT '模板id', `name` varchar(255) NOT NULL DEFAULT '' COMMENT '修改后名称', `price` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '修改后价格', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_address` ADD INDEX `user_id`(`user_id`);
ALTER TABLE `zjhj_bd_vip_card_setting` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_vip_card_appoint_goods` ADD INDEX `goods_id`(`goods_id`);
ALTER TABLE `zjhj_bd_mall_members` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_mall_members` ADD INDEX `level`(`level`);
ALTER TABLE `zjhj_bd_printer_setting` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_printer_setting` ADD INDEX `mch_id`(`mch_id`);
ALTER TABLE `zjhj_bd_printer_setting` ADD INDEX `status`(`status`);
ALTER TABLE `zjhj_bd_printer_setting` ADD INDEX `store_id`(`store_id`);
EOF;
        sql_execute($sql);
    },

    '4.2.31' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_import_goods` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `mch_id` int(11) NOT NULL DEFAULT '0', `user_id` int(11) NOT NULL COMMENT '操作账户ID', `status` tinyint(4) NOT NULL COMMENT '导入状态|1.全部失败|2.部分失败|3.全部成功', `file_name` varchar(191) NOT NULL DEFAULT '' COMMENT '导入文件名', `goods_count` int(11) NOT NULL, `success_count` int(11) NOT NULL, `error_count` int(11) NOT NULL, `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_delete` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
alter table `zjhj_bd_order_comments` add `is_top` tinyint(1) default 0 not null comment '是否置顶0.否|1.是';
EOF;
        sql_execute($sql);
    },

    '4.2.32' => function () {
    },

    '4.2.35' => function () {
    },

    '4.2.36' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_gift_log` ADD COLUMN `bless_music` VARCHAR(200) NULL COMMENT '祝福语音' AFTER `bless_word`;
EOF;
        sql_execute($sql);
    },

    '4.2.38' => function () {
        $sql = <<<EOF
alter table zjhj_bd_goods change app_share_title app_share_title varchar(65) character set utf8mb4 not null default '' comment '自定义分享标题';
ALTER TABLE `zjhj_bd_advance_goods` CHARACTER SET = utf8mb4, COLLATE = utf8mb4_general_ci;
ALTER TABLE `zjhj_bd_advance_goods_attr` CHARACTER SET = utf8mb4, COLLATE = utf8mb4_general_ci;
CREATE TABLE `zjhj_bd_stock_bonus_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT 0, `bonus_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1按周，2按月', `bonus_price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '分红金额', `bonus_rate` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '当时的分红比例', `order_num` int(11) NOT NULL DEFAULT 0 COMMENT '分红订单数', `stock_num` int(11) NOT NULL DEFAULT 0 COMMENT '当时股东人数', `start_time` timestamp(0) NOT NULL COMMENT '分红时间段-开始时间', `end_time` timestamp(0) NOT NULL COMMENT '分红时间段-结束时间', `created_at` timestamp(0) NOT NULL, `updated_at` timestamp(0) NOT NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_stock_cash` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '订单号', `price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '提现金额', `service_charge` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '提现手续费（%）', `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '提现方式 auto--自动打款 wechat--微信打款 alipay--支付宝打款 bank--银行转账 balance--打款到余额', `extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '额外信息 例如微信账号、支付宝账号等', `status` int(11) NOT NULL DEFAULT 0 COMMENT '提现状态 0--申请 1--同意 2--已打款 3--驳回', `is_delete` int(11) NOT NULL DEFAULT 0, `created_at` datetime(0) NOT NULL, `updated_at` datetime(0) NOT NULL, `deleted_at` datetime(0) NOT NULL, `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '提现记录表' ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_stock_cash_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `type` int(11) NOT NULL DEFAULT 1 COMMENT '类型 1--收入 2--支出', `price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变动佣金', `desc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, `custom_desc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, `level_id` int(11) NULL DEFAULT 0 COMMENT '当时的股东等级', `level_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, `order_num` int(11) NULL DEFAULT 0, `bonus_rate` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '当时的分红比例', `bonus_id` int(11) NULL DEFAULT 0 COMMENT '股东完成分红记录ID', `is_delete` int(11) NOT NULL DEFAULT 0, `created_at` datetime(0) NOT NULL, `updated_at` datetime(0) NOT NULL, `deleted_at` datetime(0) NOT NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分红日志' ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_stock_level` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT 0, `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '等级名称', `bonus_rate` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '分红比例', `condition` int(11) NOT NULL DEFAULT 0 COMMENT '升级条件，0不自动升级', `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否默认等级，0否1是', `is_delete` tinyint(1) NOT NULL DEFAULT 0, `deleted_at` timestamp(0) NOT NULL, `created_at` timestamp(0) NOT NULL, `updated_at` timestamp(0) NOT NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '股东等级表' ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_stock_level_up` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT 0, `type` tinyint(2) NOT NULL DEFAULT 1 COMMENT '1下线总人数，2累计佣金总额，3已提现佣金总额，4分销订单总数，5分销订单总金额', `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '股东等级升级条件' ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_stock_order` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT 0, `order_id` int(11) NOT NULL DEFAULT 0, `total_pay_price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '订单实付金额', `is_bonus` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1已分红，0未分红', `bonus_time` timestamp(0) NOT NULL COMMENT '分红时间', `bonus_id` int(11) NOT NULL DEFAULT 0 COMMENT '股东完成分红记录ID', `is_delete` tinyint(1) NOT NULL DEFAULT 0, `deleted_at` timestamp(0) NOT NULL, `created_at` timestamp(0) NOT NULL, `updated_at` timestamp(0) NOT NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分红池' ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_stock_setting` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `created_at` timestamp(0) NOT NULL ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '创建时间', `updated_at` timestamp(0) NOT NULL ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间', `is_delete` int(11) NOT NULL DEFAULT 0 COMMENT '是否删除 0--未删除 1--已删除', `deleted_at` timestamp(0) NOT NULL ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '删除时间', PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '股东分红设置' ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_stock_user` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL DEFAULT 0, `user_id` int(11) NOT NULL DEFAULT 0, `level_id` int(11) NOT NULL DEFAULT 0 COMMENT '对应等级表ID', `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '-2被拒或移除后再次申请没提交 -1移除 0审核中，1同意，2拒绝', `is_delete` tinyint(1) NOT NULL DEFAULT 0, `deleted_at` timestamp(0) NOT NULL, `created_at` timestamp(0) NOT NULL, `updated_at` timestamp(0) NOT NULL, `applyed_at` timestamp(0) NOT NULL COMMENT '申请时间', `agreed_at` timestamp(0) NOT NULL COMMENT '审核时间', PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '股东表' ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_stock_user_info` ( `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL DEFAULT 0, `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '股东姓名', `phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '股东手机号', `all_bonus` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '累计分红', `total_bonus` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '当前分红', `out_bonus` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '已提现分红', `remark` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注', `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '拒绝理由', `created_at` timestamp(0) NOT NULL, `updated_at` timestamp(0) NOT NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '股东信息表' ROW_FORMAT = Dynamic;
EOF;
        sql_execute($sql);
    },

    '4.2.39' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_bonus_setting` 
MODIFY COLUMN `updated_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间' AFTER `created_at`,
MODIFY COLUMN `deleted_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '删除时间' AFTER `is_delete`;
ALTER TABLE `zjhj_bd_order` ADD INDEX `index2`(`mall_id`, `is_delete`, `cancel_status`);
ALTER TABLE `zjhj_bd_order` ADD INDEX `index3`(`mall_id`, `is_delete`, `cancel_status`, `is_pay`);
ALTER TABLE `zjhj_bd_order` ADD INDEX `index4`(`mall_id`, `is_delete`, `cancel_status`, `pay_type`);
ALTER TABLE `zjhj_bd_order` ADD INDEX `index5`(`mall_id`, `is_delete`, `cancel_status`, `is_pay`, `pay_type`);
ALTER TABLE `zjhj_bd_order_detail` ADD INDEX `index1`(`goods_id`, `is_refund`, `order_id`);
EOF;
        sql_execute($sql);
    },

    '4.2.40' => function () {
        $sql = <<<EOF
alter table zjhj_bd_store add `is_all_day` tinyint(1) not null default 0 comment '是否全天营业0.否|1.是';
EOF;
        sql_execute($sql);
    },

    '4.2.42' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_stock_bonus_log` MODIFY COLUMN `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '分红时间段-开始时间' AFTER `stock_num`, MODIFY COLUMN `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '分红时间段-结束时间' AFTER `start_time`;
ALTER TABLE `zjhj_bd_stock_order` MODIFY COLUMN `bonus_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '分红时间' AFTER `is_bonus`;
CREATE TABLE `zjhj_bd_order_send_template` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `mall_id` INT ( 11 ) NOT NULL, `mch_id` INT ( 11 ) NOT NULL DEFAULT '0', `name` VARCHAR ( 60 ) NOT NULL DEFAULT '' COMMENT '发货单名称', `cover_pic` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '缩略图', `params` text NOT NULL COMMENT '模板参数', `is_default` TINYINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否为默认模板0.否|1.是', `created_at` TIMESTAMP NOT NULL, `updated_at` TIMESTAMP NOT NULL, `deleted_at` TIMESTAMP NOT NULL, `is_delete` TINYINT ( 1 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) ENGINE = INNODB DEFAULT CHARSET = utf8mb4;
CREATE TABLE `zjhj_bd_order_send_template_address` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `mall_id` INT ( 11 ) NOT NULL, `mch_id` INT ( 11 ) NOT NULL, `name` VARCHAR ( 60 ) NOT NULL DEFAULT '' COMMENT '网点名称', `username` VARCHAR ( 60 ) NOT NULL DEFAULT '' COMMENT '联系人', `mobile` VARCHAR ( 60 ) NOT NULL DEFAULT '' COMMENT '联系电话', `code` VARCHAR ( 60 ) NOT NULL DEFAULT '' COMMENT '网点邮编', `address` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '地址', `created_at` TIMESTAMP NOT NULL, `updated_at` TIMESTAMP NOT NULL, `deleted_at` TIMESTAMP NOT NULL, `is_delete` TINYINT ( 1 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) ENGINE = INNODB DEFAULT CHARSET = utf8mb4;
EOF;
        sql_execute($sql);
    },

    '4.2.43' => function () {
    },

    '4.2.45' => function () {
    },

    '4.2.46' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_gift_log` CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_gift_log` MODIFY COLUMN `bless_word` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '祝福语' AFTER `open_type`;
EOF;
        sql_execute($sql);
    },

    '4.2.47' => function () {
    },

    '4.2.48' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_printer_setting` ADD COLUMN `big` int(11) NOT NULL DEFAULT 0 COMMENT '放大倍数' AFTER `deleted_at`, ADD COLUMN `show_type` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '打印参数 attr 规格 goods_no 货号 form_data 下单表单' AFTER `big`;
EOF;
        sql_execute($sql);
    },

    '4.2.50' => function () {
        $sql = <<<EOF
alter table zjhj_bd_step_setting add `share_pic` varchar(255) not null default '' comment '分享图片';
alter table zjhj_bd_user_info add `remark_name` varchar(60) not null default '' comment '备注名';
ALTER TABLE `zjhj_bd_goods` ADD COLUMN `sales` int(11) NOT NULL DEFAULT 0 COMMENT '商品实际销量';
ALTER TABLE `zjhj_bd_mail_setting` ADD COLUMN `show_type` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'attr 规格 goods_no 货号 form_data 下单表单' AFTER `deleted_at`;
ALTER TABLE `zjhj_bd_step_ad` ADD COLUMN `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '流量主类型' AFTER `deleted_at`, ADD COLUMN `pic_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '广告封面' AFTER `type`, ADD COLUMN `video_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '广告视频' AFTER `pic_url`, ADD COLUMN `reward_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '奖励数据' AFTER `video_url`;
CREATE TABLE `zjhj_bd_step_ad_coupon` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `user_coupon_id` int(11) NOT NULL, `is_delete` tinyint(2) NOT NULL DEFAULT '0' COMMENT '删除', `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_diy_ad_coupon` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `user_coupon_id` int(11) NOT NULL, `is_delete` tinyint(2) NOT NULL DEFAULT '0' COMMENT '删除', `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_diy_ad_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `template_id` int(11) NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除', `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `raffled_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_step_ad_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `ad_id` int(11) NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除', `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `raffled_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_pick_activity` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态 0下架 1上架', `is_delete` tinyint(4) NOT NULL DEFAULT '0', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `title` varchar(255) NOT NULL DEFAULT '' COMMENT '活动标题', `start_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动开始时间', `end_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动结束时间', `rule_price` decimal(10,2) NOT NULL COMMENT '组合方案 元', `rule_num` int(11) NOT NULL COMMENT '组合方案 件', `is_area_limit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否单独区域购买', `area_limit` longtext NOT NULL, PRIMARY KEY (`id`) USING BTREE, KEY `idx_1` (`mall_id`,`is_delete`,`created_at`), KEY `sort` (`start_at`,`end_at`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='N元任选活动';
CREATE TABLE `zjhj_bd_pick_goods` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `status` tinyint(1) NOT NULL COMMENT '状态 0 关闭 1开启', `goods_id` int(11) NOT NULL DEFAULT '0', `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `pick_activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id', `stock` int(11) NOT NULL COMMENT '总库存', `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序', PRIMARY KEY (`id`) USING BTREE, KEY `activity` (`pick_activity_id`) USING BTREE, KEY `goods_id` (`goods_id`) USING BTREE ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='N元任选商品';
CREATE TABLE `zjhj_bd_pick_setting` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `key` varchar(255) NOT NULL, `value` text NOT NULL, `created_at` timestamp NOT NULL COMMENT '创建时间', `updated_at` timestamp NOT NULL COMMENT '更新时间', `is_delete` int(11) NOT NULL DEFAULT '0' COMMENT '是否删除 0--未删除 1--已删除', `deleted_at` timestamp NOT NULL COMMENT '删除时间', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='N元任选设置';
CREATE TABLE `zjhj_bd_pick_cart` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `goods_id` int(11) NOT NULL COMMENT '商品', `attr_id` int(11) NOT NULL COMMENT '商品规格', `num` int(11) NOT NULL DEFAULT '1' COMMENT '商品数量', `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除', `created_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `attr_info` text, `pick_activity_id` int(11) NOT NULL COMMENT '活动id', PRIMARY KEY (`id`), KEY `mall_id` (`mall_id`), KEY `user_id` (`user_id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

ALTER TABLE `zjhj_bd_user_identity` ADD INDEX `is_super_admin`(`is_super_admin`);
ALTER TABLE `zjhj_bd_admin_info` ADD INDEX `user_id`(`user_id`);
ALTER TABLE `zjhj_bd_admin_info` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_favorite` ADD INDEX `user_id`(`user_id`);
ALTER TABLE `zjhj_bd_favorite` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_goods` ADD INDEX `status`(`status`);
ALTER TABLE `zjhj_bd_goods` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_footprint_goods_log` ADD INDEX `user_id`(`user_id`);
ALTER TABLE `zjhj_bd_footprint_goods_log` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_qr_code_parameter` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_qr_code_parameter` ADD INDEX `token`(`token`);
ALTER TABLE `zjhj_bd_attachment` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_attachment` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_attachment` ADD INDEX `type`(`type`);
ALTER TABLE `zjhj_bd_attachment` ADD INDEX `mch_id`(`mch_id`);
ALTER TABLE `zjhj_bd_attachment` ADD INDEX `is_recycle`(`is_recycle`);
ALTER TABLE `zjhj_bd_attachment` ADD INDEX `attachment_group_id`(`attachment_group_id`);
ALTER TABLE `zjhj_bd_diy_page` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_diy_page` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_diy_page` ADD INDEX `is_disable`(`is_disable`);
ALTER TABLE `zjhj_bd_vip_card` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_vip_card` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_city_delivery_setting` ADD INDEX `key`(`key`);
ALTER TABLE `zjhj_bd_city_delivery_setting` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_city_delivery_setting` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_wxapp_config` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_bargain_goods` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_bargain_goods` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_bargain_goods` ADD INDEX `end_time`(`end_time`);
ALTER TABLE `zjhj_bd_goods` ADD INDEX `sort`(`sort`);
ALTER TABLE `zjhj_bd_goods` ADD INDEX `created_at`(`created_at`);
ALTER TABLE `zjhj_bd_goods_warehouse` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_goods_warehouse` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_goods` ADD INDEX `sales`(`sales`);
ALTER TABLE `zjhj_bd_order_refund` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_order_refund` ADD INDEX `mch_id`(`mch_id`);
ALTER TABLE `zjhj_bd_order_refund` ADD INDEX `user_id`(`user_id`);
ALTER TABLE `zjhj_bd_order_refund` ADD INDEX `order_id`(`order_id`);
ALTER TABLE `zjhj_bd_order_refund` ADD INDEX `order_detail_id`(`order_detail_id`);
ALTER TABLE `zjhj_bd_order_refund` ADD INDEX `order_no`(`order_no`);
ALTER TABLE `zjhj_bd_order_refund` ADD INDEX `type`(`type`);
ALTER TABLE `zjhj_bd_order_refund` ADD INDEX `status`(`status`);
ALTER TABLE `zjhj_bd_order_refund` ADD INDEX `is_send`(`is_send`);
ALTER TABLE `zjhj_bd_order_refund` ADD INDEX `is_confirm`(`is_confirm`);
ALTER TABLE `zjhj_bd_order_refund` ADD INDEX `is_refund`(`is_refund`);
ALTER TABLE `zjhj_bd_order_refund` ADD INDEX `is_delete`(`is_delete`);
EOF;
        sql_execute($sql);
    },

    '4.2.53' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_goods` MODIFY COLUMN `area_limit` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '区域限制' AFTER `is_area_limit`;
EOF;
        sql_execute($sql);
    },

    '4.2.54' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_vip_card_user` MODIFY COLUMN `all_send` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '所有赠送信息' AFTER `image_name`;
EOF;
        sql_execute($sql);
    },

    '4.2.55' => function () {
        $sql = <<<EOF
alter table `zjhj_bd_goods_cards` add `number` int(11) not null default 1 comment '卡券可核销总次数';
alter table `zjhj_bd_user_card` add `use_number` int(11) not null default 0 comment '卡券已核销次数';
alter table `zjhj_bd_user_card` add `number` int(11) not null default 1 comment '卡券可核销次数';
update `zjhj_bd_order` set pay_type = 1 where pay_type = 4 or pay_type = 5 or pay_type = 6;
ALTER TABLE `zjhj_bd_mall_setting` MODIFY COLUMN `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL AFTER `key`;
CREATE TABLE `zjhj_bd_goods_card_clerk_log` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `user_card_id` int(11) NOT NULL COMMENT '用户卡券ID', `clerk_id` int(11) NOT NULL COMMENT '核销员ID', `store_id` int(11) NOT NULL COMMENT '核销门店ID', `use_number` int(11) NOT NULL COMMENT '核销次数', `surplus_number` int(11) NOT NULL COMMENT '剩余次数', `clerked_at` timestamp NOT NULL COMMENT '核销时间', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },

    '4.2.56' => function () {
    },

    '4.2.57' => function () {
    },

    '4.2.58' => function () {
        $sql = <<<EOF
alter table `zjhj_bd_import_goods` rename to `zjhj_bd_import_data`;
alter table `zjhj_bd_import_data` change `goods_count` `count` int(11) not null comment '导入总数量';
alter table `zjhj_bd_import_data` add `type` tinyint(1) not null default 1 comment '1.商品导入|2.分类导入';
alter table `zjhj_bd_home_nav` add `sign` varchar(65) not null default '' comment '插件标识';
alter table `zjhj_bd_banner` add `sign` varchar(65) not null default '' comment '插件标识';
ALTER TABLE `zjhj_bd_order` MODIFY COLUMN `send_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '配送方式：0--快递配送 1--到店自提 2--同城配送 3--无配送' AFTER `is_recycle`;
CREATE TABLE `zjhj_bd_goods_attr_template` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `mch_id` int(11) NOT NULL DEFAULT 0, `attr_group_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '规格名', `attr_group_id` int(11) NOT NULL DEFAULT 0 COMMENT '规格组', `attr_list` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '规格值', `select_attr_list` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '后台 搜索用的', `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '删除', `created_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00', `deleted_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00', PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_composition` ( `id` int(11) NOT NULL AUTO_INCREMENT COMMENT ' ', `mall_id` int(11) NOT NULL, `name` varchar(255) NOT NULL DEFAULT '' COMMENT '套餐名', `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '套餐价', `type` tinyint(255) NOT NULL DEFAULT '1' COMMENT '套餐类型 1--固定套餐 2--搭配套餐', `status` int(11) NOT NULL DEFAULT '0' COMMENT '是否上架 0--下架 1--上架', `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序', `is_delete` int(11) NOT NULL DEFAULT '0', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `sort_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '排序的优惠金额', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='套餐表';
CREATE TABLE `zjhj_bd_composition_goods` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `model_id` int(11) NOT NULL COMMENT '套餐id', `goods_id` int(11) NOT NULL COMMENT '商品id', `is_host` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是主商品', `is_delete` tinyint(1) NOT NULL DEFAULT '0', `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额', `payment_people` int(11) NOT NULL DEFAULT '0' COMMENT '支付人数', `payment_num` int(11) NOT NULL DEFAULT '0' COMMENT '支付件数', `payment_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额', `created_at` timestamp NOT NULL, PRIMARY KEY (`id`), KEY `mall_id` (`mall_id`) USING BTREE, KEY `model_id` (`model_id`) USING BTREE, KEY `goods_id` (`goods_id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_composition_order` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `order_id` int(11) NOT NULL, `composition_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠金额', `price` decimal(10,2) NOT NULL, `is_delete` tinyint(1) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },

    '4.2.59' => function () {
    },

    '4.2.60' => function () {
    },

    '4.2.61' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_goods_member_price` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_form` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_form` ADD INDEX `mch_id`(`mch_id`);
ALTER TABLE `zjhj_bd_form` ADD INDEX `is_default`(`is_default`);
ALTER TABLE `zjhj_bd_form` ADD INDEX `status`(`status`);
ALTER TABLE `zjhj_bd_form` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_wxapp_subscribe` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_mall_members` ADD INDEX `level`(`level`);
ALTER TABLE `zjhj_bd_mall_members` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_mall_members` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_vip_card_discount` ADD INDEX `order_id`(`order_id`);
ALTER TABLE `zjhj_bd_printer_setting` ADD INDEX `mch_id`(`mch_id`);
ALTER TABLE `zjhj_bd_printer_setting` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_printer_setting` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_printer_setting` ADD INDEX `status`(`status`);
ALTER TABLE `zjhj_bd_printer_setting` ADD INDEX `store_id`(`store_id`);
ALTER TABLE `zjhj_bd_goods_attr` ADD INDEX `index1`(`is_delete`, `goods_id`);
ALTER TABLE `zjhj_bd_order_comments` ADD INDEX `order_id`(`order_id`);
ALTER TABLE `zjhj_bd_order` ADD INDEX `index2`(`mall_id`,`is_delete`,`user_id`,`is_confirm`,`is_sale`,`sale_status`,`cancel_status`,`is_recycle`);
ALTER TABLE `zjhj_bd_order` ADD INDEX `index3`(`mall_id`,`is_delete`,`user_id`,`is_send`,`pay_type`,`is_pay`,`cancel_status`,`is_recycle`);
ALTER TABLE `zjhj_bd_vip_card_appoint_goods` ADD INDEX `goods_id`(`goods_id`);
ALTER TABLE `zjhj_bd_vip_card_setting` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_vip_card_setting` ADD INDEX `is_delete`(`is_delete`);
EOF;
        sql_execute($sql);
    },

    '4.2.62' => function () {
    },

    '4.2.63' => function () {
    },

    '4.2.64' => function () {
    },

    '4.2.65' => function () {
    },

    '4.2.68' => function () {
    },

    '4.2.69' => function () {
    },

    '4.2.70' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_pintuan_goods` ADD `start_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动开始日期';
ALTER TABLE `zjhj_bd_pintuan_goods` ADD `is_auto_add_robot` TINYINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '是否自动添加机器人0.否|1.是';
ALTER TABLE `zjhj_bd_pintuan_goods` ADD `add_robot_time` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '机器人参与时间0.表示不添加';
ALTER TABLE `zjhj_bd_pintuan_goods` ADD `pintuan_goods_id` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '是否为同一组';
CREATE TABLE `zjhj_bd_miaosha_activitys` ( `id` INT ( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT, `mall_id` INT ( 11 ) NOT NULL, `status` TINYINT ( 1 ) NOT NULL DEFAULT '0' COMMENT '秒杀活动状态0.关闭|1.开启', `open_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '活动开始时间', `end_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '活动结束时间', `created_at` TIMESTAMP NOT NULL, `updated_at` TIMESTAMP NOT NULL, `deleted_at` TIMESTAMP NOT NULL, `is_delete` TINYINT ( 1 ) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) ) ENGINE = INNODB DEFAULT CHARSET = utf8mb4;
ALTER TABLE `zjhj_bd_miaosha_goods` ADD `activity_id` INT ( 11 ) NOT NULL DEFAULT '0' COMMENT '活动ID';
ALTER TABLE `zjhj_bd_booking_goods` ADD `is_order_form` TINYINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '是否开启自定义表单0.否|1.是';
ALTER TABLE `zjhj_bd_booking_goods` ADD `order_form_type` TINYINT ( 1 ) NOT NULL DEFAULT 1 COMMENT '1.选择表单|2.自定义表单';
ALTER TABLE `zjhj_bd_bargain_goods` ADD COLUMN `stock_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '减库存的方式 1--参与减库存 2--拍下减库存';
ALTER TABLE `zjhj_bd_fxhb_activity` ADD COLUMN `is_home_model` tinyint(1) NOT NULL DEFAULT 0 COMMENT '首页弹窗开关' AFTER `name`;
ALTER TABLE `zjhj_bd_qr_code_parameter` ADD `use_number` INT ( 11 ) NOT NULL DEFAULT 0 COMMENT '使用次数';
EOF;
        sql_execute($sql);
    },

    '4.2.74' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_payment_order_union` ADD COLUMN `app_version` VARCHAR ( 32 ) NOT NULL DEFAULT '' COMMENT '小程序端版本';
EOF;
        sql_execute($sql);
    },

    '4.2.76' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_order_vip_card_info` ( `id` int(11) NOT NULL AUTO_INCREMENT, `order_id` int(11) NOT NULL COMMENT '订单ID', `vip_card_detail_id` int(11) NOT NULL COMMENT '超级会员卡子卡ID', `order_total_price` decimal(10,2) NOT NULL COMMENT '超级会员卡优惠后订单的金额', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_bargain_order` ADD COLUMN `preferential_price` decimal(10,2) NOT NULL DEFAULT 0 COMMENT '优惠金额';
ALTER TABLE `zjhj_bd_address` MODIFY COLUMN `province_id` int(11) NOT NULL DEFAULT 0 AFTER `name`, MODIFY COLUMN `province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '省份名称' AFTER `province_id`, MODIFY COLUMN `city_id` int(11) NOT NULL DEFAULT 0 AFTER `province`, MODIFY COLUMN `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '城市名称' AFTER `city_id`, MODIFY COLUMN `district_id` int(11) NOT NULL DEFAULT 0 AFTER `city`, MODIFY COLUMN `district` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '县区名称' AFTER `district_id`;
ALTER TABLE `zjhj_bd_address` ADD COLUMN `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '类型：0快递 1同城' AFTER `location`;
CREATE TABLE `zjhj_bd_assistant_data` ( `id` int(11) NOT NULL AUTO_INCREMENT, `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型 0--淘宝 1--淘宝app 2--天猫 3--天猫app 4--京东 5--拼多多', `itemId` varchar(255) NOT NULL DEFAULT '0' COMMENT '原始商品id', `json` longtext NOT NULL COMMENT '数据', `created_at` timestamp NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='采集助手数据存储';
CREATE TABLE `zjhj_bd_order_detail_vip_card_info` ( `id` int(11) NOT NULL AUTO_INCREMENT, `vip_card_order_id` int(11) NOT NULL, `order_detail_id` int(11) NOT NULL, `order_detail_total_price` decimal(10,2) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },

    '4.2.81' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_order` ADD `cancel_data` text COMMENT '订单申请退款数据';
ALTER TABLE `zjhj_bd_order_refund` ADD `mobile` VARCHAR ( 255 ) NOT NULL DEFAULT '' COMMENT '联系方式';
ALTER TABLE `zjhj_bd_order_refund` ADD `refund_data` text NOT NULL COMMENT '售后数据';
ALTER TABLE `zjhj_bd_share_order` ADD COLUMN `price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT 0 COMMENT '用于分销的金额', ADD COLUMN `first_share_type` TINYINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '一级分销的分销类型', ADD COLUMN `first_share_price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT 0 COMMENT '一级佣金', ADD COLUMN `second_share_type` TINYINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '二级分销的分销类型', ADD COLUMN `second_share_price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT 0 COMMENT '二级佣金', ADD COLUMN `third_share_type` TINYINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '三级分销的分销类型', ADD COLUMN `third_share_price` DECIMAL ( 10, 2 ) NOT NULL DEFAULT 0 COMMENT '三级佣金', ADD COLUMN `flag` TINYINT ( 1 ) NOT NULL DEFAULT 0 COMMENT '修改记录 0--售后优化之前的分销订单 1--售后优化之后的订单';
EOF;
        sql_execute($sql);
    },

    '4.2.83' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_goods_warehouse` ADD COLUMN `type` varchar(255) NOT NULL DEFAULT 'goods' COMMENT '商品类型：goods--实体商品 ecard--电子卡密', ADD COLUMN `ecard_id` int NOT NULL DEFAULT 0 COMMENT '卡密id';
ALTER TABLE `zjhj_bd_scratch_log` ADD COLUMN `token` varchar(255) NOT NULL DEFAULT '' COMMENT '订单表token';
ALTER TABLE `zjhj_bd_pond_log` ADD COLUMN `token` varchar(255) NOT NULL DEFAULT '' COMMENT '订单表token';
ALTER TABLE `zjhj_bd_lottery_log` ADD COLUMN `token` varchar(255) NOT NULL DEFAULT '' COMMENT '订单表token';
CREATE TABLE `zjhj_bd_ecard` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `name` varchar(255) NOT NULL DEFAULT '' COMMENT '卡密名称', `content` longtext COMMENT '使用说明', `is_delete` tinyint(1) NOT NULL DEFAULT '0', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `list` longtext NOT NULL COMMENT '卡密字段', `sales` int(11) NOT NULL DEFAULT '0' COMMENT '已售', `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存', `is_unique` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否去重 0--否 1--是', `pre_stock` int(11) NOT NULL DEFAULT '0' COMMENT '预占用的库存', `total_stock` int(11) NOT NULL DEFAULT '0' COMMENT '总库存', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='电子卡密';
CREATE TABLE `zjhj_bd_ecard_data` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `ecard_id` int(11) NOT NULL, `token` varchar(255) NOT NULL, `key` varchar(255) NOT NULL, `value` longtext NOT NULL, `is_delete` tinyint(1) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_ecard_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `ecard_id` int(11) NOT NULL DEFAULT '0', `status` varchar(255) NOT NULL DEFAULT '' COMMENT '日志操作 add--添加 occupy--占用 sales--卖出 delete--删除', `sign` varchar(255) NOT NULL DEFAULT '' COMMENT '插件标示', `number` int(11) NOT NULL DEFAULT '0' COMMENT '数量', `created_at` timestamp NOT NULL, `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_ecard_options` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `token` varchar(255) NOT NULL DEFAULT '' COMMENT '加密字符串', `ecard_id` int(11) NOT NULL DEFAULT '0' COMMENT '电子卡密id', `value` longtext NOT NULL COMMENT '卡密字段值', `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除', `is_sales` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否出售', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_occupy` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被占用 0--否 1--是', PRIMARY KEY (`id`), KEY `e_card_id` (`ecard_id`), KEY `token` (`token`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='电子卡密数据';
CREATE TABLE `zjhj_bd_ecard_order` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `ecard_id` int(11) NOT NULL, `value` longtext NOT NULL, `order_id` int(11) NOT NULL, `order_detail_id` int(11) NOT NULL, `is_delete` tinyint(1) NOT NULL, `token` varchar(255) NOT NULL DEFAULT '' COMMENT '加密字符串', `ecard_options_id` int(11) NOT NULL, `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id', `order_token` varchar(255) NOT NULL DEFAULT '' COMMENT '订单token', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='卡密订单列表';
ALTER TABLE `zjhj_bd_order_detail` ADD COLUMN `goods_type` VARCHAR ( 255 ) NOT NULL DEFAULT 'goods' COMMENT '商品类型';
EOF;
        sql_execute($sql);
    },

    '4.2.84' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_ecard_options` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `token` varchar(255) NOT NULL DEFAULT '' COMMENT '加密字符串', `ecard_id` int(11) NOT NULL DEFAULT '0' COMMENT '电子卡密id', `value` longtext NOT NULL COMMENT '卡密字段值', `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除', `is_sales` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否出售', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_occupy` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被占用 0--否 1--是', PRIMARY KEY (`id`), KEY `e_card_id` (`ecard_id`), KEY `token` (`token`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='电子卡密数据';
EOF;
        sql_execute($sql);
    },

    '4.2.85' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_ecard_options` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `token` varchar(70) NOT NULL DEFAULT '' COMMENT '加密字符串', `ecard_id` int(11) NOT NULL DEFAULT '0' COMMENT '电子卡密id', `value` longtext NOT NULL COMMENT '卡密字段值', `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除', `is_sales` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否出售', `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_occupy` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被占用 0--否 1--是', PRIMARY KEY (`id`), KEY `e_card_id` (`ecard_id`), KEY `token` (`token`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='电子卡密数据';
EOF;
        sql_execute($sql);
    },

    '4.2.90' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_diy_template` ADD COLUMN `type` VARCHAR ( 100 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'page:微页面' AFTER `deleted_at`;
CREATE TABLE `zjhj_bd_diy_coupon_log` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `template_id` int(11) NOT NULL, `user_coupon_id` int(11) NOT NULL, `is_delete` tinyint(2) NOT NULL DEFAULT 0 COMMENT '删除', `created_at` timestamp(0) NOT NULL, `deleted_at` timestamp(0) NOT NULL, PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
ALTER TABLE `zjhj_bd_delivery` ADD COLUMN `goods_alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '商品' COMMENT '自定义商品别名' AFTER `deleted_at`, ADD COLUMN `is_goods_alias` tinyint(1) NOT NULL DEFAULT 0 COMMENT '自定义商品别名开关' AFTER `goods_alias`;
EOF;
        sql_execute($sql);
    },

    '4.2.98' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_mch_cash` ADD COLUMN `content`  longtext NULL COMMENT '备注' AFTER `is_delete`; 
CREATE TABLE `zjhj_bd_region_area` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `name` varchar(100) NOT NULL DEFAULT '' COMMENT '区域名称',   `province_rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '省代理分红比例',   `city_rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市代理分红比例',   `district_rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '区/县分红比例',   `province_condition` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '省代理条件',   `city_condition` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市代理条件',   `district_condition` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '区/县代理条件',   `become_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1:下线总人数\r\n2:分销订单总数\r\n3:分销订单总金额\r\n4:累计佣金总额\r\n5:已提现佣金总额\r\n6:消费金额',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   PRIMARY KEY (`id`),   KEY `index_1` (`mall_id`,`is_delete`,`created_at`),   KEY `created_at` (`created_at`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='区域区域表';
CREATE TABLE `zjhj_bd_region_area_detail` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `area_id` int(11) NOT NULL COMMENT '区域id',   `province_id` int(2) NOT NULL COMMENT '省id',   `is_delete` tinyint(2) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`),   KEY `area_id` (`area_id`),   KEY `index_1` (`mall_id`,`area_id`,`is_delete`),   KEY `created_at` (`created_at`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='代理区域详情表';
CREATE TABLE `zjhj_bd_region_bonus_log` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL DEFAULT '0',   `bonus_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1按周，2按月',   `pre_bonus_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '预计分红金额',   `bonus_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分红金额',   `bonus_rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '当时的分红比例',   `pre_order_num` int(11) NOT NULL DEFAULT '0' COMMENT '预计分红订单数',   `order_num` int(11) NOT NULL DEFAULT '0' COMMENT '分红订单数',   `region_num` int(11) NOT NULL DEFAULT '0' COMMENT '当时区域人数',   `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '分红时间段-开始时间',   `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '分红时间段-结束时间',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_region_cash` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `order_no` varchar(255) NOT NULL DEFAULT '' COMMENT '订单号',   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',   `service_charge` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现手续费（%）',   `type` varchar(255) NOT NULL DEFAULT '' COMMENT '提现方式 auto--自动打款 wechat--微信打款 alipay--支付宝打款 bank--银行转账 balance--打款到余额',   `extra` longtext NOT NULL COMMENT '额外信息 例如微信账号、支付宝账号等',   `status` int(11) NOT NULL DEFAULT '0' COMMENT '提现状态 0--申请 1--同意 2--已打款 3--驳回',   `is_delete` int(11) NOT NULL DEFAULT '0',   `created_at` datetime NOT NULL,   `updated_at` datetime NOT NULL,   `deleted_at` datetime NOT NULL,   `content` longtext,   PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='提现记录表';
CREATE TABLE `zjhj_bd_region_cash_log` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `type` int(11) NOT NULL DEFAULT '1' COMMENT '类型 1--收入 2--支出',   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变动佣金',   `desc` longtext,   `custom_desc` longtext,   `level_id` int(11) NOT NULL DEFAULT '0' COMMENT '当时的区域等级',   `level_name` varchar(100) NOT NULL DEFAULT '',   `order_num` int(11) NOT NULL DEFAULT '0',   `bonus_rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '当时的分红比例',   `bonus_id` int(11) NOT NULL DEFAULT '0' COMMENT '区域完成分红记录ID',   `is_delete` int(11) NOT NULL DEFAULT '0',   `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',   `province_id` int(11) NOT NULL DEFAULT '0',   `city_id` int(11) NOT NULL DEFAULT '0',   `district_id` int(11) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`),   KEY `idx_1` (`mall_id`,`is_delete`,`province_id`,`level_id`,`type`) USING BTREE ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='分红日志';
CREATE TABLE `zjhj_bd_region_level_up` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:申请升级中  1:通过升级 2:拒绝升级',   `level` tinyint(1) NOT NULL COMMENT '升级的等级',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`),   KEY `idx_1` (`mall_id`,`is_delete`,`user_id`,`created_at`),   KEY `idx_2` (`user_id`),   KEY `idx_3` (`created_at`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='代理升级申请表';
CREATE TABLE `zjhj_bd_region_order` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL DEFAULT '0',   `order_id` int(11) NOT NULL DEFAULT '0',   `total_pay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单实付金额',   `is_bonus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1已分红，0未分红',   `bonus_rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分红比例',   `bonus_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '分红时间',   `bonus_id` int(11) NOT NULL DEFAULT '0' COMMENT '区域完成分红记录ID',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `province` varchar(20) NOT NULL COMMENT '省',   `city` varchar(20) NOT NULL COMMENT '市',   `district` varchar(20) NOT NULL COMMENT '区',   `province_id` int(11) NOT NULL DEFAULT '0',   `city_id` int(11) NOT NULL DEFAULT '0',   `district_id` int(11) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='分红池';
CREATE TABLE `zjhj_bd_region_relation` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL COMMENT '代理id',   `district_id` int(11) NOT NULL COMMENT '代理的省市区id',   `is_update` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是升级中的关联地区0：否 1：是',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`),   KEY `mall_id` (`mall_id`,`district_id`,`is_update`,`is_delete`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='代理 --- 地区关联表';
CREATE TABLE `zjhj_bd_region_setting` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `key` varchar(255) NOT NULL,   `value` text NOT NULL,   `created_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',   `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',   `is_delete` int(11) NOT NULL DEFAULT '0' COMMENT '是否删除 0--未删除 1--已删除',   `deleted_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '删除时间',   PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='区域分红设置';
CREATE TABLE `zjhj_bd_region_user` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `area_id` int(11) NOT NULL COMMENT '区域ID',   `province_id` int(11) NOT NULL COMMENT '所属省',   `level` tinyint(2) NOT NULL COMMENT '1:省代理  2:市代理 3:区代理',   `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '-2被拒或移除后再次申请没提交 -1移除 0审核中，1同意，2拒绝',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `applyed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '申请时间',   `agreed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '审核时间',   PRIMARY KEY (`id`),   KEY `created_at` (`created_at`),   KEY `idx_1` (`mall_id`,`is_delete`,`status`,`created_at`) USING BTREE,   KEY `idx_2` (`mall_id`,`is_delete`,`user_id`,`status`,`created_at`),   KEY `user_id` (`user_id`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='代理表';
CREATE TABLE `zjhj_bd_region_user_info` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `user_id` int(11) NOT NULL,   `name` varchar(100) NOT NULL DEFAULT '' COMMENT '区域姓名',   `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '区域手机号',   `all_bonus` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '累计分红',   `total_bonus` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '当前分红',   `out_bonus` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已提现分红',   `remark` varchar(200) NOT NULL DEFAULT '' COMMENT '备注',   `reason` text NOT NULL COMMENT '拒绝理由',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`),   KEY `idx_1` (`user_id`,`created_at`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='代理信息表';
ALTER TABLE `zjhj_bd_region_level_up`
ADD COLUMN `reason`  varchar(512) NOT NULL DEFAULT '' COMMENT '理由' AFTER `level`;
ALTER TABLE `zjhj_bd_region_level_up`
ADD COLUMN `is_read`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '0未读  1已读' AFTER `reason`;
ALTER TABLE `zjhj_bd_vip_card_order`
MODIFY COLUMN `all_send`  varchar(4096) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' AFTER `status`;
EOF;
        sql_execute($sql);
    },

    '4.3.2' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_live_goods` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `goods_id` int(11) NOT NULL, `audit_id` int(11) NOT NULL, PRIMARY KEY (`id`), KEY `index_name` (`goods_id`,`audit_id`,`mall_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_core_plugin` MODIFY COLUMN `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL AFTER `id`;
ALTER TABLE `zjhj_bd_core_plugin` ADD COLUMN `pic_url` text NULL AFTER `deleted_at`;
ALTER TABLE `zjhj_bd_core_plugin` ADD COLUMN `desc` longtext NULL AFTER `pic_url`;
CREATE TABLE `zjhj_bd_plugin_cat` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `name` VARCHAR ( 24 ) COLLATE utf8mb4_general_ci NOT NULL, `display_name` VARCHAR ( 255 ) COLLATE utf8mb4_general_ci NOT NULL, `sort` INT ( 11 ) NOT NULL DEFAULT 100, `icon` text COLLATE utf8mb4_general_ci, `is_delete` TINYINT ( 1 ) NOT NULL DEFAULT 0, `add_time` datetime DEFAULT NULL, `update_time` datetime DEFAULT NULL, PRIMARY KEY ( `id` ), KEY `name` ( `name` ), KEY `sort` ( `sort` ), KEY `is_delete` ( `is_delete` ) ) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;
CREATE TABLE `zjhj_bd_plugin_cat_rel` ( `id` INT ( 11 ) NOT NULL AUTO_INCREMENT, `plugin_name` VARCHAR ( 32 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `plugin_cat_name` VARCHAR ( 24 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, PRIMARY KEY ( `id` ), KEY `plugin_name` ( `plugin_name` ), KEY `plugin_cat_name` ( `plugin_cat_name` ) ) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;
ALTER TABLE `zjhj_bd_plugin_cat` ADD COLUMN `color` varchar(24) NOT NULL DEFAULT '' AFTER `display_name`;
ALTER TABLE `zjhj_bd_core_plugin` ADD COLUMN `sort` int(0) NOT NULL DEFAULT 100 AFTER `desc`;
EOF;
        sql_execute($sql);
    },

    '4.3.6' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_admin_notice` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `user_id` int(11) NOT NULL DEFAULT '0',   `type` varchar(20) NOT NULL DEFAULT '' COMMENT 'update更新urgent紧急important重要',   `content` text NOT NULL,   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_statistics_data_log` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL DEFAULT '0',   `key` varchar(100) NOT NULL DEFAULT '',   `value` int(11) NOT NULL DEFAULT '0',   `time_stamp` int(11) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_statistics_user_log` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL DEFAULT '0',   `user_id` int(11) NOT NULL DEFAULT '0',   `num` int(11) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `time_stamp` int(11) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },

    '4.3.8' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_flash_sale_activity` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态 0下架 1上架',   `is_delete` tinyint(4) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   `title` varchar(255) NOT NULL DEFAULT '' COMMENT '活动标题',   `start_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动开始时间',   `end_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动结束时间',   `notice` int(11) NOT NULL DEFAULT '0' COMMENT '活动预告',   PRIMARY KEY (`id`),   KEY `idx_1` (`mall_id`,`is_delete`,`created_at`) USING BTREE,   KEY `sort` (`start_at`,`end_at`) USING BTREE ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='显示购买活动';
CREATE TABLE `zjhj_bd_flash_sale_goods` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `status` tinyint(1) NOT NULL COMMENT '状态 0 关闭 1开启',   `goods_id` int(11) NOT NULL DEFAULT '0',   `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',   `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',   PRIMARY KEY (`id`),   KEY `activity` (`activity_id`) USING BTREE,   KEY `goods_id` (`goods_id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='限时抢购商品表';
CREATE TABLE `zjhj_bd_flash_sale_goods_attr` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `discount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品折扣',   `cut` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品减钱',   `goods_id` int(11) NOT NULL,   `goods_attr_id` int(11) NOT NULL,   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='显示购买商品规格';
CREATE TABLE `zjhj_bd_flash_sale_order_discount` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `order_id` int(11) NOT NULL COMMENT '订单id',   `discount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_store` MODIFY COLUMN `description`  longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '门店描述';
ALTER TABLE `zjhj_bd_flash_sale_activity` ADD COLUMN `notice_hours`  int(11) NOT NULL DEFAULT 0 COMMENT '提前N小时' AFTER `notice`;
ALTER TABLE `zjhj_bd_flash_sale_goods` ADD COLUMN `type`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1打折  2减钱  3促销价' AFTER `goods_id`;
ALTER TABLE `zjhj_bd_flash_sale_goods_attr` ADD COLUMN `type`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1打折  2减钱  3促销价' AFTER `cut`;
EOF;
        sql_execute($sql);
    },

    '4.3.10' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_core_template_type` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `template_id` int(11) NOT NULL,   `type` varchar(255) NOT NULL DEFAULT '' COMMENT '模板适用地方',   `is_delete` tinyint(1) NOT NULL,   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COMMENT='模板市场中模板适用的地方';
alter table `zjhj_bd_pintuan_orders` add `expected_over_time` int(11) default 0 not null;
alter table `zjhj_bd_pintuan_orders` add index index_name(expected_over_time);
CREATE TABLE `zjhj_bd_wxapp_service` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `cid` int(11) NOT NULL COMMENT 'wxapp_config',   `appid` varchar(128) NOT NULL COMMENT '服务商appid',   `mchid` varchar(32) NOT NULL COMMENT '服务商mchid',   `is_choise` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1选中  0不选',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `key` varchar(32) NOT NULL COMMENT '服务商微信支付Api密钥',   `cert_pem` varchar(2000) NOT NULL DEFAULT '',   `key_pem` varchar(2000) NOT NULL DEFAULT '',   PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
ALTER TABLE `zjhj_bd_wxapp_config` MODIFY COLUMN `mchid`  varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' AFTER `updated_at`, MODIFY COLUMN `key`  varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' AFTER `mchid`;
ALTER TABLE `zjhj_bd_goods_service_relation` ADD INDEX `index1` (`goods_id`, `is_delete`) USING BTREE , ADD INDEX `index2` (`service_id`, `is_delete`) USING BTREE ;
ALTER TABLE `zjhj_bd_goods_service_relation` ADD INDEX `service_id`(`service_id`);
ALTER TABLE `zjhj_bd_goods_service_relation` ADD INDEX `goods_id`(`goods_id`);
ALTER TABLE `zjhj_bd_goods_service_relation` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_statistics_data_log` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_statistics_data_log` ADD INDEX `key`(`key`);
ALTER TABLE `zjhj_bd_statistics_data_log` ADD INDEX `value`(`value`);
ALTER TABLE `zjhj_bd_statistics_data_log` ADD INDEX `time_stamp`(`time_stamp`);
ALTER TABLE `zjhj_bd_statistics_data_log` ADD INDEX `created_at`(`created_at`);
ALTER TABLE `zjhj_bd_statistics_user_log` ADD INDEX `mall_id`(`mall_id`);
ALTER TABLE `zjhj_bd_statistics_user_log` ADD INDEX `user_id`(`user_id`);
ALTER TABLE `zjhj_bd_statistics_user_log` ADD INDEX `num`(`num`);
ALTER TABLE `zjhj_bd_statistics_user_log` ADD INDEX `created_at`(`created_at`);
ALTER TABLE `zjhj_bd_statistics_user_log` ADD INDEX `is_delete`(`is_delete`);
ALTER TABLE `zjhj_bd_statistics_user_log` ADD INDEX `time_stamp`(`time_stamp`);
ALTER TABLE `zjhj_bd_statistics_data_log` ADD INDEX ( `mall_id` ), ADD INDEX ( `key` );
ALTER TABLE `zjhj_bd_statistics_user_log` ADD INDEX ( `mall_id` ), ADD INDEX ( `user_id` );
EOF;
        sql_execute($sql);
    },
    '4.3.14' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_community_activity` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态 0下架 1上架',   `is_delete` tinyint(4) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   `title` varchar(255) NOT NULL DEFAULT '' COMMENT '活动标题',   `start_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动开始时间',   `end_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动结束时间',   `is_area_limit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否单独区域购买',   `area_limit` longtext NOT NULL,   `full_price` varchar(200) NOT NULL DEFAULT '' COMMENT '满减方案json',   `condition` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭，1开启人数条件，2开启件数条件',   `num` int(11) NOT NULL DEFAULT '0' COMMENT '条件数量',   PRIMARY KEY (`id`) USING BTREE,   KEY `idx_1` (`mall_id`,`is_delete`,`created_at`),   KEY `sort` (`start_at`,`end_at`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='社区团购活动';
CREATE TABLE `zjhj_bd_community_activity_locking` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `activity_id` int(11) NOT NULL DEFAULT '0',   `middleman_id` int(11) NOT NULL DEFAULT '0',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_community_activity_robots` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `activity_id` int(11) NOT NULL DEFAULT '0',   `middleman_id` int(11) NOT NULL DEFAULT '0',   `robots_ids` varchar(100) NOT NULL DEFAULT '',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_community_address` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `user_id` int(11) NOT NULL,   `name` varchar(255) NOT NULL COMMENT '收货人',   `province_id` int(11) NOT NULL,   `province` varchar(255) NOT NULL COMMENT '省份名称',   `city_id` int(11) NOT NULL,   `city` varchar(255) NOT NULL COMMENT '城市名称',   `district_id` int(11) NOT NULL,   `district` varchar(255) NOT NULL COMMENT '县区名称',   `mobile` varchar(255) NOT NULL COMMENT '联系电话',   `detail` varchar(1000) NOT NULL COMMENT '详细地址',   `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认',   `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   `latitude` varchar(255) NOT NULL DEFAULT '' COMMENT '经度',   `longitude` varchar(255) NOT NULL DEFAULT '' COMMENT '纬度',   `location` varchar(255) NOT NULL DEFAULT '' COMMENT '位置',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='团长地址';
CREATE TABLE `zjhj_bd_community_bonus_log` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL DEFAULT '0',   `user_id` int(11) NOT NULL DEFAULT '0',   `order_id` int(11) NOT NULL DEFAULT '0',   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',   `desc` varchar(200) NOT NULL DEFAULT '',   `price` decimal(10,2) NOT NULL DEFAULT '0.00',   `profit_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '利润',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_community_cart` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL DEFAULT '0',   `mch_id` int(11) NOT NULL DEFAULT '0',   `user_id` int(11) NOT NULL DEFAULT '0',   `activity_id` int(11) NOT NULL DEFAULT '0',   `community_goods_id` int(11) NOT NULL DEFAULT '0',   `goods_id` int(11) NOT NULL DEFAULT '0',   `goods_attr_id` int(11) NOT NULL DEFAULT '0',   `attr_info` longtext NOT NULL,   `num` int(11) NOT NULL DEFAULT '0',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='社区团购用户购物车';
CREATE TABLE `zjhj_bd_community_goods` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `goods_id` int(11) NOT NULL DEFAULT '0',   `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',   `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',   PRIMARY KEY (`id`) USING BTREE,   KEY `activity` (`activity_id`) USING BTREE,   KEY `goods_id` (`goods_id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='社区团购商品';
CREATE TABLE `zjhj_bd_community_goods_attr` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `goods_id` int(11) NOT NULL DEFAULT '0',   `attr_id` int(11) NOT NULL DEFAULT '0',   `supply_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '供货价',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_community_log` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `user_id` int(11) NOT NULL DEFAULT '0',   `middleman_id` int(11) NOT NULL DEFAULT '0',   `activity_id` int(11) NOT NULL DEFAULT '0',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_community_middleman` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '可提现利润',   `total_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '累计利润',   `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0--申请中 1--通过 2--拒绝 -1--未支付',   `apply_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '申请时间',   `become_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '通过审核时间',   `delete_first_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除后是否显示0--不显示 1--显示',   `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '审核结果原因',   `content` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',   `name` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人',   `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '联系电话',   `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   `pay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付的金额',   `token` varchar(255) NOT NULL DEFAULT '',   `pay_type` tinyint(255) NOT NULL DEFAULT '0' COMMENT '支付方式',   `pay_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '支付时间',   `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '销售总额',   `order_count` int(11) NOT NULL DEFAULT '0' COMMENT '订单总数',   PRIMARY KEY (`id`),   KEY `user_id` (`user_id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='社区团购 团长信息';
CREATE TABLE `zjhj_bd_community_middleman_activity` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `middleman_id` int(11) NOT NULL DEFAULT '0' COMMENT '团长user_id',   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',   `is_remind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否提醒 0--未提醒 1--已提醒',   `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_community_order` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL DEFAULT '0',   `order_id` int(11) NOT NULL DEFAULT '0',   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',   `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',   `middleman_id` int(11) NOT NULL COMMENT '团长ID',   `profit_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总利润',   `profit_data` text NOT NULL COMMENT '利润详情',   `full_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '满多少',   `discount_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `activity_no` varchar(100) NOT NULL DEFAULT '' COMMENT '活动编号',   `no` int(11) NOT NULL DEFAULT '0' COMMENT '编号',   `num` int(11) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_community_relations` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `user_id` int(11) NOT NULL DEFAULT '0',   `middleman_id` int(11) NOT NULL DEFAULT '0',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='社区团购 用户与团长关系';
CREATE TABLE `zjhj_bd_community_switch` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `middleman_id` int(11) NOT NULL DEFAULT '0',   `activity_id` int(11) NOT NULL DEFAULT '0',   `goods_id` int(11) NOT NULL DEFAULT '0',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='活动商品关闭表';
CREATE TABLE `zjhj_bd_finance` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `order_no` varchar(255) NOT NULL DEFAULT '' COMMENT '订单号',   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',   `service_charge` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现手续费（%）',   `type` varchar(255) NOT NULL DEFAULT '' COMMENT '提现方式 auto--自动打款 wechat--微信打款 alipay--支付宝打款 bank--银行转账 balance--打款到余额',   `extra` longtext COMMENT '额外信息 例如微信账号、支付宝账号等',   `status` int(11) NOT NULL DEFAULT '0' COMMENT '提现状态 0--申请 1--同意 2--已打款 3--驳回',   `is_delete` int(11) NOT NULL DEFAULT '0',   `created_at` datetime NOT NULL,   `updated_at` datetime NOT NULL,   `deleted_at` datetime NOT NULL,   `content` longtext,   `name` varchar(255) NOT NULL DEFAULT '' COMMENT '真实姓名',   `model` varchar(255) NOT NULL DEFAULT '' COMMENT '提现插件(share,bonus,stock,region,mch)',   `transfer_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0.待转账 | 1.已转账  | 2.拒绝转账',   `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '手机号',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='提现记录汇总表';
ALTER TABLE `zjhj_bd_order_detail`  ADD INDEX (`created_at`);
EOF;
        sql_execute($sql);
    },
    '4.3.20' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_region_cash` MODIFY COLUMN `order_no`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '订单号' AFTER `user_id`, MODIFY COLUMN `type`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '提现方式 auto--自动打款 wechat--微信打款 alipay--支付宝打款 bank--银行转账 balance--打款到余额' AFTER `service_charge`, MODIFY COLUMN `extra`  longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '额外信息 例如微信账号、支付宝账号等' AFTER `type`, MODIFY COLUMN `content`  longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL AFTER `deleted_at`, DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci; 
ALTER TABLE `zjhj_bd_finance` MODIFY COLUMN `order_no`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '订单号' AFTER `user_id`, MODIFY COLUMN `type`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '提现方式 auto--自动打款 wechat--微信打款 alipay--支付宝打款 bank--银行转账 balance--打款到余额' AFTER `service_charge`, MODIFY COLUMN `extra`  longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '额外信息 例如微信账号、支付宝账号等' AFTER `type`, MODIFY COLUMN `content`  longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL AFTER `deleted_at`, MODIFY COLUMN `name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '真实姓名' AFTER `content`, MODIFY COLUMN `model`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '提现插件(share,bonus,stock,region,mch)' AFTER `name`, MODIFY COLUMN `phone`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号' AFTER `transfer_status`, DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci;
alter table `zjhj_bd_order_detail_express` add `city_mobile` varchar(255);
alter table `zjhj_bd_order_detail_express` add `city_info` longtext;
alter table `zjhj_bd_order_detail_express` add `city_name` varchar(255);
alter table `zjhj_bd_order_detail_express` add `shop_order_id` varchar(255);
alter table `zjhj_bd_order_detail_express` add `status` int(11) default 0 not null;
alter table `zjhj_bd_order_detail_express` add `express_type` varchar(255) default '';
CREATE TABLE `zjhj_bd_city_service` (`id` int(11) NOT NULL AUTO_INCREMENT,`mall_id` int(11) NOT NULL,`platform` varchar(255) DEFAULT NULL COMMENT '所属平台',`name` varchar(255) NOT NULL COMMENT '配送名称',`distribution_corporation` int(11) NOT NULL COMMENT '配送公司 1.顺丰|2.闪送|3.美团配送|4.达达',`shop_no` varchar(255) DEFAULT NULL COMMENT '门店编号',`data` text,`created_at` timestamp NULL DEFAULT NULL,`is_delete` int(1) NOT NULL DEFAULT '0',`service_type` varchar(255) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_pond_setting` ADD COLUMN `bg_pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '背景图' AFTER `is_print`,ADD COLUMN `bg_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '背景颜色' AFTER `bg_pic`,ADD COLUMN `bg_color_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '背景颜色类型' AFTER `bg_color`,ADD COLUMN `bg_gradient_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '背景渐变颜色' AFTER `bg_color_type`;
ALTER TABLE `zjhj_bd_scratch_setting` ADD COLUMN `bg_pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '背景图' AFTER `is_print`;
alter table `zjhj_bd_order_detail_express` add `city_service_id` int(11) default 0 not null;
ALTER TABLE `zjhj_bd_recharge` ADD COLUMN `send_member_id` int(11) NOT NULL DEFAULT 0 COMMENT '赠送的会员' AFTER `send_integral`;
ALTER TABLE `zjhj_bd_recharge_orders` ADD COLUMN `send_member_id` int(11) NOT NULL DEFAULT 0 COMMENT '赠送的会员' AFTER `send_integral`;
EOF;
        sql_execute($sql);
    },

    '4.3.25' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_goods_coupon_relation` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `goods_id` int(11) NOT NULL,   `coupon_id` int(11) NOT NULL,   `num` int(11) NOT NULL DEFAULT '1',   `is_delete` int(11) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品赠送优惠券信息';
ALTER TABLE `zjhj_bd_goods` ADD COLUMN `give_balance`  decimal(10,2) NOT NULL DEFAULT 0 COMMENT '赠送余额' AFTER `give_integral_type`, ADD COLUMN `give_balance_type`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '赠送余额类型1.固定值 |2.百分比' AFTER `give_balance`;
CREATE TABLE `zjhj_bd_user_coupon_goods` ( `id`  int(11) NOT NULL AUTO_INCREMENT , `mall_id`  int(11) NOT NULL DEFAULT 0 COMMENT '商城ID' , `user_coupon_id`  int(11) NOT NULL DEFAULT 0 COMMENT '优惠券ID' , `user_id`  int(11) NOT NULL DEFAULT 0 COMMENT '用户ID' , `goods_id`  int(11) NOT NULL COMMENT '商品ID' , `is_delete`  int(11) NOT NULL DEFAULT 0 COMMENT '是否删除 0--不删除 1--删除' , `created_at`  timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间' , `updated_at`  timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间' , `deleted_at`  timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '删除时间' , PRIMARY KEY (`id`), INDEX `mall_id` (`mall_id`) USING BTREE , INDEX `user_coupon_id` (`user_coupon_id`) USING BTREE , INDEX `user_id` (`user_id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='随商品赠送优惠券关联表' AUTO_INCREMENT=1 ROW_FORMAT=DYNAMIC;
ALTER TABLE `zjhj_bd_favorite` ADD COLUMN `mirror_price`  decimal(10,2) NOT NULL DEFAULT 0 COMMENT '收藏时的售价' AFTER `goods_id`;
ALTER TABLE `zjhj_bd_balance_log` ADD COLUMN `order_no`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '订单号' AFTER `custom_desc`;  
ALTER TABLE `zjhj_bd_integral_log` ADD COLUMN `order_no`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '订单号' AFTER `custom_desc`;
CREATE TABLE `zjhj_bd_city_preview_order` ( `id` int(11) NOT NULL AUTO_INCREMENT, `result_data` json DEFAULT NULL, `order_info` json DEFAULT NULL, `created_at` timestamp NULL DEFAULT NULL, `order_detail_sign` varchar(255) DEFAULT NULL, `all_order_info` json DEFAULT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },
    '4.3.29' => function () {
        $sql = <<<EOF
DROP TABLE IF EXISTS `zjhj_bd_city_preview_order`;
CREATE TABLE `zjhj_bd_city_preview_order` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `result_data` text,   `order_info` text,   `created_at` timestamp NULL DEFAULT NULL,   `order_detail_sign` varchar(255) DEFAULT NULL,   `all_order_info` text,   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },
    '4.3.30' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_coupon` ADD COLUMN `can_receive_count`  int(11) NOT NULL DEFAULT 1 COMMENT '可领取数量', ADD COLUMN `app_share_title`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '', ADD COLUMN `app_share_pic`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '';
ALTER TABLE `zjhj_bd_user_card` ADD COLUMN `receive_id`  int NOT NULL DEFAULT 0 COMMENT '转赠领取的用户id', ADD COLUMN `parent_card_id`  int NOT NULL DEFAULT 0 COMMENT '转赠的用户卡券id';
ALTER TABLE `zjhj_bd_goods_cards` ADD COLUMN `app_share_title`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '', ADD COLUMN `app_share_pic`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '';
EOF;
        sql_execute($sql);
    },
    '4.3.34' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_vip_card_order` ADD COLUMN `sign`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' AFTER `deleted_at`;
CREATE TABLE `zjhj_bd_exchange_code`  (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `library_id` int(11) NOT NULL,   `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 后台 1礼品卡',   `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,   `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态开关 0禁用 1 启用 2 兑换 3结束',   `validity_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',   `valid_end_time` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   `valid_start_time` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   `created_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   `r_user_id` int(11) NOT NULL DEFAULT 0,   `r_raffled_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   `r_rewards` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,   `r_origin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '兑换来源',   `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '后台联系人',   `mobile` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '后台手机号码',   PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic; 
CREATE TABLE `zjhj_bd_exchange_code_log`  (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `is_success` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否兑换成功',   `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',   `origin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'admin app',   `remake` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '简单说明',   `created_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_exchange_coupon_relation`  (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `code_id` int(11) NOT NULL,   `user_coupon_id` int(11) NOT NULL,   `created_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_exchange_goods`  (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `library_id` int(11) NOT NULL DEFAULT 0,   `goods_id` int(11) NOT NULL,   `created_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_exchange_library`  (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '名称',   `remark` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '说明',   `expire_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'all' COMMENT 'all 永久 fixed 固定 relatively相对',   `expire_start_time` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '固定开始',   `expire_end_time` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '固定开始',   `expire_start_day` int(10) NOT NULL DEFAULT 0 COMMENT '相对开始',   `expire_end_day` int(10) NOT NULL DEFAULT 0 COMMENT '相对结束',   `mode` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 全部 1 份',   `code_format` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'english_num' COMMENT 'english_num, num',   `rewards` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '奖励品',   `rewards_s` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '奖励品类型 后台搜索使用',   `is_recycle` tinyint(1) NOT NULL COMMENT '是否加入回收站',   `recycle_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   `is_delete` tinyint(1) NOT NULL DEFAULT 0,   `created_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_exchange_order`  (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `order_id` int(11) NOT NULL,   `exchange_id` int(11) NOT NULL,   `code_id` int(11) NOT NULL,   `goods_id` int(11) NOT NULL,   `is_delete` tinyint(1) NOT NULL DEFAULT 0,   `created_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_exchange_record_order`  (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `order_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,   `code_id` int(11) NOT NULL,   `is_delete` tinyint(1) NOT NULL DEFAULT 0,   `created_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
CREATE TABLE `zjhj_bd_exchange_reward_result`  (   `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,   `code_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',   `token` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',   `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,   PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
ALTER TABLE `zjhj_bd_user_card` ADD COLUMN `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '简单备注来源' AFTER `parent_card_id`;
ALTER TABLE `zjhj_bd_share_cash` MODIFY COLUMN `order_no`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '订单号' AFTER `user_id`, MODIFY COLUMN `type`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '提现方式 auto--自动打款 wechat--微信打款 alipay--支付宝打款 bank--银行转账 balance--打款到余额' AFTER `service_charge`, MODIFY COLUMN `extra`  longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '额外信息 例如微信账号、支付宝账号等' AFTER `type`, MODIFY COLUMN `content`  longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL AFTER `deleted_at`, DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci;  
CREATE TABLE `zjhj_bd_exchange_svip_order`  (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `order_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `code_id` int(11) NOT NULL,   `is_delete` tinyint(1) NOT NULL DEFAULT 0,   `created_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
EOF;
        sql_execute($sql);
    },
    '4.3.40' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_goods` ADD COLUMN `shipping_id`  int(11) NOT NULL DEFAULT 0 COMMENT '包邮模板ID' AFTER `forehead`;
ALTER TABLE `zjhj_bd_free_delivery_rules` ADD COLUMN `status`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否默认  0否 1是' AFTER `detail`;
ALTER TABLE `zjhj_bd_free_delivery_rules` ADD COLUMN `type`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:订单满额包邮  2:订单满件包邮  3:单商品满额包邮 4:单商品满件包邮' AFTER `name`;
CREATE TABLE `zjhj_bd_full_reduce_activity` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `name` varchar(255) NOT NULL COMMENT '活动标题',   `content` varchar(8192) NOT NULL DEFAULT '',   `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0下架 1上架',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   `start_at` timestamp NOT NULL,   `end_at` timestamp NOT NULL,   `appoint_type` tinyint(1) NOT NULL COMMENT '1:全部商品\r\n2:全部自营商品\r\n3:指定商品参加\r\n4:指定商品不参加',   `rule_type` tinyint(1) NOT NULL COMMENT '1:阶梯满减\r\n2:循环满减',   `discount_rule` varchar(512) NOT NULL COMMENT '满减规则',   `appoint_goods` text NOT NULL,   `noappoint_goods` text NOT NULL,   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_order` ADD COLUMN `full_reduce_price`  decimal(10,2) NOT NULL DEFAULT 0 COMMENT '满减活动优惠价格' AFTER `member_discount_price`;
ALTER TABLE `zjhj_bd_gift_send_order` ADD COLUMN `full_reduce_price`  decimal(10,2) NOT NULL DEFAULT 0 COMMENT '满减活动优惠价格' AFTER `member_discount_price`;
ALTER TABLE `zjhj_bd_full_reduce_activity` MODIFY COLUMN `discount_rule`  varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '阶梯满减规则' AFTER `rule_type`, ADD COLUMN `loop_discount_rule`  varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '循环满减规则' AFTER `discount_rule`;
ALTER TABLE `zjhj_bd_printer_setting` ADD COLUMN `order_send_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' AFTER `show_type`;
ALTER TABLE `zjhj_bd_delivery` ADD COLUMN `business_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 1 COMMENT '业务类型' AFTER `is_goods_alias`;
CREATE TABLE `zjhj_bd_goods_hot_search`  (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `goods_id` int(11) NOT NULL COMMENT '商品id',   `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '热搜词',   `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'goods 自动 hot-search 手动',   `sort` smallint(2) NOT NULL DEFAULT 0 COMMENT '排序',   `is_delete` tinyint(1) NOT NULL DEFAULT 0,   `created_at` timestamp(0) NOT NULL,   `deleted_at` timestamp(0) NOT NULL,   PRIMARY KEY (`id`) USING BTREE,   INDEX `mall_id`(`mall_id`, `type`, `is_delete`) USING BTREE,   INDEX `goods_id`(`goods_id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
ALTER TABLE `zjhj_bd_goods` ADD COLUMN `detail_count` int(11) NOT NULL DEFAULT 0 COMMENT '详情浏览量统计' AFTER `sales`;
ALTER TABLE `zjhj_bd_goods_cards` ADD COLUMN `is_allow_send` int(1) NOT NULL DEFAULT 0 COMMENT '是否允许转赠';
EOF;
        sql_execute($sql);
    },
    '4.3.44' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_user` ADD COLUMN `unionid`  varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' AFTER `mobile`;  
EOF;
        sql_execute($sql);
    },
    '4.3.45' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_goods_services` ADD COLUMN `pic`  varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '商品服务标识' AFTER `name`; 
ALTER TABLE `zjhj_bd_goods` ADD COLUMN `guarantee_title`  varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '商品服务标题' AFTER `detail_count`, ADD COLUMN `guarantee_pic`  varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '商品服务标识' AFTER `guarantee_title`;
ALTER TABLE `zjhj_bd_vip_card_setting` ADD COLUMN `is_order_form`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '下单表单开关' AFTER `rules`, ADD COLUMN `order_form`  text NULL AFTER `is_order_form`;
ALTER TABLE `zjhj_bd_goods_warehouse` ADD COLUMN `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '副标题' AFTER `name`;
ALTER TABLE `zjhj_bd_share` ADD COLUMN `form` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '分销商自定义表单' AFTER `delete_first_show`;
ALTER TABLE `zjhj_bd_order_comments` ADD COLUMN `goods_info` longtext NULL COMMENT '商品信息' AFTER `is_top`;
ALTER TABLE `zjhj_bd_order_comments` ADD COLUMN `attr_id` int(11) NOT NULL DEFAULT 0 COMMENT '规格' AFTER `goods_info`;
ALTER TABLE `zjhj_bd_delivery` ADD COLUMN `kd100_business_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '快递100 业务类型' AFTER `is_goods`, ADD COLUMN `kd100_template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '快递100 模板' AFTER `kd100_business_type`;
EOF;
        sql_execute($sql);
    },
    '4.3.52' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_wholesale_goods` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `goods_id` int(11) NOT NULL,   `mall_id` int(11) NOT NULL,   `type` tinyint(1) NOT NULL COMMENT '优惠方式，0折扣，1减钱\r\n',   `wholesale_rules` varchar(2048) NOT NULL DEFAULT '' COMMENT '批发规则',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `rise_num` int(11) NOT NULL DEFAULT '0' COMMENT '0不设置',   `rules_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '规则开关，0关闭，1开启',   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='批发商品';
CREATE TABLE `zjhj_bd_wholesale_order` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL DEFAULT '0',   `order_id` int(11) NOT NULL DEFAULT '0',   `discount` decimal(10,2) NOT NULL DEFAULT '0.00',   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_goods` ADD COLUMN `param_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '参数名称' AFTER `guarantee_pic`, ADD COLUMN `param_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '参数内容' AFTER `param_name`;
ALTER TABLE `zjhj_bd_coupon` ADD COLUMN `use_obtain` tinyint(1) NOT NULL DEFAULT 0 COMMENT '领取后赠送' AFTER `app_share_pic`;
ALTER TABLE `zjhj_bd_coupon_mall_relation` ADD COLUMN `order_id` int(11) NOT NULL COMMENT '订单id' AFTER `is_delete`, ADD COLUMN `type` varchar(20) NOT NULL COMMENT ' use优惠券自动发放' AFTER `order_id`;
alter table zjhj_bd_store add `extra_attributes` text not null;
EOF;
        sql_execute($sql);
    },
    '4.3.60' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_wxapp_platform` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `appid` varchar(128) NOT NULL COMMENT '第三方平台应用appid',   `appsecret` varchar(255) NOT NULL COMMENT '第三方平台应用appsecret',   `token` varchar(255) NOT NULL COMMENT '第三方平台应用token（消息校验Token）',   `encoding_aes_key` varchar(512) NOT NULL COMMENT '第三方平台应用Key（消息加解密Key）',   `component_access_token` varchar(255) NOT NULL DEFAULT '',   `token_expires` int(11) NOT NULL DEFAULT '0' COMMENT 'token过期时间',   `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '授权类型\r\n1：公众号\r\n2：小程序\r\n3：公众号/小程序同时展现\r\n',   `third_appid` varchar(128) NOT NULL COMMENT '第三方平台绑定的应用appid',   `domain` longtext NOT NULL COMMENT '域名',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
CREATE TABLE `zjhj_bd_wxapp_wxminiprogram_audit` (   `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',   `appid` varchar(45) NOT NULL DEFAULT '' COMMENT '小程序appid',   `auditid` varchar(45) NOT NULL DEFAULT '' COMMENT '审核编号',   `version` varchar(45) NOT NULL DEFAULT '',   `template_id` int(11) NOT NULL COMMENT '模板id',   `status` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '审核状态，其中0为审核成功，1为审核失败，2为审核中，3已提交审核, 4已发布',   `reason` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '当status=1，审核被拒绝时，返回的拒绝原因',   `created_at` timestamp NOT NULL COMMENT '提交审核时间',   `release_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `is_delete` tinyint(4) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='微信小程序提交审核的小程序';
CREATE TABLE `zjhj_bd_wxapp_wxminiprograms` (   `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',   `mall_id` int(11) NOT NULL COMMENT '商城id',   `nick_name` varchar(45) NOT NULL DEFAULT '' COMMENT '微信小程序名称',   `token` varchar(45) NOT NULL DEFAULT '' COMMENT '平台生成的token值',   `head_img` varchar(255) NOT NULL DEFAULT '' COMMENT '微信小程序头像',   `verify_type_info` tinyint(1) NOT NULL DEFAULT '0' COMMENT '授权方认证类型，-1代表未认证，0代表微信认证',   `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示，0显示，1隐藏',   `user_name` varchar(45) NOT NULL DEFAULT '' COMMENT '原始ID',   `qrcode_url` varchar(2048) NOT NULL DEFAULT '' COMMENT '二维码图片的URL',   `business_info` varchar(2048) NOT NULL DEFAULT '' COMMENT 'json格式。用以了解以下功能的开通状况（0代表未开通，1代表已开通）： open_store:是否开通微信门店功能 open_scan:是否开通微信扫商品功能 open_pay:是否开通微信支付功能 open_card:是否开通微信卡券功能 open_shake:是否开通微信摇一摇功能',   `idc` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'idc',   `principal_name` varchar(45) NOT NULL DEFAULT '' COMMENT '小程序的主体名称',   `signature` varchar(255) NOT NULL DEFAULT '' COMMENT '帐号介绍',   `miniprograminfo` varchar(255) NOT NULL DEFAULT '' COMMENT 'json格式。判断是否为小程序类型授权，包含network小程序已设置的各个服务器域名',   `func_info` longtext COMMENT 'json格式。权限集列表，ID为17到19时分别代表： 17.帐号管理权限 18.开发管理权限 19.客服消息管理权限 请注意： 1）该字段的返回不会考虑小程序是否具备该权限集的权限（因为可能部分具备）。',   `authorizer_appid` varchar(45) NOT NULL DEFAULT '' COMMENT '小程序appid',   `authorizer_access_token` varchar(255) NOT NULL DEFAULT '' COMMENT '授权方接口调用凭据（在授权的公众号或小程序具备API权限时，才有此返回值），也简称为令牌',   `authorizer_expires` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'refresh有效期',   `authorizer_refresh_token` varchar(255) NOT NULL DEFAULT '' COMMENT '接口调用凭据刷新令牌',   `created_at` timestamp NOT NULL COMMENT '授权时间',   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='微信小程序授权列表';
ALTER TABLE `zjhj_bd_wxapp_config` MODIFY COLUMN `appid`  varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' AFTER `mall_id`, MODIFY COLUMN `appsecret`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' AFTER `appid`;
ALTER TABLE `zjhj_bd_wxapp_wxminiprograms` ADD COLUMN `domain`  longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '业务域名' AFTER `authorizer_refresh_token`;  
EOF;
        sql_execute($sql);
    },
    '4.3.66' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_wxapp_fast_create` ( `id`  int(11) NOT NULL AUTO_INCREMENT , `mall_id`  int(11) NOT NULL , `name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '企业名称' , `code`  varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '企业代码' , `code_type`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '企业代码类型（1：统一社会信用代码， 2：组织机构代码，3：营业执照注册号）' , `legal_persona_wechat`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '法人微信' , `legal_persona_name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '法人姓名' , `component_phone`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '第三方联系电话' , `md5`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '唯一标识' , `status`  tinyint(4) NOT NULL DEFAULT '-2' , `appid`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建小程序appid' , `auth_code`  varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '第三方授权码' , `updated_at`  timestamp NOT NULL , `created_at`  timestamp NOT NULL , `deleted_at`  timestamp NOT NULL , `is_delete`  tinyint(1) NOT NULL DEFAULT 0 , PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1 ROW_FORMAT=DYNAMIC ;
alter table zjhj_bd_live_goods modify column audit_id varchar(255) default null;
EOF;
        sql_execute($sql);
    },
    '4.3.68' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_video_number_data` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `mall_id` int(11) DEFAULT '0',   `mch_id` int(11) DEFAULT '0',   `type` varchar(255) DEFAULT NULL,   `key` varchar(255) DEFAULT NULL,   `value` varchar(255) DEFAULT NULL,   `url` varchar(255) DEFAULT NULL,   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   `is_delete` int(11) DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_video_number` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `mall_id` int(11) DEFAULT '0',   `mch_id` int(11) DEFAULT '0',   `user_id` int(11) DEFAULT '0',   `goods_id` int(11) DEFAULT '0',   `media_id` varchar(255) DEFAULT NULL,   `msg_id` varchar(255) DEFAULT NULL,   `status` varchar(255) DEFAULT NULL,   `extra_attributes` text,   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },
    '4.3.69' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_order_express_single` MODIFY COLUMN `print_teplate` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL AFTER `ebusiness_id`;
EOF;
        sql_execute($sql);
    },
    '4.3.70' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_exchange_library` ADD COLUMN `is_limit` tinyint(1) NOT NULL DEFAULT 0 COMMENT '限制兑换' AFTER `deleted_at`, ADD COLUMN `limit_user_num` int(8) NOT NULL DEFAULT 0 COMMENT '每位用户每天限制兑换成功次数' AFTER `is_limit`, ADD COLUMN `limit_user_success_num` int(8) NOT NULL DEFAULT 0 COMMENT '永久兑换成功次数字' AFTER `limit_user_num`, ADD COLUMN `limit_user_type` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'day' COMMENT '限制兑换类型 day all' AFTER `limit_user_success_num`;
ALTER TABLE `zjhj_bd_exchange_code_log` ADD COLUMN `library_id` int(11) NOT NULL  COMMENT '库id';
ALTER TABLE `zjhj_bd_delivery` ADD COLUMN `kd100_t_height` int(11) NOT NULL DEFAULT 150 COMMENT '打印纸高度' AFTER `kd100_template`,ADD COLUMN `kd100_t_width` int(11) NOT NULL DEFAULT 100 COMMENT '打印纸宽度' AFTER `kd100_t_height`;
EOF;
        sql_execute($sql);
    },
    '4.3.76' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_pay_type` ( `id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT , `mall_id`  int(11) NOT NULL , `name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '支付名称' , `type`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:微信  2:支付宝' , `appid`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' , `mchid`  varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' , `key`  varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' , `cert_pem`  varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' , `key_pem`  varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' , `is_service`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否为服务商支付  0否 1是' , `service_key`  varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' , `service_appid`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '服务商appid' , `service_mchid`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '服务商mch_id' , `service_cert_pem`  varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' , `service_key_pem`  varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' , `is_auto_add`  tinyint(4) NOT NULL DEFAULT 0 COMMENT '0否 1是' , `alipay_appid`  varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' , `app_private_key`  varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付宝应用私钥' , `alipay_public_key`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '支付宝平台公钥' , `appcert`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL , `alipay_rootcert`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '支付宝根证书' , `is_delete`  tinyint(1) NOT NULL DEFAULT 0 , `updated_at`  timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP , `created_at`  timestamp NOT NULL , `deleted_at`  timestamp NOT NULL , PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1 ROW_FORMAT=DYNAMIC ;
CREATE TABLE `zjhj_bd_wechat_config` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `appid` varchar(255) NOT NULL DEFAULT '',   `appsecret` varchar(255) NOT NULL DEFAULT '',   `is_delete` tinyint(4) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   `name` varchar(255) NOT NULL DEFAULT '',   `logo` varchar(255) NOT NULL DEFAULT '',   `qrcode` varchar(255) NOT NULL DEFAULT '',   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_wechat_template` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `tpl_name` varchar(65) NOT NULL DEFAULT '',   `tpl_id` varchar(255) NOT NULL DEFAULT '',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_wechat_wxmpprograms` (   `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',   `mall_id` int(11) NOT NULL COMMENT '商城id',   `nick_name` varchar(45) DEFAULT '' COMMENT '公众号名称',   `token` varchar(45) NOT NULL DEFAULT '' COMMENT '平台生成的token值',   `head_img` varchar(255) NOT NULL DEFAULT '' COMMENT '公众号头像',   `verify_type_info` tinyint(1) NOT NULL DEFAULT '0' COMMENT '授权方认证类型，-1代表未认证，0代表微信认证',   `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示，0显示，1隐藏',   `user_name` varchar(45) NOT NULL DEFAULT '' COMMENT '原始ID',   `qrcode_url` varchar(2048) NOT NULL DEFAULT '' COMMENT '二维码图片的URL',   `business_info` varchar(2048) NOT NULL DEFAULT '' COMMENT 'json格式。用以了解以下功能的开通状况（0代表未开通，1代表已开通）： open_store:是否开通微信门店功能 open_scan:是否开通微信扫商品功能 open_pay:是否开通微信支付功能 open_card:是否开通微信卡券功能 open_shake:是否开通微信摇一摇功能',   `idc` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'idc',   `principal_name` varchar(45) NOT NULL DEFAULT '' COMMENT '公众号的主体名称',   `signature` varchar(255) NOT NULL DEFAULT '' COMMENT '帐号介绍',   `miniprograminfo` varchar(255) NOT NULL DEFAULT '' COMMENT 'json格式。判断是否为小程序类型授权，包含network小程序已设置的各个服务器域名',   `func_info` longtext COMMENT 'json格式。权限集列表，ID为17到19时分别代表： 17.帐号管理权限 18.开发管理权限 19.客服消息管理权限 请注意： 1）该字段的返回不会考虑小程序是否具备该权限集的权限（因为可能部分具备）。',   `authorizer_appid` varchar(45) NOT NULL DEFAULT '' COMMENT '公众号appid',   `authorizer_access_token` varchar(255) NOT NULL DEFAULT '' COMMENT '授权方接口调用凭据（在授权的公众号或小程序具备API权限时，才有此返回值），也简称为令牌',   `authorizer_expires` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'refresh有效期',   `authorizer_refresh_token` varchar(255) NOT NULL DEFAULT '' COMMENT '接口调用凭据刷新令牌',   `created_at` timestamp NOT NULL COMMENT '授权时间',   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='公众号授权列表';
CREATE TABLE `zjhj_bd_user_platform` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `platform` varchar(255) NOT NULL DEFAULT '' COMMENT '用户所属平台标识',   `platform_id` varchar(255) NOT NULL DEFAULT '' COMMENT '用户所属平台的用户id',   `password` varchar(255) NOT NULL DEFAULT '' COMMENT 'h5平台使用的密码',   `unionid` varchar(255) NOT NULL DEFAULT '' COMMENT '微信平台使用的unionid',   `subscribe` tinyint(1) NOT NULL DEFAULT '0' COMMENT '微信平台使用的是否关注',   PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_user_center` ( `id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT , `mall_id`  int(11) NOT NULL , `config`  longblob NOT NULL , `is_delete`  tinyint(1) NOT NULL DEFAULT 0 , `created_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , `updated_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , `deleted_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , `name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' , `is_recycle`  tinyint(1) NOT NULL DEFAULT 0 , `platform`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' , PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1 ROW_FORMAT=DYNAMIC ;
ALTER TABLE `zjhj_bd_diy_page` ADD COLUMN `platform`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' AFTER `deleted_at`;
ALTER TABLE `zjhj_bd_order` ADD COLUMN `platform`  varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'wxapp 微信小程序' AFTER `cancel_data`;
ALTER TABLE `zjhj_bd_payment_order_union` ADD COLUMN `platform`  varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' AFTER `app_version`;
EOF;
        sql_execute($sql);
    },
    '4.3.81' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_wxapp_fast_create` MODIFY COLUMN `status`  int(11) NOT NULL DEFAULT '-2' AFTER `md5`; 
EOF;
        sql_execute($sql);
    },
    '4.3.82' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_user_info` MODIFY COLUMN `total_balance` decimal(12, 2) NOT NULL COMMENT '总余额' AFTER `balance`;
EOF;
        sql_execute($sql);
    },
    '4.3.83' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_user_info` MODIFY COLUMN `total_balance` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '总余额' AFTER `balance`;
EOF;
        sql_execute($sql);
    },
    '4.3.84' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_lottery` ADD COLUMN `deplete_integral_num` int(11) NOT NULL DEFAULT 0 COMMENT '消耗积分' AFTER `buy_goods_id`;
CREATE TABLE `zjhj_bd_goods_params_template`  (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `mch_id` int(11) NOT NULL DEFAULT 0,   `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '模板名称',   `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '参数内容',   `select_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '搜索使用',   `is_delete` tinyint(1) NOT NULL DEFAULT 0,   `created_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),   `deleted_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
EOF;
        sql_execute($sql);
    },
    '4.3.87' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_order_express_single` ADD COLUMN `order_detail_id` int(11) NOT NULL DEFAULT 0 AFTER `deleted_at`;
EOF;
        sql_execute($sql);
    },
    '4.3.89' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_scan_code_pay_activities_groups_rules` ADD COLUMN `send_money_type`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '赠送余额类型1.固定2.百分比' AFTER `send_money`;
ALTER TABLE `zjhj_bd_lottery_setting` ADD COLUMN `bg_pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '背景图' AFTER `is_print`, ADD COLUMN `bg_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '背景颜色' AFTER `bg_pic`, ADD COLUMN `bg_color_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '背景颜色类型' AFTER `bg_color`, ADD COLUMN `bg_gradient_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '背景渐变颜色' AFTER `bg_color_type`;
ALTER TABLE `zjhj_bd_goods` ADD COLUMN `limit_buy_status` smallint(1) NOT NULL DEFAULT 1 COMMENT '限购状态0--关闭 1--开启', ADD COLUMN `limit_buy_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'day' COMMENT '限购类型 day--每天 week--每周 month--每月 all--永久', ADD COLUMN `limit_buy_value` int(11) NOT NULL DEFAULT -1 COMMENT '限购数量', ADD COLUMN `min_number` int(11) NOT NULL DEFAULT 1 COMMENT '起售数量', ADD COLUMN `is_setting_show_and_buy_auth` smallint(1) NOT NULL DEFAULT 0 COMMENT '是否单独设置浏览和购买权限', ADD COLUMN `show_goods_auth` varchar(255) NOT NULL DEFAULT '-1' COMMENT '会员等级浏览权限', ADD COLUMN `buy_goods_auth` varchar(255) NOT NULL DEFAULT '-1' COMMENT '会员等级购买权限';
EOF;
        sql_execute($sql);
    },
    '4.4.1' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_user_info` ADD COLUMN `pay_password`  VARCHAR(255) NOT NULL DEFAULT '' COMMENT '支付密码';
ALTER TABLE `zjhj_bd_order_detail` ADD COLUMN `erase_price` decimal(10, 2) NOT NULL DEFAULT 0 COMMENT '抹零价格' AFTER `member_discount_price`;
CREATE TABLE `zjhj_bd_teller_cashier` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `mch_id` int(11) NOT NULL DEFAULT '0',   `user_id` int(11) NOT NULL COMMENT '用户ID',   `number` varchar(255) NOT NULL COMMENT '收银员编号',   `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '门店ID',   `creator_id` int(11) NOT NULL COMMENT '创建者ID',   `status` tinyint(1) DEFAULT '0' COMMENT '状态0.不启用|1.启用',   `push_money` decimal(10,2) DEFAULT '0.00' COMMENT '提成总金额',   `sale_money` decimal(10,2) DEFAULT '0.00' COMMENT '销售总金额',   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   `is_delete` tinyint(1) DEFAULT '0',   PRIMARY KEY (`id`),   KEY `index` (`mall_id`,`mch_id`,`user_id`,`store_id`,`creator_id`,`status`,`is_delete`) USING BTREE ) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_teller_orders` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) DEFAULT NULL,   `mch_id` int(11) DEFAULT '0',   `order_id` int(11) DEFAULT '0' COMMENT '订单ID',   `re_order_id` int(11) DEFAULT '0' COMMENT '充值订单ID',   `cashier_id` int(11) DEFAULT NULL COMMENT '收银员ID',   `sales_id` int(11) DEFAULT '0' COMMENT '导购员ID',   `order_type` varchar(255) DEFAULT NULL COMMENT '订单类型',   `add_money` decimal(10,2) DEFAULT '0.00' COMMENT '订单加价',   `change_price_type` varchar(255) DEFAULT NULL COMMENT '改价类型 加价|减价',   `change_price` decimal(10,2) DEFAULT '0.00' COMMENT '订单改价金额',   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `is_refund` tinyint(1) DEFAULT '0' COMMENT '是否有退款0.否|1.是',   `refund_money` decimal(10,2) DEFAULT '0.00' COMMENT '退款总金额',   `order_query` int(11) DEFAULT '0' COMMENT '付款码订单查询次数',   `is_pay` int(11) DEFAULT '0' COMMENT '是否支付0.未付款|1.已付款',   `pay_type` int(11) DEFAULT '0' COMMENT '支付方式',   `work_log_id` int(11) DEFAULT '0' COMMENT '交班记录ID',   `is_statistics` tinyint(1) DEFAULT '0' COMMENT '是否统计0.否|1.是',   PRIMARY KEY (`id`),   KEY `index` (`mall_id`,`mch_id`,`order_id`,`re_order_id`,`cashier_id`,`sales_id`,`order_type`) USING BTREE ) ENGINE=InnoDB AUTO_INCREMENT=694 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_teller_printer_setting` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `mch_id` int(11) NOT NULL DEFAULT '0',   `store_id` int(11) DEFAULT '0',   `printer_id` int(11) NOT NULL COMMENT '打印机id',   `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭 1启用',   `type` text COMMENT '打印类型',   `order_send_type` text COMMENT '发货方式',   `show_type` longtext NOT NULL COMMENT 'attr 规格 goods_no 货号 form_data 下单表单',   `big` int(11) NOT NULL DEFAULT '0' COMMENT '倍数',   `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   PRIMARY KEY (`id`),   KEY `mch_id` (`mch_id`),   KEY `is_delete` (`is_delete`),   KEY `mall_id` (`mall_id`),   KEY `status` (`status`),   KEY `store_id` (`store_id`) ) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_teller_push_order` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) DEFAULT NULL,   `mch_id` int(11) DEFAULT '0',   `user_type` varchar(65) DEFAULT NULL COMMENT '用户类型',   `order_type` varchar(65) DEFAULT NULL COMMENT '订单类型',   `teller_order_id` int(11) NOT NULL COMMENT '收银台订单ID',   `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',   `re_order_id` int(11) DEFAULT '0' COMMENT '充值订单ID',   `push_type` varchar(65) DEFAULT NULL COMMENT '提成类型',   `push_order_money` decimal(10,2) DEFAULT '0.00' COMMENT '按订单提成金额 ',   `push_percent` decimal(10,2) DEFAULT '0.00' COMMENT '按百分比提成',   `sales_id` int(11) DEFAULT '0' COMMENT '导购员ID',   `cashier_id` int(11) DEFAULT '0' COMMENT '收银员ID',   `push_money` decimal(10,2) DEFAULT '0.00' COMMENT '订单过售后最终提成金额',   `status` varchar(255) DEFAULT NULL COMMENT '订单状态',   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   `is_delete` tinyint(1) DEFAULT '0',   PRIMARY KEY (`id`),   KEY `index` (`mall_id`,`mch_id`,`user_type`,`order_type`,`order_id`,`re_order_id`,`push_type`,`sales_id`,`cashier_id`,`status`) ) ENGINE=InnoDB AUTO_INCREMENT=768 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_teller_sales` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `mch_id` int(11) NOT NULL DEFAULT '0',   `number` varchar(255) NOT NULL COMMENT '导购员编号',   `name` varchar(255) NOT NULL COMMENT '姓名',   `head_url` varchar(255) NOT NULL COMMENT '头像',   `mobile` varchar(255) NOT NULL COMMENT '电话',   `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '门店ID',   `creator_id` int(11) NOT NULL COMMENT '创建者ID',   `status` tinyint(1) DEFAULT '0' COMMENT '状态0.不启用|1.启用',   `push_money` decimal(10,2) DEFAULT '0.00' COMMENT '提成总金额',   `sale_money` decimal(10,2) DEFAULT '0.00' COMMENT '销售总金额',   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   `is_delete` tinyint(1) DEFAULT '0',   PRIMARY KEY (`id`),   KEY `index` (`mall_id`,`mch_id`,`store_id`,`creator_id`,`status`,`is_delete`) ) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_teller_work_log` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) DEFAULT NULL,   `mch_id` int(11) DEFAULT '0',   `store_id` int(11) DEFAULT '0' COMMENT '门店ID',   `start_time` timestamp NULL DEFAULT NULL COMMENT '上班时间',   `end_time` timestamp NULL DEFAULT NULL COMMENT '交班时间',   `cashier_id` int(11) DEFAULT NULL COMMENT '收银员ID',   `status` varchar(255) DEFAULT NULL COMMENT '交班状态pending 上班中|finish 交班完成',   `extra_attributes` text COMMENT '交班详细信息',   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   `is_delete` int(11) DEFAULT '0',   PRIMARY KEY (`id`),   KEY `index` (`mall_id`,`mch_id`,`store_id`,`cashier_id`,`status`,`is_delete`) USING BTREE ) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_goods_attr` ADD COLUMN `bar_code` varchar(255) NOT NULL DEFAULT '' COMMENT '条形码' AFTER `pic_url`;
EOF;
        sql_execute($sql);
    },
    '4.4.2' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_teller_cashier` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `mch_id` int(11) NOT NULL DEFAULT '0',   `user_id` int(11) NOT NULL COMMENT '用户ID',   `number` varchar(255) NOT NULL COMMENT '收银员编号',   `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '门店ID',   `creator_id` int(11) NOT NULL COMMENT '创建者ID',   `status` tinyint(1) DEFAULT '0' COMMENT '状态0.不启用|1.启用',   `push_money` decimal(10,2) DEFAULT '0.00' COMMENT '提成总金额',   `sale_money` decimal(10,2) DEFAULT '0.00' COMMENT '销售总金额',   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   `is_delete` tinyint(1) DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_teller_orders` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) DEFAULT NULL,   `mch_id` int(11) DEFAULT '0',   `order_id` int(11) DEFAULT '0' COMMENT '订单ID',   `re_order_id` int(11) DEFAULT '0' COMMENT '充值订单ID',   `cashier_id` int(11) DEFAULT NULL COMMENT '收银员ID',   `sales_id` int(11) DEFAULT '0' COMMENT '导购员ID',   `order_type` varchar(255) DEFAULT NULL COMMENT '订单类型',   `add_money` decimal(10,2) DEFAULT '0.00' COMMENT '订单加价',   `change_price_type` varchar(255) DEFAULT NULL COMMENT '改价类型 加价|减价',   `change_price` decimal(10,2) DEFAULT '0.00' COMMENT '订单改价金额',   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `is_refund` tinyint(1) DEFAULT '0' COMMENT '是否有退款0.否|1.是',   `refund_money` decimal(10,2) DEFAULT '0.00' COMMENT '退款总金额',   `order_query` int(11) DEFAULT '0' COMMENT '付款码订单查询次数',   `is_pay` int(11) DEFAULT '0' COMMENT '是否支付0.未付款|1.已付款',   `pay_type` int(11) DEFAULT '0' COMMENT '支付方式',   `work_log_id` int(11) DEFAULT '0' COMMENT '交班记录ID',   `is_statistics` tinyint(1) DEFAULT '0' COMMENT '是否统计0.否|1.是',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_teller_printer_setting` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `mch_id` int(11) NOT NULL DEFAULT '0',   `store_id` int(11) DEFAULT '0',   `printer_id` int(11) NOT NULL COMMENT '打印机id',   `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭 1启用',   `type` text COMMENT '打印类型',   `order_send_type` text COMMENT '发货方式',   `show_type` longtext NOT NULL COMMENT 'attr 规格 goods_no 货号 form_data 下单表单',   `big` int(11) NOT NULL DEFAULT '0' COMMENT '倍数',   `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_teller_push_order` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) DEFAULT NULL,   `mch_id` int(11) DEFAULT '0',   `user_type` varchar(65) DEFAULT NULL COMMENT '用户类型',   `order_type` varchar(65) DEFAULT NULL COMMENT '订单类型',   `teller_order_id` int(11) NOT NULL COMMENT '收银台订单ID',   `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',   `re_order_id` int(11) DEFAULT '0' COMMENT '充值订单ID',   `push_type` varchar(65) DEFAULT NULL COMMENT '提成类型',   `push_order_money` decimal(10,2) DEFAULT '0.00' COMMENT '按订单提成金额 ',   `push_percent` decimal(10,2) DEFAULT '0.00' COMMENT '按百分比提成',   `sales_id` int(11) DEFAULT '0' COMMENT '导购员ID',   `cashier_id` int(11) DEFAULT '0' COMMENT '收银员ID',   `push_money` decimal(10,2) DEFAULT '0.00' COMMENT '订单过售后最终提成金额',   `status` varchar(255) DEFAULT NULL COMMENT '订单状态',   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   `is_delete` tinyint(1) DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_teller_sales` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `mch_id` int(11) NOT NULL DEFAULT '0',   `number` varchar(255) NOT NULL COMMENT '导购员编号',   `name` varchar(255) NOT NULL COMMENT '姓名',   `head_url` varchar(255) NOT NULL COMMENT '头像',   `mobile` varchar(255) NOT NULL COMMENT '电话',   `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '门店ID',   `creator_id` int(11) NOT NULL COMMENT '创建者ID',   `status` tinyint(1) DEFAULT '0' COMMENT '状态0.不启用|1.启用',   `push_money` decimal(10,2) DEFAULT '0.00' COMMENT '提成总金额',   `sale_money` decimal(10,2) DEFAULT '0.00' COMMENT '销售总金额',   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   `is_delete` tinyint(1) DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_teller_work_log` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) DEFAULT NULL,   `mch_id` int(11) DEFAULT '0',   `store_id` int(11) DEFAULT '0' COMMENT '门店ID',   `start_time` timestamp NULL DEFAULT NULL COMMENT '上班时间',   `end_time` timestamp NULL DEFAULT NULL COMMENT '交班时间',   `cashier_id` int(11) DEFAULT NULL COMMENT '收银员ID',   `status` varchar(255) DEFAULT NULL COMMENT '交班状态pending 上班中|finish 交班完成',   `extra_attributes` text COMMENT '交班详细信息',   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   `is_delete` int(11) DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },
    '4.4.5' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_store` ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态开关' AFTER `extra_attributes`;
ALTER TABLE `zjhj_bd_diy_page` ADD COLUMN `access_limit` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL AFTER `platform`;
ALTER TABLE `zjhj_bd_goods` ADD COLUMN `is_setting_send_type` smallint(1) NOT NULL DEFAULT 0 COMMENT '是否单独设置发货方式0--否 1--是', ADD COLUMN `send_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '发货方式', ADD COLUMN `is_time` smallint(1) NOT NULL DEFAULT 0 COMMENT '是否单独设置销售时间', ADD COLUMN `sell_begin_time` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开售时间', ADD COLUMN `sell_end_time` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间';
CREATE TABLE `zjhj_bd_goods_remind` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `goods_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `is_remind` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否提醒',   `is_delete` smallint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_core_file` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) DEFAULT '0',   `mch_id` int(11) DEFAULT '0',   `file_name` varchar(255) DEFAULT '' COMMENT '文件名称',   `percent` decimal(11,2) DEFAULT '0.00' COMMENT '下载进度',   `status` tinyint(1) DEFAULT '0' COMMENT '是否完成',   `is_delete` tinyint(1) DEFAULT '0',   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_goods_remind` ADD COLUMN `remind_at` timestamp NOT NULL COMMENT '提醒时间';
EOF;
        sql_execute($sql);
    },
    '4.4.9' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_full_reduce_activity` ADD COLUMN `app_share_title` varchar(255) NOT NULL DEFAULT '' COMMENT '分享标题', ADD COLUMN `app_share_pic` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '分享图片';
EOF;
        sql_execute($sql);
    },
    '4.4.14' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_fission_activity` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `name` varchar(255) NOT NULL DEFAULT '' COMMENT '活动名称',   `start_time` timestamp NOT NULL COMMENT '开始时间',   `end_time` timestamp NOT NULL COMMENT '结束时间',   `style` smallint(1) NOT NULL DEFAULT '1' COMMENT '红包墙的样式',   `number` int(11) NOT NULL DEFAULT '2' COMMENT '红包数量2～100',   `app_share_title` varchar(255) NOT NULL DEFAULT '' COMMENT '自定义分享标题',   `app_share_pic` longtext COMMENT '自定义分享图片',   `rule_title` varchar(255) NOT NULL DEFAULT '' COMMENT '规则标题',   `rule_content` longtext NOT NULL COMMENT '规则内容',   `is_delete` smallint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   `expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '赠品失效天数',   `status` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否上架',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_fission_activity_log` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `activity_id` int(11) NOT NULL,   `invite_activity_log_id` int(11) NOT NULL DEFAULT '0' COMMENT '参与邀请id',   `invite_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '邀请人id',   `select_name` varchar(255) NOT NULL DEFAULT '' COMMENT '搜索使用 名称',   `rewards` longtext COMMENT '关卡奖励',   `activity` longtext COMMENT '活动',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_fission_activity_reward` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL DEFAULT '0',   `activity_id` int(11) NOT NULL DEFAULT '0',   `type` smallint(1) NOT NULL DEFAULT '0' COMMENT '类型0--红包奖励 1--关卡一奖励 2--关卡二奖励 3--关卡三奖励 4关卡四奖励 5关卡五奖励',   `status` varchar(255) NOT NULL DEFAULT 'cash' COMMENT '奖励种类 \ncash--现金 \nbalance--余额\ncoupon--优惠券\ninteger—积分\ngoods—赠品\ncard—卡券',   `people_number` int(11) NOT NULL DEFAULT '1' COMMENT '邀请人数',   `model_id` int(11) NOT NULL DEFAULT '0' COMMENT '赠品、卡券、优惠券时为奖励的id',   `exchange_type` varchar(255) NOT NULL DEFAULT 'online' COMMENT '兑奖方式 online--线上 offline--线下',   `min_number` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '现金、余额、积分奖励时，最小值',   `max_number` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '现金、余额、积分奖励时最大值',   `send_type` varchar(255) NOT NULL DEFAULT 'random' COMMENT '数量发放方式random--随机金额。average--平均',   `is_delete` smallint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   `level` varchar(255) NOT NULL DEFAULT 'main' COMMENT '奖励的等级 main--主要奖励 secondary--次要奖励',   `attr_id` int(11) NOT NULL DEFAULT '0' COMMENT '奖励为赠品时，规格id',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_fission_coupon_relation` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `activity_log_id` int(11) NOT NULL,   `reward_log_id` int(11) NOT NULL,   `user_coupon_id` int(11) NOT NULL,   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_fission_reward_log` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `reward_type` smallint(1) NOT NULL COMMENT '关卡',   `reward_id` int(11) NOT NULL COMMENT '奖励id 0第一次奖励',   `activity_log_id` int(11) NOT NULL COMMENT '活动id',   `is_exchange` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否兑换',   `reward` longtext COMMENT '关卡记录',   `expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '赠品失效天数',   `real_reward` decimal(10,2) NOT NULL COMMENT '兑换真实奖励 随机奖品使用的',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `result_id` int(11) NOT NULL DEFAULT '0' COMMENT '领取后的id',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_fission_reward_log` ADD COLUMN `token` varchar(255) NOT NULL DEFAULT '' COMMENT '订单token';
EOF;
        sql_execute($sql);
    },
    '4.4.15' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_goods_warehouse` ADD COLUMN `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '关键词' AFTER `subtitle`;
ALTER TABLE `zjhj_bd_recharge` ADD COLUMN `send_type` int(10) NOT NULL DEFAULT 7 COMMENT '赠送类型' AFTER `send_member_id`, ADD COLUMN `send_card` longtext NULL COMMENT '赠送卡券' AFTER `send_type`, ADD COLUMN `send_coupon` longtext NULL COMMENT '赠送优惠券' AFTER `send_card`, ADD COLUMN `lottery_limit` int(10) NOT NULL DEFAULT 0 COMMENT '抽奖次数' AFTER `send_coupon`;
ALTER TABLE `zjhj_bd_recharge_orders` ADD COLUMN `send_type` int(10) NOT NULL DEFAULT 7 COMMENT '赠送类型' AFTER `send_member_id`, ADD COLUMN `send_card` longtext NULL COMMENT '赠送卡券' AFTER `send_type`, ADD COLUMN `send_coupon` longtext NULL COMMENT '赠送优惠券' AFTER `send_card`, ADD COLUMN `lottery_limit` int(10) NOT NULL DEFAULT 0 COMMENT '抽奖次数' AFTER `send_coupon`;
CREATE TABLE `zjhj_bd_pond_bout`  (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `bout` int(11) UNSIGNED NOT NULL DEFAULT 0,   `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,   PRIMARY KEY (`id`) USING BTREE,   UNIQUE INDEX `user_id`(`user_id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
ALTER TABLE `zjhj_bd_pond_log` ADD COLUMN `bout_id` int(11) NOT NULL DEFAULT 0 COMMENT '赠送次数id' AFTER `token`;
CREATE TABLE `zjhj_bd_scratch_bout`  (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `user_id` int(11) NOT NULL,   `bout` int(11) UNSIGNED NOT NULL DEFAULT 0,   `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,   PRIMARY KEY (`id`) USING BTREE,   UNIQUE INDEX `user_id`(`user_id`) USING BTREE ) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
ALTER TABLE `zjhj_bd_scratch_log` ADD COLUMN `bout_id` int(11) NOT NULL DEFAULT 0 COMMENT '赠送次数id' AFTER `token`;
CREATE TABLE `zjhj_bd_license` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `domain` varchar(255) DEFAULT '' COMMENT '域名',   `icp_number` varchar(255) DEFAULT '' COMMENT 'icp备案号',   `security_address` varchar(255) DEFAULT '' COMMENT '公安备案地',   `security_number` varchar(255) DEFAULT '' COMMENT '公安备案号',   `electronic_domain` varchar(255) DEFAULT '' COMMENT '电子执照链接',   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   `is_delete` tinyint(1) DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_account_permissions_group` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `name` varchar(65) DEFAULT '' COMMENT '权限套餐名称',   `permissions` text COMMENT '权限',   `permissions_text` text,   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   `is_delete` tinyint(1) DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_account_user_group` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `name` varchar(255) DEFAULT '' COMMENT '用户组名称',   `permissions_group_id` int(11) DEFAULT NULL,   `created_at` timestamp NULL DEFAULT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   `is_delete` tinyint(1) DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_admin_info` ADD COLUMN `user_group_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户组ID';
CREATE TABLE `zjhj_bd_url_scheme` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `name` varchar(255) NOT NULL DEFAULT '',   `expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '失效时间',   `is_expire` smallint(1) NOT NULL DEFAULT '0' COMMENT '生成的scheme码类型，\n到期失效：1，\n永久有效：0。',   `link` varchar(255) NOT NULL DEFAULT '',   `url_scheme` varchar(255) NOT NULL DEFAULT '',   `is_delete` smallint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,   `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },
    '4.4.16' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_license` ADD COLUMN `icp_link` varchar(255) NOT NULL DEFAULT '' COMMENT 'icp跳转链接';
EOF;
        sql_execute($sql);
    },
    '4.4.18' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_wechat_wxmpprograms` ADD COLUMN `version` tinyint(1) NOT NULL DEFAULT 1 COMMENT '数据版本1--第一版 2--第二版';
ALTER TABLE `zjhj_bd_wechat_config` ADD COLUMN `version` tinyint(1) NOT NULL DEFAULT 1 COMMENT '数据版本1--第一版 2--第二版';
CREATE TABLE `zjhj_bd_wechat_keyword` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL DEFAULT '0',   `rule_id` int(11) NOT NULL DEFAULT '0' COMMENT '规则id',   `name` varchar(255) NOT NULL DEFAULT '' COMMENT '关键词',   `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '匹配方式0--全匹配1--模糊匹配',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_wechat_keyword_rules` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL DEFAULT '0',   `name` varchar(255) NOT NULL DEFAULT '' COMMENT '规则名称',   `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '回复方式0--全部回复1--随机一条回复',   `reply_id` varchar(255) NOT NULL DEFAULT '' COMMENT '回复内容',   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NOT NULL,   `deleted_at` timestamp NOT NULL,   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_city_service` ADD COLUMN `plugin` varchar(255) NOT NULL DEFAULT 'mall' COMMENT '区分插件还是商城';
ALTER TABLE `zjhj_bd_city_service` ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态是否开启';
CREATE TABLE `zjhj_bd_wechat_subscribe_reply` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `mall_id` int(11) NOT NULL,  `type` int(11) NOT NULL DEFAULT '0' COMMENT '消息类型 0--文字 1--图片 2--语音 3--视频 4--图文',  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '消息内容',  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '图文消息时标题',  `picurl` longtext COMMENT '图文消息时图片链接',  `url` longtext COMMENT '图文消息时跳转链接，其他消息类型时媒体链接',  `media_id` varchar(255) NOT NULL DEFAULT '' COMMENT '图片，音频，视频消息时，临时素材id',  `is_delete` tinyint(1) NOT NULL DEFAULT '0',  `created_at` timestamp NOT NULL,  `updated_at` timestamp NOT NULL,  `deleted_at` timestamp NOT NULL,  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消息使用的地方0--关注回复1--关键词回复2--菜单回复', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },
    '4.4.24' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_step_setting` ADD COLUMN `is_coupon` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否优惠券' AFTER `share_pic`, ADD COLUMN `is_integral` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否积分抵扣' AFTER `is_coupon`;
ALTER TABLE `zjhj_bd_bargain_goods` ADD COLUMN `limit_data` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '限制参与方式' AFTER `stock_type`;
ALTER TABLE `zjhj_bd_admin_info` ADD COLUMN `subjoin_permissions` text COMMENT '附加权限';
EOF;
        sql_execute($sql);
    },
    '4.4.29' => function () {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_minishop_goods` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL DEFAULT '0',   `goods_id` int(11) NOT NULL DEFAULT '0',   `apply_status` smallint(1) NOT NULL DEFAULT '0' COMMENT '审核状态0--未审核 1--审核中 2--审核通过 3--审核驳回',   `status` smallint(1) NOT NULL DEFAULT '0' COMMENT '上下架状态 0--下架 1--上架 2--上架申请中',   `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '小商店上商品id',   `third_cat` varchar(255) NOT NULL DEFAULT '' COMMENT '小商店上分类',   `brand` varchar(255) NOT NULL DEFAULT '' COMMENT '小商店上品牌',   `title` varchar(255) NOT NULL DEFAULT '' COMMENT '小商店上商品标题',   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '小商店上商品价格',   `stock` int(11) NOT NULL DEFAULT '0' COMMENT '小商店上商品库存',   `goods_info` longtext NOT NULL COMMENT '上传时商品信息',   `product_info` longtext NOT NULL COMMENT '小商店上商品信息',   `desc` longtext NOT NULL COMMENT '小商店上商品详情',   `audit_info` longtext NOT NULL COMMENT '审核结果',   `created_at` timestamp NOT NULL,   `updated_at` timestamp NULL DEFAULT NULL,   `deleted_at` timestamp NULL DEFAULT NULL,   `is_delete` tinyint(1) NOT NULL DEFAULT '0',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_minishop_order` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `payment_order_union_id` int(11) NOT NULL,   `order_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '交易组件平台订单id',   `ticket` varchar(255) NOT NULL DEFAULT '' COMMENT '拉起收银台的ticket\n',   `ticket_expire_time` timestamp NOT NULL COMMENT 'ticket有效截止时间\n',   `final_price` decimal(10,2) NOT NULL COMMENT '订单最终价格（单位：分）',   `status` int(11) NOT NULL COMMENT '订单状态10-待付款20-待发货30--待收货100--已完成200--全部商品售后之后，订单取消250--用户主动取消/待付款超时取消/商家取消1010--用户已付定金',   `data` longtext NOT NULL,   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_minishop_refund` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `mall_id` int(11) NOT NULL,   `order_id` int(11) NOT NULL,   `order_refund_id` int(11) NOT NULL,   `status` int(11) NOT NULL,   `aftersale_infos` longtext NOT NULL,   PRIMARY KEY (`id`) USING BTREE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
    },
    '4.4.31' => function() {
        $sql = <<<EOF
CREATE TABLE `zjhj_bd_app_manage` ( `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255) DEFAULT NULL COMMENT '应用标识', `display_name` varchar(255) DEFAULT NULL COMMENT '应用名称', `pic_url_type` int(11) DEFAULT '1' COMMENT '图标类型', `pic_url` varchar(255) DEFAULT '' COMMENT '应用图标', `content` varchar(255) DEFAULT '' COMMENT '应用简介', `is_show` tinyint(1) DEFAULT '1' COMMENT '是购买用户是否可见', `pay_type` varchar(255) DEFAULT 'service' COMMENT '购买方式：online 线上购买 ', `price` decimal(10,2) DEFAULT '0.00' COMMENT '应用售价', `detail` mediumtext COMMENT '应用详情', `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, `deleted_at` timestamp NULL DEFAULT NULL, `is_delete` tinyint(1) DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `zjhj_bd_app_order` ( `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) DEFAULT NULL COMMENT '账号ID', `nickname` varchar(255) DEFAULT NULL COMMENT '账号名称', `name` varchar(255) DEFAULT NULL COMMENT '应用标识', `app_name` varchar(255) DEFAULT NULL COMMENT '应用名称', `order_no` varchar(255) DEFAULT NULL COMMENT '订单号', `pay_price` decimal(10,2) DEFAULT NULL COMMENT '支付价格', `pay_type` varchar(255) DEFAULT NULL COMMENT '支付方式', `is_pay` tinyint(1) DEFAULT '0' COMMENT '是否支付', `pay_time` timestamp NULL DEFAULT NULL COMMENT '支付时间', `extra_attributes` mediumtext, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, `deleted_at` timestamp NULL DEFAULT NULL, `is_delete` tinyint(1) DEFAULT '0', `out_trade_no` varchar(255) DEFAULT NULL COMMENT '商户订单号', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `zjhj_bd_core_file` ADD COLUMN `user_id` int(11) COMMENT '用户ID';
EOF;
        sql_execute($sql);
    },
    '4.4.33' => function () {
        $sql = <<<EOF
ALTER TABLE `zjhj_bd_pintuan_order_relation` ADD COLUMN `is_refund` int(11) DEFAULT 0 COMMENT '0.默认未处理1.已退款2.待退款';
EOF;
        sql_execute($sql);
    },
	'5.0.0' => function () {
        $url = \Yii::$app->request->hostInfo;
        $sql = <<<EOF

	DROP TABLE IF EXISTS `zjhj_bd_cloud_template`;
        CREATE TABLE `zjhj_bd_cloud_template` (
         `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '云模板ID',
         `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '云模板名称',
         `pics` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '云模板图片',
         `detail` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '云模板详情',
         `price` decimal(10,2) NOT NULL COMMENT '云模板价格',
         `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '云模板类型',
         `version` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '云模板版本',
         `package` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '云模板资源包',
         PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='云模板';
        INSERT INTO `zjhj_bd_cloud_template` VALUES (1, '双十二', '[\"{$url}/plugins/diy/down/img/d66b72d485ceed26d358e8f142dec60f.png\"]', '双十二', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/6d79442a06563f9b356854994d794b9a.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (2, '服饰1', '[\"{$url}/plugins/diy/down/img/42d68cee10e3fa8af2c23ab81241e14e.png\"]', '服饰1', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/e7b0241ebaf1b6a62498d6b3368104d7.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (3, '服饰2', '[\"{$url}/plugins/diy/down/img/7e96634708ed70371f9fbcd3dcba0bb4.png\"]', '服饰2', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/ab1744dc43d2086eb9c783bef3103a81.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (4, '服饰3', '[\"{$url}/plugins/diy/down/img/2e86a1ad42ac8ea8b5f9c8e1131e2cc2.png\"]', '服饰3', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/0f42e2f848b9f4f915e89bb143a07f08.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (5, '生鲜1', '[\"{$url}/plugins/diy/down/img/3d6b77eb7d512537c8e03e86c1e052f5.png\"]', '生鲜1', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/60cada7959c796057723077f5ece92b8.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (6, '生鲜2', '[\"{$url}/plugins/diy/down/img/7b516ba8fce669badea52aa2452dc3db.png\"]', '生鲜2', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/2d55951b4a6f9edfb3119f1a6523d85d.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (7, '美妆1', '[\"{$url}/plugins/diy/down/img/9d24aec77b45951b50343f3bc9708cd9.png\"]', '美妆1', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/7926abf6c6100457633685f5bc59375a.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (8, '美妆2', '[\"{$url}/plugins/diy/down/img/dc9370e6666b5cb588d81c1b22c72151.png\"]', '美妆2', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/35f29d446dd3b6329affd943e0e9f5c4.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (9, '超市1', '[\"{$url}/plugins/diy/down/img/3bddad82d6c63870596334e318777fa5.png\"]', '超市1', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/33bd4dfe5ad7a707042a74b7bd79d843.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (10, '超市2', '[\"{$url}/plugins/diy/down/img/81ecb3a4ec8e8248da0e7f7a027bafd2.png\"]', '超市2', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/87646dd04503f59f316e23af515015e8.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (11, '超市3', '[\"{$url}/plugins/diy/down/img/63f989c9f7b9663e6ab9de13c03056b6.png\"]', '超市3', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/476d9d5bcc2b4faba2693b4f6c990603.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (12, '春节模板', '[\"{$url}/plugins/diy/down/img/2be7b8bd21b5b89c1008bcbfbd6fc876.png\"]', '春节模板', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/29434d36932147c538e575e95918ec3b.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (13, '元宵节模板', '[\"{$url}/plugins/diy/down/img/a5c397ade39f0e76c784d46078f0ea20.png\"]', '元宵节模板', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/4057bee3a03bcbe14d1c7d5fb36a5afb.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (14, '情人节模板', '[\"{$url}/plugins/diy/down/img/615112ec2dc3e3b0b8aaa9b0b93bd4f0.png\"]', '情人节模板', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/f275c5db1cbd432567b7eaab81cf1dd0.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (15, '春节模板2', '[\"{$url}/plugins/diy/down/img/2022fc32d157bbdacae989174c75d583.png\"]', '春节模板2', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/732a5469e1f2110de1b0433ba9223a14.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (16, '元宵节模板2', '[\"{$url}/plugins/diy/down/img/5f8c2f51b4a9160decb00bed2277952f.png\"]', '元宵节模板2', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/46fcb342159ea926a4a5258503717895.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (17, '情人节模板2', '[\"{$url}/plugins/diy/down/img/a0dadedd1a511bfa59fa63497a8c974d.png\"]', '情人节模板2', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/ee6f185747fc4079bc097331ffe26e94.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (18, '妇女节', '[\"{$url}/plugins/diy/down/img/be4f913235be3ca1b7a4b4d34bb45216.png\"]', '妇女节', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/aa6b10949760ec20e2febfe8598d28e1.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (19, '妇女节2', '[\"{$url}/plugins/diy/down/img/df3307934c25cfbf1ae2c1046037d8bc.png\"]', '妇女节2', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/3d31d280d6bb8e87fdf433369bc25126.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (20, '51劳动节', '[\"{$url}/plugins/diy/down/img/fdce3d73af8165e0f5652187213950e3.png\"]', '51劳动节', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/976637b958d269cd483784e9c5c1c71e.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (21, '51劳动节-2', '[\"{$url}/plugins/diy/down/img/a65509e5cee18c6dda134cde50009892.png\"]', '51劳动节-2', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/db17654eddbc57754dfb9542f57ffd6a.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (22, '618年中盛典', '[\"{$url}/plugins/diy/down/img/3dec44461189a8c99b157e426ec98aa4.png\"]', '618年中盛典', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/c651005b5944a0b61871b82459edece4.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (23, '618年中盛典-2', '[\"{$url}/plugins/diy/down/img/35120f94cf467eb8874aa67afe0b12c4.png\"]', '618年中盛典-2', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/fc0274194a2297cceee2d62bdb7ec248.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (24, '端午节2', '[\"{$url}/plugins/diy/down/img/995334b8ad426121aa8565297b51fcca.png\"]', '端午节2', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/0605dac1b23afa05e59ee253c5785ff2.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (25, '端午节1', '[\"{$url}/plugins/diy/down/img/da8a5f4aa964f4dd933b60f9643a7353.png\"]', '端午节1', 0.00, 'diy', '0.0.1', '{$url}/plugins/diy/down/zip/61173884b5b6f8bb604ad0bfe5998b22.zip'); 
        INSERT INTO `zjhj_bd_cloud_template` VALUES (26, '七夕节', '[\"{$url}/plugins/diy/down/img/7c028875992161e54de2ca8ff3368d07.png\"]', '七夕节', 0.00, 'diy', '0.01', '{$url}/plugins/diy/down/zip/d6b97eba29e23e34fc6540018a745a3f.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (27, '国庆节1', '[\"{$url}/plugins/diy/down/img/01a2c2b646e906a4030890f63e90b3e5.png\"]', '国庆节1', 0.00, 'diy', '0.01', '{$url}/plugins/diy/down/zip/46b7d293c7e09914096ab0452405057b.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (28, '国庆节2', '[\"{$url}/plugins/diy/down/img/5d7e89956a98affd664148d8bea71551.png\"]', '国庆节2', 0.00, 'diy', '0.01', '{$url}/plugins/diy/down/zip/2c707440327f70b161354716a39a1323.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (29, '中秋节1', '[\"{$url}/plugins/diy/down/img/4f03a7e64fd2d253f5c0377758a3339f.png\"]', '中秋节1', 0.00, 'diy', '0.01', '{$url}/plugins/diy/down/zip/2236ba6e328a1697f3aad986e7aa87f9.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES (30, '中秋节2', '[\"{$url}/plugins/diy/down/img/6a9368b86fda2ae8036eca737a63c889.png\"]', '中秋节2', 0.00, 'diy', '0.01', '{$url}/plugins/diy/down/zip/6cbfc8b71f419e0744b717acfdf9154e.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES ('31', '双十二1', '[\"{$url}/plugins/diy/down/img/d580c61a686ced24053ad0aec7457096.png\"]', '双十二1', '0.00', 'diy', '0.01', '{$url}/plugins/diy/down/zip/b7855271cd534e42504dc850ff5ff216.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES ('32', '双十二2', '[\"{$url}/plugins/diy/down/img/096eb7e2b1c7ace2e8d40580e230ab81.png\"]', '双十二2', '0.00', 'diy', '0.01', '{$url}/plugins/diy/down/zip/eec38ebdb62248f1476dfb2595ab2883.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES ('33', '圣诞节', '[\"{$url}/plugins/diy/down/img/9d5be7805c6895bcb76f91b309bf60c0.png\"]', '圣诞节', '0.00', 'diy', '0.01', '{$url}/plugins/diy/down/zip/467383db158a2c65edbf1163197ff78b.zip');
        INSERT INTO `zjhj_bd_cloud_template` VALUES ('34', '元旦节', '[\"{$url}/plugins/diy/down/img/4d7d33a1854ecd602ef737d9f465f048.png\"]', '元旦节', '0.00', 'diy', '0.01', '{$url}/plugins/diy/down/zip/f04d03473489c20f894655e76d2a6b76.zip');

EOF;
        sql_execute($sql);
    }
];
