const Login_Button = document.querySelector('.Login');
const Register_Button = document.querySelector(".Register");
const Login_Button_OK = document.querySelector("#LoginButtonOK");
const Register_Button_OK = document.querySelector("#RegisterButtonOK");
const LoginWindowDiv = document.querySelector("#LoginWindowDiv");
const RegisterWindowDiv = document.querySelector("#RegisterWindowDiv");
const TitleLarge = document.querySelector("#TitleLarge");
const body = document.querySelector("body");
const TopNav = document.querySelector("#top-nav");
const ContentPage = document.querySelector(".content-page");
const ThinkBoxText = document.querySelector("#thinkbox-text");
const Upload_Div = document.querySelector("#UploadDiv");
const prevButton = document.querySelector('#prev');
const nextButton = document.querySelector('#next');


if (document.URL.includes("ThinkBoxLoginPage.php")) {
  Login_Button.addEventListener("click",  function(){
    OpenLogin();
});

Register_Button.addEventListener("click", function(){
    OpenRegister();
});

Register_Button_OK.addEventListener("submit", function(event){
  PlaceholderNameLogin(event);
});
}

//loginForm.addEventListener("submit", function(event){
  //AuthenticateLogin(event);
//});


//TODO: POPRAVI BOJE BORDERA DA RADI SA THEMOVIMA I POPRAVI DA SE OTVARA UPLOAD BUTTON

function OpenLogin() {
    //button nije clickable dok se animacija ne izvrsi da se sprijeci spammanje
    Login_Button.style.pointerEvents = "none";
    //Hidden je po default da se ne spawnaju stvari iz diva na ekran od starta pa se tu odhideaju
    LoginWindowDiv.style.contentVisibility = "auto";
    let widthpos = 0;
    let heightpos = 0;
    let id = null;
    //ako je prozor otvoren zatvori ga, ako je zatvoren otvori ga
    if (LoginWindowDiv.style.width=="800px"){
    widthpos = 800;
    heightpos = 400;
    clearInterval(id);
    id = setInterval(frame, 1);
    function frame() {
      if (widthpos == 0 && heightpos == 0) {
          clearInterval(id);
          LoginWindowDiv.style.border = "0px solid var(--text-main)";
          Login_Button.style.pointerEvents = "auto";
      } else {
        console.log(widthpos);
        console.log(heightpos);
        widthpos-=10; 
        heightpos-=5;
        LoginWindowDiv.style.width = widthpos + "px";
        LoginWindowDiv.style.height = heightpos + "px";
    }
}
    }
    else{
    LoginWindowDiv.style.border = "6px solid var(--text-main)";
    clearInterval(id);
    id = setInterval(frame, 1.5);
    function frame() {
    if (widthpos == 800 && heightpos == 400) {
        clearInterval(id);
        Login_Button.style.pointerEvents = "auto";
      } else {
        widthpos+=10; 
        heightpos+=5;
        LoginWindowDiv.style.width = widthpos + "px";
        LoginWindowDiv.style.height = heightpos + "px";
    } 
}
    }

}

function OpenRegister() {
  Register_Button.style.pointerEvents = "none";
  RegisterWindowDiv.style.contentVisibility = "auto";
  let widthpos = 0;
  let heightpos = 0;
  let id = null;
  if (RegisterWindowDiv.style.width=="800px"){
  widthpos = 800;
  heightpos = 400;
  clearInterval(id);
  id = setInterval(frame, 1);
  function frame() {
    if (widthpos == 0 && heightpos == 0) {
        clearInterval(id);
        RegisterWindowDiv.style.border = "0px solid var(--text-main)";
        Register_Button.style.pointerEvents = "auto";
    } else {
      console.log(widthpos);
      console.log(heightpos);
      widthpos-=10; 
      heightpos-=5;
      RegisterWindowDiv.style.width = widthpos + "px";
      RegisterWindowDiv.style.height = heightpos + "px";
  }
}
  }
  else{
    RegisterWindowDiv.style.border = "6px solid var(--text-main)";
  clearInterval(id);
  id = setInterval(frame, 1.5);
  function frame() {
  if (widthpos == 800 && heightpos == 400) {
      clearInterval(id);
      Register_Button.style.pointerEvents = "auto";
    } else {
      widthpos+=10; 
      heightpos+=5;
      RegisterWindowDiv.style.width = widthpos + "px";
      RegisterWindowDiv.style.height = heightpos + "px";
  } 
}
  }

}

function SuccesfulLoginAnimation(){
  window.scrollTo(0, 0);
  LoginWindowDiv.style.animation = "fadeOut 5s forwards";
  RegisterWindowDiv.style.animation = "fadeOut 5s forwards";
  Login_Button.style.animation = "fadeOutFromTop 5s forwards";
  Register_Button.style.animation = "fadeOut 5s forwards";
  TitleLarge.style.animation = "moveTitleToMiddle 1.5s forwards";
  body.style.overflow = "hidden";
  setTimeout(() => {
    document.location.href = "ThinkBoxMainPage.php?from=login";
  }, 2000);
}

function AuthenticateLogin(event){
  event.preventDefault();

  $.ajax({
      type: "POST",
      url: "authenticate.php",
      data: $(this).serialize(),
      dataType: "json",
      success: function(response) {
          if (response.success) {
            SuccesfulLoginAnimation(event);
          } else {
              message.html("<span style='color: red;'>" + response.message + "</span>");
          }
      }
  });
};

//---------------------ThinkBoxMainPage------------------------------------

function onLoadMainPage(){
  body.style.overflow = "hidden";
  body.style.visibility = "visible";
  ThinkBoxText.style.visibility = "hidden";
  TitleLarge.style.position = "absolute";
  TitleLarge.style.marginTop = "13%";
  TitleLarge.style.marginLeft= "16.88%";
  TitleLarge.style.fontSize= "280px";
  TopNav.style.animation = "fadeIn 4s forwards";
  ContentPage.style.animation = "fadeIn 4s forwards";
  setTimeout(() => {
    TitleLarge.style.animation = "moveTitleFromMiddleToLogo 1.75s forwards";
  }, 500);
  setTimeout(() => {
    TitleLarge.style.display = "none";
    ThinkBoxText.style.visibility = "visible";
    //body.style.overflow = "auto";
    ContentPage.style.animation = "";
  }, 2750);
}

//Upload button da radi
if (document.URL.includes("ThinkBoxMainPage.php")) {
  const Upload_Button = document.querySelector("#UploadButton");
  const Upload_Div = document.querySelector("#UploadDiv");

  if (Upload_Button && Upload_Div) {
    Upload_Button.addEventListener("click", () => {
      Upload_Div.classList.toggle("active");
    });
  }
}

// Upload logika
$(document).on("submit", "#uploadForm", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  $.ajax({
    url: "secureupload.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      const res = JSON.parse(response);
      $("#response")
        .html(res.message)
        .css("color", res.status === "success" ? "green" : "red");
    },
    error: function () {
      $("#response").html("An error occurred.").css("color", "red");
    }
  });
});


//Logout
$(document).on('click', '#logoutLink', function (e) {
  e.preventDefault(); 
  window.location.href = 'logout.php'; 
});

function highlightActiveNavIcon() {
  const path = location.pathname;

  document.querySelectorAll('#top-nav p.active').forEach(el => el.classList.remove('active'));

  if (path.includes("ThinkBoxFavoritesPage.php")) {
    document.querySelector("#FavoritesIcon p")?.classList.add("active");
  } else if (path.includes("ThinkBoxThemePage.php")) {
    document.querySelector("#ThemesIcon p")?.classList.add("active");
  } else if (path.includes("ThinkBoxAdminPage.php")) {
    document.querySelector("#AdminIcon p")?.classList.add("active");
  }
}


/*function fetchAllBooks(){
fetch('get_books.php')
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById('books-bro');
        data.forEach(book => {
            const div = document.createElement('div');
            div.className = 'book-file';
            div.innerHTML = `
                <h2>${book.title}</h2>
                <p>${book.description}</p>
                <img src="data:image/jpeg;base64,${book.image_data}" />
                <a href="readingZone.php?id=${book.id}">Read</a>
                <p>Uploaded on: ${book.uploaded_at}</p>
            `;
            container.appendChild(div);
        });
    });
  }*/

let hasFetchedBooks  = false;   // prevent double loading of books
let hasFetchedAdmin  = false;   // prevent double loading of admin data 

function resetBookFetch()  { hasFetchedBooks = false; }
function resetAdminFetch() { hasFetchedAdmin = false; }


//---------------------KNJIGE------------------------------------

function initializeSearchBar() {
  setTimeout(() => {
  const searchBox = document.getElementById('searchBox');
  const container = document.getElementById('books-bro');

  if(!searchBox || !container) return;

  const cards = Array.from(container.querySelectorAll('.book-file'));
  cards.forEach((card, idx) => card.dataset.originalIndex = idx);

  searchBox.addEventListener('input', function () {
    const query = this.value.trim().toLowerCase();

    if (!query) {
      const originalOrder = cards.slice().sort((a, b) => a.dataset.originalIndex - b.dataset.originalIndex);
      animateReorder(originalOrder, container);
      return;
    }

    const matched = [], unmatched = [];

    cards.forEach(card => {
      const title = card.dataset.title.toLowerCase();
      (title.includes(query) ? matched : unmatched).push(card);
    });

    animateReorder([...matched, ...unmatched], container);
  });

  function animateReorder(newOrder, container) {
    const firstRects = new Map();

    newOrder.forEach(card => firstRects.set(card, card.getBoundingClientRect()));
    newOrder.forEach(card => container.appendChild(card));
    newOrder.forEach(card => {
      const lastRect = card.getBoundingClientRect();
      const firstRect = firstRects.get(card);
      const deltaX = firstRect.left - lastRect.left;
      const deltaY = firstRect.top - lastRect.top;

      if (deltaX !== 0 || deltaY !== 0) {
        card.style.transition = 'none';
        card.style.transform = `translate(${deltaX}px, ${deltaY}px)`;
      } else {
        card.style.transform = '';
      }
    });

    void container.offsetWidth;

    newOrder.forEach(card => {
      card.style.transition = 'transform 300ms ease';
      card.style.transform = '';
    });
  }
}, 500);
}

function fetchAllBooks(privateOnly = false) {
  if (hasFetchedBooks) return;
  hasFetchedBooks = true;

  const container = document.getElementById('books-bro');
  if (!container) {
    console.warn("Container 'books-bro' not found.");
    return;
  }

  container.style.opacity = 0;
  const fetchUrl = privateOnly ? 'get_books.php?private=1' : 'get_books.php';

  fetch(fetchUrl)
    .then(res => res.json())
    .then(data => {
      container.innerHTML = '';

      if (!data.length) {
        container.innerHTML = '<p>No books found.</p>';
        return;
      }

      data.forEach(book => {
        const div = document.createElement('div');
        div.className = 'book-file';
        div.setAttribute('data-title', book.title);
        div.setAttribute('data-id', book.id);
        div.innerHTML = `
          <h2>${book.title}</h2>
          <p>${book.description}</p>
          <img src="data:image/jpeg;base64,${book.image_data}" />
          <p>Uploaded on: ${book.uploaded_at}</p>
        `;

        div.addEventListener('click', function (e) {
        // Don't trigger if the clicked element is a link, title, or description
        const tag = e.target.tagName.toLowerCase();
        if (tag === 'a' || tag === 'h2' || (tag === 'p' && !e.target.classList.contains('upload-date'))) return;
        window.location.href = `readingZone.php?id=${book.id}`;
      });
      
        container.appendChild(div);
      });

      requestAnimationFrame(() => {
        container.style.transition = 'opacity .5s ease';
        container.style.opacity = 1;
      });
    })
    .catch(err => {
      container.innerHTML = '<p style="color:red;">Failed to load books.</p>';
      console.error('Error loading books:', err);
    });
}

//---------------------ADMIN------------------------------------

function fetchAdminData() {
  if (hasFetchedAdmin) return;
  hasFetchedAdmin = true;

  const wrap = document.getElementById('adminContainer');
  if (!wrap) return;                      // user isn’t on admin page

  wrap.innerHTML = '<p style="opacity:.6">Loading…</p>';

  fetch('get_admin_data.php')
    .then(res => {
      if (res.status === 403) throw new Error('access_denied');
      return res.json();
    })
    .then(data => {
      wrap.innerHTML = '';

      if (!data.length) {
        wrap.innerHTML = '<p>No users found.</p>';
        return;
      }

      data.forEach(u => {
        const userDiv = document.createElement('div');
        userDiv.className = 'admin-user';
        userDiv.innerHTML = `
          <h3>
            User #${u.id}: ${u.username}
            <button class="delete-user" data-uid="${u.id}" title="Delete user">🗑️ Delete user</button>
          </h3>
          <ul id="books-of-${u.id}"></ul>
        `;
        wrap.appendChild(userDiv);

        const list = userDiv.querySelector('ul');
        if (!u.books.length) {
          list.innerHTML = '<li><em>No books</em></li>';
        } else {
          u.books.forEach(b => {
            const li = document.createElement('li');
            li.innerHTML = `
              <strong>${b.title}</strong>
              <small> (${b.uploaded_at})</small>
              <button class="delete-book" data-bid="${b.id}" title="Delete book">🗑️</button>
            `;
            list.appendChild(li);
          });
        }
      });
    })
    .catch(err => {
      if (err.message === 'access_denied') {
        wrap.innerHTML = '<p style="color:red;">Access denied.</p>';
      } else {
        console.error(err);
        wrap.innerHTML = '<p style="color:red;">Failed to load admin data.</p>';
      }
    });
}

/* delegated delete handlers */
document.addEventListener('click', e => {
  if (e.target.matches('.delete-book')) {
    const id = e.target.dataset.bid;
    if (!confirm('Delete this book?')) return;

    fetch('delete_book.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id })
    })
      .then(r => r.json())
      .then(() => {
        resetAdminFetch();
        fetchAdminData();
      });
  }

  if (e.target.matches('.delete-user')) {
    const id = e.target.dataset.uid;
    if (!confirm('Delete user and ALL their books?')) return;

    fetch('delete_user.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id })
    })
      .then(r => r.json())
      .then(() => {
        resetAdminFetch();
        fetchAdminData();
      });
  }
});

//---------------------SPA NAVIGACIJA------------------------------------

$(document).ready(function () {
  initPageScripts();   // run once on initial load

  /* top-nav clicks */
  $('#top-nav a').on('click', function (e) {
    const url = $(this).attr('href');
    if (!url || url === '#') return;

    e.preventDefault();

    $('.content-page').fadeOut(100, () => {
      $('.content-page').load(url + ' .content-page > *', () => {
        history.pushState(null, '', url);
        $('.content-page').fadeIn(100);

        resetBookFetch();
        resetAdminFetch();
        initPageScripts();
      });
    });
  });

  /* browser back/forward */
  window.onpopstate = () => {
    const url = location.pathname;

    $('.content-page').fadeOut(100, () => {
      $('.content-page').load(url + ' .content-page > *', () => {
        $('.content-page').fadeIn(100);

        resetBookFetch();
        resetAdminFetch();
        initPageScripts();
      });
    });
  };
});

//---------------------REINIT SKRIPTA------------------------------------

function initPageScripts() {
  /* ------ Upload animation ------ */
  const UploadUI = document.querySelector("#UploadButtonDiv");
   // Da se upload gumb vidi samo na main pageu
  if (location.pathname.includes("ThinkBoxMainPage.php")) {
    if (UploadUI) UploadUI.classList.add("visible");
  }
  else{
    if (UploadUI) UploadUI.classList.remove("visible");
    if(Upload_Div && Upload_Div.classList.contains("active")){
    Upload_Div.classList.remove("active");
    }
  }

  /*if (document.URL.includes("ThinkBoxMainPage.php") && Upload_Button && Upload_Div) {
    Upload_Button.addEventListener("click", openUpload);

    
    function openUpload() {
      Upload_Button.style.pointerEvents = "none";
      Upload_Div.style.contentVisibility = "auto";

      let width  = parseInt(Upload_Div.style.width)  || 0;
      let height = parseInt(Upload_Div.style.height) || 0;
      let id;

      if (Upload_Div.style.width === "400px") {   // === window open → close it
        id = setInterval(frameClose, 1);
      } else {                                    // === window closed → open it
        Upload_Div.style.border = "4px solid var(--text-main)";
        id = setInterval(frameOpen, 1.5);
      }

      function frameClose() {
        if (width <= 0 && height <= 0) {
          clearInterval(id);
          Upload_Div.style.border = "0px solid var(--text-main)";
          Upload_Button.style.pointerEvents = "auto";
        } else {
          width  -= 5;
          height -= 5;
          Upload_Div.style.width  = width  + "px";
          Upload_Div.style.height = height + "px";
        }
      }

      function frameOpen() {
        if (width >= 400 && height >= 400) {
          clearInterval(id);
          Upload_Button.style.pointerEvents = "auto";
        } else {
          width  += 5;
          height += 5;
          Upload_Div.style.width  = width  + "px";
          Upload_Div.style.height = height + "px";
        }
      }
    }
  }/*



  /* ------ Page-specific fetches ------ */
  if (document.URL.includes("ThinkBoxFavoritesPage.php")) {
    fetchAllBooks(true);            // private only
  } else if (document.URL.includes("ThinkBoxAdminPage.php")) {
    fetchAdminData();               // admin panel
  } else {
    fetchAllBooks();                // public uploads
  }
  initializeSearchBar();
  highlightActiveNavIcon();
}


//---------------------ThinkBoxThemePage------------------------------------
  
const availableThemes = ['theme1', 'theme2', 'theme3', 'theme4'];

function selectTheme(themeId) {
  document.body.classList.remove(...availableThemes.map(t => 'theme-' + t));
  document.body.classList.add('theme-' + themeId);
  if(document.getElementById('selected-theme')){
  document.getElementById('selected-theme').innerText = `Selected Theme: ${themeId}`;
  }
  localStorage.setItem('selectedTheme', themeId);
}

window.addEventListener('DOMContentLoaded', () => {
  const savedTheme = localStorage.getItem('selectedTheme') || 'theme1';
  selectTheme(savedTheme);
});

  //<a href="data:application/epub+zip;base64,${book.epub_data}" download="${book.title}.epub">Download EPUB</a>




