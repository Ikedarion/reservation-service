document.addEventListener("DOMContentLoaded", function() {
    const genreSelect = document.getElementById("genre");
    const addressInput = document.getElementById("address");
    const autoTagInput = document.getElementById("auto-tag");
    const previewButton = document.getElementById("preview-button");
    const previewCard = document.getElementById("preview-card");
    const previewImageTag = document.getElementById("preview-image-tag");
    const previewImageDiv = document.getElementById("preview-image");
    const editBtn = document.getElementById("edit-btn");
    const closePreviewBtn = document.getElementById("close-preview-btn");
    const closeFormBtn = document.getElementById("close-form-btn");
    const createForm = document.getElementById("create-form");
    const detail = document.getElementById("detail");
    const databaseImage = previewImageDiv.getAttribute("data-image");

    console.log(databaseImage);

    function extractPrefecture(address) {
        const pattern = /(東京都|北海道|(?:京都|大阪)府|[一-龯]{2,3}県)/;
        const match = address.match(pattern);
        return match ? match[0] : "";
    }

    function updateAutoTags() {
        const genre = genreSelect.value.trim();
        const prefecture = extractPrefecture(addressInput.value).trim();
        const autoTags = [genre && `#${genre}`, prefecture && `#${prefecture}`].filter(Boolean);
        autoTagInput.value = autoTags.join(" ");
    }

    function showPreview(event) {
        event.preventDefault();
        document.getElementById("preview-name").textContent = document.getElementById("name").value;
        document.getElementById("preview-genre").textContent = `#${genreSelect.value}`;
        document.getElementById("preview-address").textContent = `#${extractPrefecture(addressInput.value)}`;
        document.getElementById("preview-description").textContent = document.getElementById("description").value;

        const image = document.getElementById("image").files[0];
        if (image) {
            const reader = new FileReader();
            reader.onload = (e) => previewImageTag.src = e.target.result;
            reader.readAsDataURL(image);
        } else {
            previewImageTag.src = databaseImage;
        }
        previewCard.style.display = "block";
    }

    function initializeView() {
        if (createForm.classList.contains('open')) {
            detail.style.display = "none";
            createForm.style.display = "block";
        } else {
            createForm.style.display = "none";
            detail.style.display = "block";
        }
    }

    genreSelect.addEventListener("change", updateAutoTags);
    addressInput.addEventListener("input", updateAutoTags);
    previewButton.addEventListener("click", showPreview);
    closePreviewBtn.addEventListener("click", () => previewCard.style.display = "none");
    editBtn.addEventListener("click", () => {
        createForm.classList.add('open');
        initializeView();
    });
    closeFormBtn.addEventListener("click", () => {
        createForm.classList.remove('open');
        initializeView();
    });
    initializeView();
});