        </main>
    </div>
</div>

<!-- Quill Rich Text Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
// Sidebar Mobile Toggle
const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
const adminSidebar = document.getElementById('adminSidebar');
if (sidebarToggleBtn && adminSidebar) {
    if (window.innerWidth <= 992) {
        sidebarToggleBtn.style.display = 'inline-flex';
    }
    sidebarToggleBtn.addEventListener('click', () => {
        adminSidebar.classList.toggle('open');
    });
}

// Global Delete Confirmation
function confirmDelete(url, itemLabel = 'इस आइटम') {
    if (confirm(`क्या आप वाकई ${itemLabel} को हटाना (Delete) चाहते हैं? यह क्रिया वापस नहीं ली जा सकती।`)) {
        window.location.href = url;
    }
}

// Auto Slugify Generator
function generateSlug(text) {
    return text.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '') // remove invalid chars
        .replace(/\s+/g, '-')         // collapse whitespace and replace by -
        .replace(/-+/g, '-')         // collapse dashes
        .trim();
}
</script>

</body>
</html>
