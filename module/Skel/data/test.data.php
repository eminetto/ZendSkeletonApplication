<?php
//queries used by tests
return array(
    'post' => array(
        'create' => 'CREATE TABLE "post" (
                      "id" INTEGER PRIMARY KEY NOT NULL,
                      "title" varchar(100) NOT NULL,
                      "body" text NOT NULL,
                      "created" timestamp NOT NULL
                    )',
        'drop' => "DROP TABLE post"
    ),
    'comment' => array(
        'create' => 'CREATE TABLE "comment" (
                      "id" integer primary key NOT NULL,
                      "post_id" smallint(6) NOT NULL,
                      "body" text NOT NULL,
                      "email" varchar(100) NOT NULL,
                      "created" timestamp NOT NULL,
                      CONSTRAINT "fk_comment_post" FOREIGN KEY ("post_id") REFERENCES "post" ("id")
                    )',
        'drop' =>'drop table comment'
    ),
);