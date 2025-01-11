<!-- <?php
session_start(); // Start the session to access session variables
?> -->

<div class="topnav">
    <a href="index.php">Home</a>
    <a href="add_book.php">Add Book</a>
    <a href="display_books.php">Display Books</a>
    <a href="contact.php">Contact</a>
    <a href="add_book(admin).php">Add Book Admin</a>
    <div class="dropdown" style="float:right">
        <button class="dropbtn"><?php echo htmlspecialchars($_SESSION['user']); ?> ▼</button>
        <div class="dropdown-content">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>

<style>
    .dropdown {
        float: right;
        overflow: hidden;
    }

    .dropdown .dropbtn {
        font-size: 16px;
        border: none;
        outline: none;
        color: white;
        padding: 14px 16px;
        background-color: inherit;
        font-family: inherit;
        margin: 0;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        float: none;
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
    }

    .dropdown-content a:hover {
        background-color: #ddd;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        background-color: #3e8e41;
    }
</style>