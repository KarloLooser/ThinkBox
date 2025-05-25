<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="mystyle.css?v=<?php echo time(); ?>">
<script src="https://kit.fontawesome.com/5afb4ac850.js" crossorigin="anonymous"></script>
  <meta charset="UTF-8" />
  <title>EPUB Reader</title>
  <style>
    body {
      margin: 0;
      overflow:hidden;
    }
  </style>
</head>
<body>

  <div id="controls">
    <a href="ThinkBoxMainPage.php" id="backHome"><i class="fa-sharp fa-solid fa-chevron-left fa-2xl" style="color: var(--text-main);"></i></a>
  </div>

  <div id="left-overlay"></div>
  <div id="viewer"></div>
  <div id="right-overlay"></div>

  <div id="bottom-controls">
    <button id="prev"><span><i class="fa-sharp fa-solid fa-chevron-left fa-2xl" style="color: var(--text-main);"></i></span></button>
    <div id="pageNumber">0 / 0</div>
    <button id="next"><span><i class="fa-sharp fa-solid fa-chevron-right fa-2xl" style="color: var(--text-main);"></i></span></button>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/epubjs/dist/epub.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="myscript.js?v=<?php echo time(); ?>"></script>

  <script>
    const params = new URLSearchParams(window.location.search);
    const bookId = params.get("id");
    const storageKey = `last-location-book-${bookId}`;
    let book, rendition;

    async function loadBook(id) {
      const res = await fetch(`get_book_for_reading.php?id=${id}`);
      if (!res.ok) {
        alert("Failed to load book");
        return;
      }

      const blob = await res.blob();
      console.log("Blob size:", blob.size); // should be > 0

      var byteArray = new Uint8Array(['byteArray']);
      const objectUrl = await blob.arrayBuffer();
      var book = ePub(objectUrl);

      rendition = book.renderTo("viewer", {
        width: "100%",
        height: "100%",
        spread: "none"
      });

      rendition.themes.register("custom", {
      body: {
        "color": "var(--text-main)",
        "background": "transparent"
      }
      });

      rendition.hooks.content.register(function(contents) {
      const style = document.createElement("style");

      // Get the computed value of the --text-main variable after theme is applied
      const textColor = getComputedStyle(document.body).getPropertyValue('--text-main').trim();

      style.innerHTML = `
        :root {
          --text-main: ${textColor};
        }
        body {
          color: var(--text-main) !important;
          background: transparent !important;
        }
      `;

      contents.document.head.appendChild(style);
    });

      rendition.themes.select("custom");

      const savedLocation = localStorage.getItem(storageKey);

      if (savedLocation) {
        rendition.display(savedLocation);
      } else {
        rendition.display();
      }

      // Save location on change
      rendition.on("relocated", (location) => {
        localStorage.setItem(storageKey, location.start.cfi);
        updatePageNumber(location);
      });

      // Update total pages when layout is ready
      rendition.on("layout", () => {
        updatePageNumber(rendition.location);
      });
    }

    document.getElementById("left-overlay").addEventListener("click", (event) => {
      event.stopPropagation(); // Prevent the event from propagating to the viewer
      if (rendition) {
        rendition.prev(); // Navigate to the previous page
      }
    });
    
    document.getElementById("right-overlay").addEventListener("click", (event) => {
      event.stopPropagation(); // Prevent the event from propagating to the viewer
      if (rendition) {
        rendition.next(); // Navigate to the next page
      }
    });

    function updatePageNumber(location) {
      if (!location) return;
      const currentPage = location.start.displayed.page;
      const totalPages = location.start.displayed.total;
      document.getElementById("pageNumber").textContent = `${currentPage} / ${totalPages}`;
    }

    document.getElementById("next").addEventListener("click", () => {
      if (rendition) rendition.next();
    });

    document.getElementById("prev").addEventListener("click", () => {
      if (rendition) rendition.prev();
    });

    if (bookId) {
      loadBook(bookId);
    } else {
      alert("No book ID provided.");
    }

    // Go to specific page
    document.getElementById("goToPageBtn").addEventListener("click", () => {
      const input = document.getElementById("goToPage");
      const page = parseInt(input.value, 10);

      if (!rendition || isNaN(page)) return;

      const currentLocation = rendition.location;
      const displayed = currentLocation.start.displayed;

      if (page >= 1 && page <= displayed.total) {
        const targetIndex = displayed.start + (page - 1);
        rendition.display(targetIndex);
      } else {
        alert("Invalid page number");
      }
    });


  </script>

</body>
</html>
