<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="mystyle.css?v=<?php echo time(); ?>">
<script src="https://kit.fontawesome.com/5afb4ac850.js" crossorigin="anonymous"></script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ThinkBox</title>
<style>
body{
    visibility:hidden;
    overflow-y:hidden;
}
</style>
</head>
<body>
<div id="TitleLarge">ThinkBox</div> <!-- top level div cuzza structure-->
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
        <div id="UploadButtonDiv"><button id="UploadButton"><i class="fa-solid fa-circle-plus fa-2xl"></i></button></div>
            <div id="UploadDiv">
                    <form id="uploadForm" enctype="multipart/form-data">
                    <label>Title:</label>
                    <input type="text" name="title" required><br>
                    <label id="descriptionLabel" >Description:</label>
                    <textarea name="description" required></textarea><br>
                    <label>EPUB File:</label>
                    <input type="file"  name="epub" accept="application/epub+zip" required><br>
                    <label>Image File:</label>
                    <input type="file" name="image" accept="image/*" required><br>
                    <label for="visibility"> Private upload</label>
                    <input type="checkbox" id="visibility" name="visibility" value="private"><br>
                    <button id="SubmitButtonUpload" type="submit">Upload</button>
                </form>
            <div id="response"></div>
            </div>
    <div class="content-page">
        <div id="books-bro">
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="myscript.js?v=<?php echo time(); ?>"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const params = new URLSearchParams(window.location.search);
        const fromLogin = params.get("from");

        if (fromLogin === "login") {
            onLoadMainPage();
        } else {
            // If not from login, make the body visible without triggering animations
            document.body.style.visibility = "visible";
            TitleLarge.style.display = "none";
        }
    });
</script>

<script>


  setTimeout(() => {

  const searchBox      = document.getElementById('searchBox');
  const container      = document.getElementById('books-bro');
  // Cache a static NodeList (it keeps references to the live nodes)
  const cards          = Array.from(container.querySelectorAll('.book-file'));
  console.log(cards);

  // Remember the original order so we can restore it when the box is cleared
  cards.forEach((card, idx) => card.dataset.originalIndex = idx);

  searchBox.addEventListener('input', function () {
    const query = this.value.trim().toLowerCase();

      if (!query) {
    const originalOrder = cards.slice().sort((a, b) => a.dataset.originalIndex - b.dataset.originalIndex);
    animateReorder(originalOrder, container);
    return;
  }

    // Partition into matches / non-matches
    const matched   = [];
    const unmatched = [];

    cards.forEach(card => {
      const title = card.dataset.title.toLowerCase();
      (title.includes(query) ? matched : unmatched).push(card);
    });

function animateReorder(newOrder, container) {
  const firstRects = new Map();

  // 1. Get first positions
  newOrder.forEach(card => {
    firstRects.set(card, card.getBoundingClientRect());
  });

  // 2. Reorder in DOM
  newOrder.forEach(card => container.appendChild(card));

  // 3. Calculate deltas and set inverse transform immediately (no transition yet)
  newOrder.forEach(card => {
    const lastRect = card.getBoundingClientRect();
    const firstRect = firstRects.get(card);

    const deltaX = firstRect.left - lastRect.left;
    const deltaY = firstRect.top - lastRect.top;

    if (deltaX !== 0 || deltaY !== 0) {
      card.style.transition = 'none';            // Turn off transition for immediate transform
      card.style.transform = `translate(${deltaX}px, ${deltaY}px)`;
    } else {
      card.style.transform = '';
    }
  });

  // 4. Force browser to apply the transform immediately
  void container.offsetWidth;

  // 5. Remove the transform, triggering CSS transition to animate movement
  newOrder.forEach(card => {
    card.style.transition = 'transform 300ms ease'; // Enable transition
    card.style.transform = '';                       // Animate to natural position
  });
}

    // Re-attach nodes: all matches first, then the rest (keeps stable order)
    animateReorder([...matched, ...unmatched], container);
  });
}, 500);


</script>
</body>
</html>