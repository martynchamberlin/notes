NotesApp
========

Perhaps more than any other application to date, I've used and polished the NotesApp project quite thoroughly. I use it every day to manage my schoolwork as well as client and personal projects. It doesn't have the robust synchronization of Evernote or Dropbox, so I recommend backing up your database tables to an external server on a weekly basis.

## Installation 

After cloning this repository, you still need to do two things in order to fully configure your instance of this application:

1. Retrieve the [config class](https://github.com/martynchamberlin/ConfigClass) and plug in your database credentials.
2. Import the SQL file located inside this repository's `SQL` directory. 	- Make sure that the database name in your configuration file matches the database name that you import this SQL file into.  

## Searching

The searching functionality with NotesApp has a few quirks.

1. To pull up all articles in a category, type `in:category-name`.
2. To perform the search accross both archived and regular articles, append an `-a` parameter at the end of the string. There must be a space between this and your search query. 