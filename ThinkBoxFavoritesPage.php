<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="mystyle.css?v=<?php echo time(); ?>">
<script src="https://kit.fontawesome.com/5afb4ac850.js" crossorigin="anonymous"></script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ThinkBox</title>
<style>
</style>
</head>
<body>
 <div id="top-nav">
        <a href="ThinkBoxMainPage.php" class="thinkbox-logo">
            <div id="thinkbox-text">ThinkBox</div>
        </a>
        <input type="text" id="searchBox" placeholder="Search books...">
        <a href="ThinkBoxFavoritesPage.php">
        <div id="FavoritesIcon" class="icon-with-label1"><p class="hover-underline-animation-icons-small"><i class="fa-solid fa-book-bookmark fa-2xl"></i></p><span>Private</span></div>
        </a>
        <a href="ThinkBoxThemePage.php">
        <div id="ThemesIcon" class="icon-with-label2"><p class="hover-underline-animation-icons-long"><i class="fa-solid fa-paintbrush fa-2xl"></i></p><span>Themes</span></div>
        </a>
        <a href="ThinkBoxAdminPage.php">
        <div id="AdminIcon" class="icon-with-label3"><p class="hover-underline-animation-icons-small"><i class="fa-solid fa-wrench fa-2xl"></i></p><span>Admin</span></div>
        </a>
        <a href="#" id="logoutLink">
        <div id="LogOutIcon" class="icon-with-label4"><p class="hover-underline-animation-icons-long"><i class="fa-solid fa-right-from-bracket fa-2xl"></i></p><span>Logout</span></div>
        </a>
</div>
    <div class="content-page">
        <div id="books-bro">
    </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="myscript.js?v=<?php echo time(); ?>"></script>
</body>
</html>