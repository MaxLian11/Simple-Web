# Simple-Web

Simple-Web is a simple blog written in HTMl, CSS, PHP, and SQL that allows users to leave posts and comments. 

Database consists of 4 tables:
- users: Records of registered users. Includes username, email, first and last names, and a password.
- blog: Records of blog posts published by users with such data as title, description, tags and date.
- comment: Records of comments left by other users with a negative or positive reaction. Also includes such attributes as description, date, user id and blog id.
- follows: Contains two attributes, user id and follower id. This table keeps track of which users are followed by which other users.

The functionalities of this project include:
- Register Page to sign up a new user. Prevents creating users with already existed username, email or non macthing password.
- Log in Page that logs in users who were successfully registered.
- Home Page displays all blog posts with corresponding comments with details about both. Users can follow each other and filter the list of blog posts to only display their subscriptions or to display all blogs published by a specific user.
- Users can create new posts and leave comments. There are triggers in the Database that create restriptions about a number of published blog posts and comments per day and per blog post.
- Users Page shows the list of users with a number of blog posts published. It also has different filters that can be applied to the users. This page is a good practice and is a nice representation of the work of PHP and SQL.
