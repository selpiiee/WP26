// ===== Hamburger menu =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== Delete confirmation =====
function initDeleteConfirm() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-delete");
        if (!btn) return;

        const row = btn.closest("tr");
        const name = row ? row.querySelector("td")?.textContent : "this item";
        const confirmed = confirm("Are you sure you want to delete \"" + name + "\"?");
        if (confirmed && row) {
            row.remove();
        }
    });
}

// ===== Real-time table filter/search =====
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(function (row) {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(keyword) ? "" : "none";
        });
    });
}

// ===== Form validation =====
function showError(input, message) {
    removeError(input);
    const span = document.createElement("span");
    span.className = "error";
    span.textContent = message;
    input.insertAdjacentElement("afterend", span);
}

function removeError(input) {
    const next = input.nextElementSibling;
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

function initFormValidation() {
    const form = document.getElementById("add-form");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        let valid = true;

        const title = form.querySelector("[name='title'], [name='name']");
        if (title && title.value.trim() === "") {
            showError(title, "This field is required.");
            valid = false;
        } else if (title) {
            removeError(title);
        }

        const isbn = form.querySelector("[name='isbn']");
        if (isbn) {
            const isbnValue = isbn.value.trim();
            const isbnRegex = /^[0-9-]+$/;
            if (isbnValue !== "" && !isbnRegex.test(isbnValue)) {
                showError(isbn, "ISBN can only contain numbers and hyphens.");
                valid = false;
            } else {
                removeError(isbn);
            }
        }

        const author = form.querySelector("[name='author']");
        if (author && author.value.trim() === "") {
            showError(author, "Author is required.");
            valid = false;
        } else if (author) {
            removeError(author);
        }

        const year = form.querySelector("[name='year']");
        if (year) {
            const value = parseInt(year.value, 10);
            if (isNaN(value) || value < 1900 || value > 2026) {
                showError(year, "Year must be between 1900-2026.");
                valid = false;
            } else {
                removeError(year);
            }
        }

        const stock = form.querySelector("[name='stock']");
        if (stock) {
            const value = parseInt(stock.value, 10);
            if (isNaN(value) || value < 0) {
                showError(stock, "Stock cannot be negative.");
                valid = false;
            } else {
                removeError(stock);
            }
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

// ===== EXERCISE 1: Generic JSON Fetch & Render Function =====
async function loadDataToTable(jsonFileName, tbodyId, keys) {
    const tbody = document.getElementById(tbodyId);
    if (!tbody) return;

    try {
        const response = await fetch(`../data/${jsonFileName}`);
        const data = await response.json();

        tbody.innerHTML = "";

        // Exercise 3: Delay 3000ms (3 detik)
        setTimeout(() => {
            data.forEach(item => {
                const tr = document.createElement("tr");
                const cellsHTML = keys.map(key => `<td>${item[key] ?? '-'}</td>`).join('');
                
                tr.innerHTML = `
                    ${cellsHTML}
                    <td>
                        <button type="button" class="btn-delete">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }, 3000);
    } catch (error) {
        console.error(`Error loading ${jsonFileName}:`, error);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initDeleteConfirm();
    initTableFilter();
    initFormValidation();

    loadDataToTable("books.json", "books-tbody", ["title", "author", "year", "category", "stock"]);
    loadDataToTable("members.json", "members-tbody", ["name", "email", "phone"]);
});