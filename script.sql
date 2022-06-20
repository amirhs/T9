create table contacts
(
    id          int unsigned auto_increment
        primary key,
    name        varchar(255) not null,
    family      varchar(255) not null,
    phoneNumber varchar(255) not null,
    t9Number    varchar(255) not null,
    constraint contacts_phonenumber_unique
        unique (phoneNumber)
)
    collate = utf8_unicode_ci;

create index t9Number__index
    on contacts (t9Number);


