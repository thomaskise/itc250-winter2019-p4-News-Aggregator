#News feeds SQL file
#feeds-20190227.sql
# creates 2 tables, Categories and Topics 

drop table if exists wn19_FeedCategories;
create table wn19_FeedCategories(
CategoryID int unsigned not null auto_increment primary key,
Category varchar(120)
);
insert into wn19_FeedCategories values(NULL, 'Food');
insert into wn19_FeedCategories values(NULL, 'Art');
insert into wn19_FeedCategories values(NULL, 'Travel');


drop table if exists wn19_Topics;
create table wn19_Topics(
TopicID int unsigned not null auto_increment primary key,
TopicName varchar(120),
TopicURL text,
CategoryID int DEFAULT 0
); 

insert into wn19_Topics values(NULL, 'Vegan', 'https://news.google.com/rss/search?q=vegan+foods&hl=en-US&gl=US&ceid=US:en', 1);
insert into wn19_Topics values(NULL, 'Vegetarian', 'https://news.google.com/rss/search?q=vegetarian+foods&hl=en-US&gl=US&ceid=US:en', 1);
insert into wn19_Topics values(NULL, 'Thai', 'https://news.google.com/rss/search?q=thai+food&hl=en-US&gl=US&ceid=US:en', 1);
insert into wn19_Topics values(NULL, 'Modern Art', 'https://news.google.com/rss/search?q=modern+art&hl=en-US&gl=US&ceid=US:en', 2);
insert into wn19_Topics values(NULL, 'Impressionism', 'https://news.google.com/rss/search?q=impressionism&hl=en-US&gl=US&ceid=US:en', 2);
insert into wn19_Topics values(NULL, 'Ancient Greece', 'https://news.google.com/rss/search?q=ancient+greek+art&hl=en-US&gl=US&ceid=US:en', 2);
insert into wn19_Topics values(NULL, 'Grand Canyon', 'https://news.google.com/rss/search?q=travel+grand+canyon&hl=en-US&gl=US&ceid=US:en', 3);
insert into wn19_Topics values(NULL, 'Disneyland', 'https://news.google.com/rss/search?q=travel+disneyland&hl=en-US&gl=US&ceid=US:en', 3);
insert into wn19_Topics values(NULL, 'London', 'https://news.google.com/rss/search?q=travel+london&hl=en-US&gl=US&ceid=US:en', 3);

