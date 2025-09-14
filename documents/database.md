# Database of shared files

Chitch uses its own custom database, using plain files. The challenge with a website database is that multiple clients can write and read to the file all at once. However the same file is shared with all clients, thus there needs to be an interface that allows for parallel access. Thus we can call them **shared files**. Then each page can have its data stored in its own shared file. Together these shared files make up the database.

## Immutable Files

The database has the information stored in only 1 file on the server. Still multiple clients may want to write and read to that single file at the same time. To solve this chitch uses an immutable file, by making the file append-only.

When a client wants to write something to the database, it appends the data to the end of the file. This operation locks other clients from writing, until the write is complete. This ensures that no data is lost or corrupted due to simultaneous writes.

There is one exception, and that is if the data, to be written, is under 4096 KB total. This is the size of a standard block on the filesystem. If the data can fit inside one of those block, then it can be written atomically. The write appears instant, thus we can skip the locking step.

Reading the file however is never blocked. This makes sense for a website. Visitors can always keep seeing the web page with current data. You would only have to wait a little bit if posting data. Commit markers are used (<!-- commit -->) to indicate the end of a complete write operation. This allows readers to know when the end of valid data is. This allows multiple clients to read simultaneously without blocking when new data is being written to the file.

The different types of content produced from forms on different pages get their own file. This keeps the data separated and allows for custom handling of different types of data.

## Local Database

The database directory is placed outside the code, since it is user generated data. This has the benefit of updates to the code, without having to edit the database. Thus upgrades can be performed to the site, with the user data remaining intact. In the filesystem it can look like:

- chitch/
- database/

To upgrade you could simply swap out the chitch directory with the newer directory.

## Inspecting the Database

Since the database files are just standard text, you can open them in about any program or editor. This helps with debugging the data structure, or even do a quick refactor. On a live database it is not recommend though as the file may have changed in the time it took to make the edit. Thus potentially losing data.

## Temporary Data
Beside permanant data, Chitch also relies on temporary storage. This is for things such as session data for user logins. The default appointed directory for temporary files, set in PHP, is used. On shared hosting you can get your own personal temporary directory which does not overlap with other users.  