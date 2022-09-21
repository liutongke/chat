CREATE TABLE `apply_friend`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `uid`        int(11) NOT NULL,
    `friend_uid` int(11) NOT NULL COMMENT '好友id',
    `apply_time` datetime NOT NULL COMMENT '申请时间',
    `agree_time` datetime DEFAULT NULL COMMENT '处理时间',
    `agree`      tinyint(4) DEFAULT '0' COMMENT '0-等待确认 1-同意 2-拒绝 4-拉黑',
    `content`    char(50) NOT NULL COMMENT '申请备注',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='申请成为朋友';

CREATE TABLE `blacklist`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `uid`        int(11) NOT NULL,
    `friend_uid` int(11) NOT NULL COMMENT '黑名单id',
    `add_time`   datetime NOT NULL COMMENT '添加时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='黑名单';

CREATE TABLE `friend`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `uid`        int(11) NOT NULL,
    `friend_uid` int(11) NOT NULL COMMENT '好友id',
    `remark`     char(20) DEFAULT NULL COMMENT '备注',
    `agree_time` datetime NOT NULL COMMENT '同意时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COMMENT='好友列表';

CREATE TABLE `user_info`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `nick`        char(10) CHARACTER SET latin1  DEFAULT NULL,
    `password`    char(32) CHARACTER SET latin1 NOT NULL,
    `register_tm` datetime                      NOT NULL COMMENT '注册时间',
    `login_tm`    datetime                       DEFAULT NULL COMMENT '最后一次登录时间',
    `head`        char(100) CHARACTER SET latin1 DEFAULT NULL COMMENT '头像',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110011 DEFAULT CHARSET=utf8mb4;

