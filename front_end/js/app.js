const categoryList = document.getElementById("category-list");
const courseContainer = document.getElementById("course-container");
const pageTitle = document.getElementById("page-title");

const apiBase = "http://api.cc.localhost";
//categories fetching
function loadCategories() {
  fetch(`${apiBase}/categories`)
    .then((res) => res.json())
    .then((data) => {
      renderCategories(data);
    });
}
//categories rendering
function renderCategories(categories, parentId = null, level = 0) {
    const list = parentId ? document.querySelector(`[data-parent="${parentId}"]`) : categoryList;
  
    categories
      .filter(cat => cat.parent == parentId)
      .forEach(cat => {
        const countText = cat.course_count > 0 ? ` (${cat.course_count})` : "";
        const li = document.createElement("li");
        li.innerHTML = `${"&nbsp;".repeat(level * 4)}<span class="category-link" data-id="${cat.id}">${cat.name}${cat.course_count > 0 ? `<span class="category-count">(${cat.course_count})</span>` : ""}</span>`;
        list.appendChild(li);
        const subUl = document.createElement("ul");
        subUl.setAttribute("data-parent", cat.id);
        subUl.classList.add("list-unstyled", "ms-3");
        list.appendChild(subUl);
        renderCategories(categories, cat.id, level + 1);
      });
      document.querySelectorAll(".category-link").forEach(el =>
        el.addEventListener("click", () => {
          const catId = el.dataset.id;
          document.querySelectorAll(".category-link").forEach(link =>
            link.classList.remove("category-selected")
          );
          el.classList.add("category-selected");
          pageTitle.innerText = el.innerText.replace(/\s*\(\d+\)$/, '');
          loadCourses(catId);
        })
      );
  }
  

//courses fetching
function loadCourses(categoryId = null) {
    const url = categoryId
    ? `${apiBase}/courses/by-category/${categoryId}`
    : `${apiBase}/courses`;
    fetch(url)
      .then((res) => res.text())
      .then((text) => {
        console.log(text);
        try {
          const data = JSON.parse(text);
          console.log(data);
          renderCourses(data);
        } catch (e) {
          console.error(e.message);
          console.error(text);
        }
      })
      .catch((err) => console.error("Fetch failed:", err));
  }
  
//courses rendering
function renderCourses(courses) {
  courseContainer.innerHTML = "";

  if (courses.length === 0) {
    courseContainer.innerHTML = "<p>No courses found.</p>";
    return;
  }
  courses.forEach(course => {
    const col = document.createElement("div");
    col.className = "col-12 col-xl-6 col-xxl-4 mb-4";

    col.innerHTML = `
        <div class="card course-card h-100 position-relative">
            <img src="${course.image_preview}" alt="${course.title}" class="card-img-top">
            <div class="category-tag">${course.category_name}</div>
             <div class="card-body">
                <h5 class="card-title text-truncate">${course.title}</h5>
                <p class="card-text">${course.description}</p>
             </div>
        </div>
        `;
    courseContainer.appendChild(col);
  });
}

loadCategories();
loadCourses();
